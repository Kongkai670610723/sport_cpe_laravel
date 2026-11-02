<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Exact Badminton ordering from Figma Frame 2
     */
    private const BADMINTON_FIGMA_ORDER = [
        17, 18, 23, 20, 25, 27, 28, 30, 21, 22, 24, 26, 19, 29, 31, 32
    ];

    public function index(Request $request)
    {
        $categorySlug = $request->get('category', 'all');
        $categories = Category::all();

        $query = Product::with(['category', 'promotions']);

        $currentCategory = null;
        if ($categorySlug !== 'all') {
            $currentCategory = Category::where('category_name', 'like', "%{$categorySlug}%")->first();
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->category_id);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQ) use ($search) {
                      $catQ->where('category_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->get('promo') === 'bogo') {
            $query->whereHas('promotions', function ($q) {
                $q->where('promo_code', 'BUY2GET1');
            });
        }

        $sort = $request->get('sort', 'default');
        if ($sort === 'asc' || $sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc' || $sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            if ($currentCategory && strtolower($currentCategory->category_name) === 'badminton' && !$request->filled('search')) {
                $badmintonList = implode(',', self::BADMINTON_FIGMA_ORDER);
                $query->orderByRaw("FIELD(product_id, {$badmintonList})");
            } else {
                $query->orderBy('product_id', 'asc');
            }
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories', 'categorySlug', 'currentCategory', 'sort'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'promotions'])->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('product_id', '!=', $product->product_id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}

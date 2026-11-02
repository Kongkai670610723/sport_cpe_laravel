<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
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
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($request->get('promo') === 'bogo') {
            $query->whereHas('promotions', function ($q) {
                $q->where('promo_code', 'BUY2GET1');
            });
        }

        $sort = $request->get('sort', 'default');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('product_id', 'asc');
        }

        $products = $query->paginate(12);

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

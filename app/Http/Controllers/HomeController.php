<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $promotions = Promotion::where('is_active', true)->get();

        $query = Product::with(['category', 'promotions']);

        // Check if user requested Buy 2 Get 1 Free filter (promo=bogo)
        if ($request->get('promo') === 'bogo') {
            $query->whereHas('promotions', function ($q) {
                $q->where('promo_code', 'BUY2GET1');
            });
        }

        // Sorting
        $sort = $request->get('sort', 'featured');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('is_featured', 'desc')->orderBy('product_id', 'asc');
        }

        $products = $query->paginate(12);

        return view('home', compact('categories', 'promotions', 'products', 'sort'));
    }
}

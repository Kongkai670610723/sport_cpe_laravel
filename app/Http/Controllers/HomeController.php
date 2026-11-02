<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;

class HomeController extends Controller
{
    /**
     * Exact Figma Frame 1 catalog order for SPORT.CPE
     */
    private const HOME_ORDERED_IDS = [
        17, 1, 65, 33, 18, 23, 20, 25, 27, 28, 30, 26,
        2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16,
        19, 21, 22, 24, 29, 31, 32, 34, 35, 36, 37, 38, 39,
        40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52,
        53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 66,
        67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79,
        80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92,
        93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104,
        105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115,
        116, 117, 118, 119, 120, 121, 122, 123, 124, 125, 126,
        127, 128
    ];

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
        $sort = $request->get('sort', 'default');
        if ($sort === 'asc' || $sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc' || $sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $idList = implode(',', self::HOME_ORDERED_IDS);
            $query->orderByRaw("FIELD(product_id, {$idList})");
        }

        $products = $query->paginate(12)->withQueryString();

        return view('home', compact('categories', 'promotions', 'products', 'sort'));
    }
}

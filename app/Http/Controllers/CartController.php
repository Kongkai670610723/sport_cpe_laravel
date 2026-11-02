<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Promotion;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0.00;
        $bogoDiscount = 0.00;

        foreach ($cart as $productId => &$item) {
            $lineSubtotal = $item['price'] * $item['quantity'];
            $subtotal += $lineSubtotal;

            // Buy 2 Get 1 Free (1 free for every 3 items of eligible products)
            $freeQty = !empty($item['promo_eligible']) ? intdiv($item['quantity'], 3) : 0;
            $itemDiscount = $freeQty * $item['price'];

            $item['free_qty'] = $freeQty;
            $item['discount_amount'] = $itemDiscount;
            $bogoDiscount += $itemDiscount;
        }

        $total = max(0, $subtotal - $bogoDiscount);

        return view('cart.index', compact('cart', 'subtotal', 'bogoDiscount', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $product = Product::with('promotions')->findOrFail($validated['product_id']);
        $qty = $validated['quantity'] ?? 1;
        $cart = session()->get('cart', []);

        $isBuy2Get1 = $product->promotions->contains('promo_code', 'BUY2GET1');

        if (isset($cart[$product->product_id])) {
            $cart[$product->product_id]['quantity'] += $qty;
        } else {
            $cart[$product->product_id] = [
                'product_id'     => $product->product_id,
                'name'           => $product->name,
                'brand'          => $product->brand,
                'price'          => (float) $product->price,
                'quantity'       => $qty,
                'image_url'      => $product->image_url,
                'promo_eligible' => $isBuy2Get1,
                'stock_quantity' => $product->stock_quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', "เพิ่ม {$product->name} ลงในตะกร้าเรียบร้อยแล้ว");
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $quantity = (int) $request->input('quantity', 1);

        if (isset($cart[$id])) {
            if ($quantity > 0) {
                $cart[$id]['quantity'] = $quantity;
            } else {
                unset($cart[$id]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'อัปเดตจำนวนสินค้าเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'ลบสินค้าออกจากตะกร้าเรียบร้อยแล้ว');
    }
}


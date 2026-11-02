<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Promotion;

class AdminController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('order_status', '!=', 'Cancelled')->sum('net_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();

        $recentOrders = Order::with(['user', 'items.product', 'promotion'])->orderBy('order_id', 'desc')->get();
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)->orderBy('stock_quantity', 'asc')->get();

        $buy2Get1Promo = Promotion::where('promo_code', 'BUY2GET1')->first();
        $bogoProducts = $buy2Get1Promo ? $buy2Get1Promo->products : collect();
        $allProducts = Product::orderBy('name', 'asc')->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'recentOrders',
            'lowStockProducts',
            'bogoProducts',
            'allProducts'
        ));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['order_status' => $request->input('order_status')]);

        return redirect()->route('admin.dashboard')->with('success', "อัปเดตสถานะออเดอร์ #{$id} เป็น {$order->order_status} เรียบร้อยแล้ว");
    }

    public function restockProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $addQty = max(1, (int) $request->input('add_quantity', 10));
        $product->increment('stock_quantity', $addQty);

        return redirect()->route('admin.dashboard')->with('success', "เติมสต็อกสินค้า #{$id} เพิ่ม +{$addQty} ชิ้น เรียบร้อยแล้ว (ปัจจุบันมี {$product->fresh()->stock_quantity} ชิ้น)");
    }

    public function toggleBogoProduct(Request $request)
    {
        $productId = (int) $request->input('product_id');
        $promo = Promotion::where('promo_code', 'BUY2GET1')->first();

        if ($promo) {
            if ($promo->products->contains('product_id', $productId)) {
                $promo->products()->detach($productId);
                return redirect()->route('admin.dashboard')->with('success', "นำสินค้าออกจากโปรโมชัน 2 แถม 1 เรียบร้อยแล้ว");
            } else {
                $promo->products()->attach($productId);
                return redirect()->route('admin.dashboard')->with('success', "เพิ่มสินค้าเข้าร่วมโปรโมชัน 2 แถม 1 เรียบร้อยแล้ว");
            }
        }

        return redirect()->route('admin.dashboard');
    }
}

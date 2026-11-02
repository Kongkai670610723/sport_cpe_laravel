<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id() ?? 1; // Default to demo user 1 if guest
        $orders = Order::with(['orderItems.product', 'promotion'])
            ->where('user_id', $userId)
            ->orderBy('order_date', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'promotion', 'user'])->findOrFail($id);
        return view('orders.complete', compact('order'));
    }

    // ACID Transaction for Placing an Order and Decrementing Stock
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'ไม่มีสินค้าในตะกร้า');
        }

        $userId = Auth::id() ?? 1;
        $user = User::find($userId);

        $order = DB::transaction(function () use ($request, $cart, $userId, $user) {
            $subtotal = 0.00;
            $bogoDiscount = 0.00;

            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $freeQty = !empty($item['promo_eligible']) ? intdiv($item['quantity'], 3) : 0;
                $bogoDiscount += $freeQty * $item['price'];
            }

            $voucherDiscount = 0.00;
            $promoId = null;
            $promoCode = $request->input('promo_code');

            if (!empty($promoCode)) {
                $promo = Promotion::where('promo_code', strtoupper($promoCode))->where('is_active', true)->first();
                if ($promo && $subtotal >= (float) $promo->min_order_amount) {
                    $promoId = $promo->promo_id;
                    if ($promo->discount_type === 'PERCENT') {
                        $voucherDiscount = ($subtotal - $bogoDiscount) * ((float) $promo->discount_value / 100);
                    } elseif ($promo->discount_type === 'FIXED') {
                        $voucherDiscount = min((float) $promo->discount_value, $subtotal - $bogoDiscount);
                    }
                }
            }

            $totalDiscount = $bogoDiscount + $voucherDiscount;
            $shippingFee = ($subtotal >= 1000) ? 0.00 : 50.00;
            $netAmount = max(0, ($subtotal - $totalDiscount) + $shippingFee);

            // 1. Create order record
            $newOrder = Order::create([
                'user_id'          => $userId,
                'promo_id'         => $promoId,
                'order_date'       => now(),
                'total_amount'     => $subtotal,
                'discount_amount'  => $totalDiscount,
                'net_amount'       => $netAmount,
                'order_status'     => 'Paid',
                'shipping_address' => $request->input('shipping_address', $user->address ?? 'ภาควิชาวิศวกรรมคอมพิวเตอร์ มหาวิทยาลัยเชียงใหม่'),
                'payment_method'   => $request->input('payment_method', 'PromptPay QR'),
            ]);

            // 2. Insert line items & decrement product stock in MySQL
            foreach ($cart as $productId => $item) {
                $freeQty = !empty($item['promo_eligible']) ? intdiv($item['quantity'], 3) : 0;
                $itemDiscount = $freeQty * $item['price'];
                $itemSubtotal = ($item['price'] * $item['quantity']) - $itemDiscount;

                OrderItem::create([
                    'order_id'      => $newOrder->order_id,
                    'product_id'    => $productId,
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['price'],
                    'item_discount' => $itemDiscount,
                    'subtotal'      => $itemSubtotal,
                ]);

                // Decrement inventory stock safely
                Product::where('product_id', $productId)->decrement('stock_quantity', $item['quantity']);
            }

            return $newOrder;
        });

        // Clear session cart
        session()->forget('cart');
        session()->forget('applied_voucher');

        return redirect()->route('orders.show', $order->order_id)->with('success', 'การสั่งซื้อเสร็จสมบูรณ์!');
    }
}

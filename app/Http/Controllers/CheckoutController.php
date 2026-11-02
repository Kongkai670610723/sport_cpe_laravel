<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'ไม่มีสินค้าในตะกร้า กรุณาเลือกสินค้าก่อนทำการชำระเงิน');
        }

        $subtotal = 0.00;
        $bogoDiscount = 0.00;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $freeQty = !empty($item['promo_eligible']) ? intdiv($item['quantity'], 3) : 0;
            $bogoDiscount += $freeQty * $item['price'];
        }

        $appliedCode = session()->get('applied_voucher', $request->get('code', ''));
        $voucherDiscount = 0.00;
        $activePromotion = null;

        if (!empty($appliedCode)) {
            $promo = Promotion::where('promo_code', strtoupper($appliedCode))
                ->where('is_active', true)
                ->first();

            if ($promo && $subtotal >= (float) $promo->min_order_amount) {
                $activePromotion = $promo;
                if ($promo->discount_type === 'PERCENT') {
                    $voucherDiscount = ($subtotal - $bogoDiscount) * ((float) $promo->discount_value / 100);
                } elseif ($promo->discount_type === 'FIXED') {
                    $voucherDiscount = min((float) $promo->discount_value, $subtotal - $bogoDiscount);
                }
            }
        }

        $shippingFee = ($subtotal >= 1000) ? 0.00 : 50.00;
        $netTotal = max(0, ($subtotal - $bogoDiscount - $voucherDiscount) + $shippingFee);

        return view('checkout.index', compact(
            'cart',
            'subtotal',
            'bogoDiscount',
            'voucherDiscount',
            'shippingFee',
            'netTotal',
            'activePromotion',
            'appliedCode'
        ));
    }
}

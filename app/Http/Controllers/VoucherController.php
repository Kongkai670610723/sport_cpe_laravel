<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class VoucherController extends Controller
{
    public function index()
    {
        $promotions = Promotion::where('is_active', true)->get();
        return view('vouchers.index', compact('promotions'));
    }

    public function apply(Request $request)
    {
        $code = strtoupper($request->input('promo_code', ''));
        $promo = Promotion::where('promo_code', $code)->where('is_active', true)->first();

        if (!$promo) {
            return back()->with('error', "ไม่พบคูปอง '{$code}' หรือคูปองหมดอายุแล้ว");
        }

        session()->put('applied_voucher', $code);

        return redirect()->route('checkout.index', ['code' => $code])->with('success', "ใช้คูปอง '{$code}' เรียบร้อยแล้ว!");
    }
}

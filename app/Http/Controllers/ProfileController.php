<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;

class ProfileController extends Controller
{
    public function show()
    {
        $userId = Auth::id() ?? 1;
        $user = User::findOrFail($userId);
        $orders = Order::with(['orderItems.product', 'promotion'])
            ->where('user_id', $userId)
            ->orderBy('order_id', 'desc')
            ->get();

        return view('profile.show', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'name'    => 'required|string|max:150',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'บันทึกข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $discount = Discount::where('discount_code', $request->code)->first();

        if (!$discount) {
            return response()->json(['success' => false, 'message' => 'Kode diskon tidak ditemukan.'], 404);
        }

        if ($request->subtotal < $discount->minimal_subtotal) {
            return response()->json([
                'success' => false,
                'message' => 'Subtotal tidak memenuhi syarat minimal untuk diskon ini.'
            ], 422);
        }

        $potongan = ($discount->discount / 100) * $request->subtotal;
        $potongan = min($potongan, $discount->maximal_discount);

        return response()->json([
            'success' => true,
            'discount_value' => round($potongan),
            'message' => 'Diskon berhasil diterapkan.'
        ]);
    }
}

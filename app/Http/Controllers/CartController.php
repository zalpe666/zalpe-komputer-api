<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getByUser($id_user)
    {
        $cartItems = Cart::where('id_user', $id_user)
            ->with(['product' => function ($query) {
                $query->select('id', 'name', 'price', 'photo', 'weight');
            }])
            ->get(['id', 'id_user', 'id_product', 'quantity']);
        $data = [];
        $subtotal = 0;
        $real_weight = 0;

        foreach ($cartItems as $item) {
            if ($item->product) {
                $total_price = $item->product->price * $item->quantity;
                $subtotal += $total_price;

                $weight = $item->product->weight * $item->quantity;
                $real_weight += $weight;

                $data[] = [
                    'id' => $item->id,
                    'id_user' => $item->id_user,
                    'id_product' => $item->id_product,
                    'quantity' => $item->quantity,
                    'total_price' => $total_price,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'price' => $item->product->price,
                        'photo' => $item->product->photo,
                        'weight' => $item->product->weight,
                    ],
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'subtotal' => $subtotal,
            'real_weight' => $real_weight,
            'total_weight' => ceil($real_weight / 1000)

        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'id_product' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        // Cari item yang sudah ada di keranjang
        $cartItem = Cart::where('id_user', $request->id_user)
            ->where('id_product', $request->id_product)
            ->first();

        // Jika item sudah ada, tambahkan 1 ke quantity
        if ($cartItem) {
            $cartItem->quantity += 1;  // Selalu tambah 1 unit
            $cartItem->save();
        } else {
            // Jika item belum ada di keranjang, buat item baru dengan quantity 1
            $cartItem = Cart::create([
                'id_user' => $request->id_user,
                'id_product' => $request->id_product,
                'quantity' => 1  // Set quantity langsung ke 1
            ]);
        }

        return response()->json(['success' => true, 'data' => $cartItem]);
    }

    public function decrease(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'id_product' => 'required|integer',
        ]);

        $cartItem = Cart::where('id_user', $request->id_user)
            ->where('id_product', $request->id_product)
            ->first();

        if (!$cartItem) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan']);
        }

        if ($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->save();
        } else {
            $cartItem->delete(); // kalau sudah 1, langsung hapus
        }

        return response()->json(['success' => true]);
    }

    // 4. Hapus item dari cart
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',  // Menambahkan validasi untuk id cart
            'id_user' => 'required|integer',  // Pastikan user_id juga valid
        ]);

        // Hapus item berdasarkan id cart dan id_user
        $deleted = Cart::where('id', $request->id)
            ->where('id_user', $request->id_user)
            ->delete();

        return response()->json(['success' => $deleted > 0]);
    }
}

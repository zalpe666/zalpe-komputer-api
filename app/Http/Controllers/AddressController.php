<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function getByUser(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|integer',
        ]);

        $addresses = Address::where('id_user', $validated['id_user'])->get();

        if ($addresses->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Alamat tidak ditemukan',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data alamat ditemukan',
            'data' => $addresses
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'type' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|string',
            'province_id' => 'required',
            'province_name' => 'required',
            'city_id' => 'required',
            'city_name' => 'required',
            'address' => 'required|string',
        ]);


        $address = Address::create([
            'id_user' => $request->id_user,
            'type' => $request->type,
            'name' => $request->name,
            'phone' => $request->phone,
            'province_id' => $request->province_id,
            'province_name' => $request->province_name,
            'city_id' => $request->city_id,
            'city_name' => $request->city_name,
            'address' => $request->address,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Address added successfully',
            'data' => $address
        ], 201);
    }
}

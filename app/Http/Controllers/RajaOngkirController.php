<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => 'd54e28a29f16ef596a5340d6c4623500',  // Gantilah dengan API key yang benar
        ])->get('https://api.rajaongkir.com/starter/province');

        return $response->json(); // Mengembalikan data provinsi
    }

    public function getCities($provinceId): mixed
    {
        $response = Http::withHeaders([
            'key' => 'd54e28a29f16ef596a5340d6c4623500',  // Gantilah dengan API key yang benar
        ])->get('https://api.rajaongkir.com/starter/city', [
            'province' => $provinceId
        ]);

        return $response->json(); // Mengembalikan data kota berdasarkan provinsi
    }

    public function getCost(Request $request)
    {
        $origin = $request->origin;  // Kota asal
        $destination = $request->destination;  // Kota tujuan
        $weight = $request->weight;  // Berat barang

        $response = Http::withHeaders([
            'key' => 'd54e28a29f16ef596a5340d6c4623500',  // Gantilah dengan API key yang benar
        ])->post('https://api.rajaongkir.com/starter/cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => 'jne', // Ganti dengan kurir yang diinginkan, seperti 'jne', 'pos', dll.
        ]);

        return $response->json(); // Mengembalikan data biaya ongkir
    }
}

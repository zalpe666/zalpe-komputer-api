<?php

namespace App\Http\Controllers;
use App\Models\Brands;
use Illuminate\Http\Request;

class BrandsController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->query('limit', 0);
        $random = $request->query('random') === 'true';

        $query = Brands::query();

        if ($random) {
            $query->inRandomOrder();
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $brands = $query->get();

        return response()->json([
            'total_brands' => $brands->count(),
            'brands' => $brands
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Products;  // Pastikan menggunakan Products, bukan Product
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function searchProducts(Request $request)
    {
        // Ambil parameter dari URL
        $category = $request->input('category');
        $brand = $request->input('brand');
        $slug = $request->input('slug');
        $sort = $request->input('sort');
        $limit = $request->input('limit');
        $random = $request->input('random');
        $search = $request->input('search'); // <-- Tambahkan ini

        // Mulai query
        $query = Products::select(
            'product.id',
            'product.name',
            'product.slug',
            'product.price',
            'product.price_default',
            'product.photo',
            'product.description',
            'product.categories',
            'product.subcategories',
            'product.brand',
            'product.weight',
            'product.discount',
            'product.discount_status',
            'product.status',
            'product.created_at',
            'product.updated_at',
            DB::raw('IFNULL(COUNT(DISTINCT transaction_detail.id), 0) AS transaction_count'),
            DB::raw('IFNULL(ROUND(AVG(transaction_detail.rating), 1), 0) AS average_rating'),
            DB::raw('IFNULL(COUNT(DISTINCT CASE WHEN transaction_detail.rating IS NOT NULL THEN transaction_detail.id END), 0) AS rated_user_count')
        )
            ->leftJoin('transaction_detail', function ($join) {
                $join->on('product.id', '=', 'transaction_detail.id_product')
                    ->where('transaction_detail.status', 'Success');
            })
            ->leftJoin('user_activity', 'product.id', '=', 'user_activity.id_product');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('product.name', 'LIKE', "%$search%")
                    ->orWhere('product.brand', 'LIKE', "%$search%")
                    ->orWhere('product.categories', 'LIKE', "%$search%");
            });
        }
        // Filter berdasarkan kategori jika ada
        if ($category) {
            $query->where('product.categories', '=', $category);
        }

        // Filter berdasarkan brand jika ada
        if ($brand) {
            $query->where('product.brand', '=', $brand);
        }

        // Filter berdasarkan slug jika ada
        if ($slug) {
            $query->where('product.slug', '=', $slug);
        }

        // Grup berdasarkan ID produk dan semua kolom yang tidak menggunakan agregat
        $query->groupBy(
            'product.id',
            'product.name',
            'product.slug',
            'product.price',
            'product.price_default',
            'product.photo',
            'product.description',
            'product.categories',
            'product.subcategories',
            'product.brand',
            'product.weight',
            'product.discount',
            'product.discount_status',
            'product.status',
            'product.created_at',
            'product.updated_at'
        );

        // Sorting produk berdasarkan parameter
        if ($sort) {
            switch ($sort) {
                case 'price-asc':
                    $query->orderBy('product.price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('product.price', 'desc');
                    break;
                case 'name-asc':
                    $query->orderBy('product.name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('product.name', 'desc');
                    break;
                case 'discounted':
                    $query->where('product.discount_status', 1)
                        ->orderBy('product.discount', 'desc');
                    break;
                default:
                    break;
            }
        }

        // Handle random jika parameter random diberikan
        if ($random === 'true') {
            $query->inRandomOrder();
        }

        // Menambahkan limit jika ada
        if ($limit) {
            $query->limit($limit);
        }

        // Eksekusi query dan ambil hasilnya
        $products = $query->get();

        // Mengembalikan response dalam format JSON
        return response()->json([
            'total_products' => $products->count(),
            'products' => $products
        ]);
    }
}

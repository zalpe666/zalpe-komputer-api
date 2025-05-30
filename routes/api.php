<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\RajaOngkirController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/brands', [BrandsController::class, 'index']);
Route::get('/products', [ProductsController::class, 'searchProducts']);

Route::get('/cart/user/{id_user}', [CartController::class, 'getByUser']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::post('/cart/decrease', [CartController::class, 'decrease']);
Route::delete('/cart/delete', [CartController::class, 'delete']);


Route::post('/discount/check', [DiscountController::class, 'check']);


Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'getProvinces']);
Route::get('/rajaongkir/cities/{provinceId}', [RajaOngkirController::class, 'getCities']);
Route::post('/rajaongkir/cost', [RajaOngkirController::class, 'getCost']);

Route::post('/address/by-user', [AddressController::class, 'getByUser']);
Route::post('/address/store', [AddressController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


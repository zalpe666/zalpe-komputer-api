<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{   
    protected $table = 'cart';
    protected $fillable = ['id_user', 'id_product', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Products::class, 'id_product');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}

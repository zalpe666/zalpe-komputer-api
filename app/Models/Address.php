<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $fillable = ['id_user', 'type', 'name', 'phone', 'province_id', 'province_name', 'city_id', 'city_name', 'address'];


}

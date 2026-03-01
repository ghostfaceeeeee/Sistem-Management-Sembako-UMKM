<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'nama_supplier',
        'alamat',
        'no_telp',
        'email'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
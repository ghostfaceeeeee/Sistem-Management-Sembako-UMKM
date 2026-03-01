<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama_barang',
        'category_id',
        'harga_beli',
        'harga_jual',
        'supplier_id',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStockAttribute(): int
    {
        $in = $this->stockTransactions()->where('type', 'in')->sum('quantity');
        $out = $this->stockTransactions()->where('type', 'out')->sum('quantity');

        return $in - $out;
    }
}

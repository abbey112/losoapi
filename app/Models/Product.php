<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'status',
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
    ];
    //scopes
    public function scopeActive($query)
    {        return $query->where('status', 'active');
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where('name', 'like',"%{$term}%");
                     
    }

    //relationships
    public function vendor() 
    {
        return $this->belongsTo(Vendor::class);
    }
    public function orders() 
    {
        return $this->hasMany(Order::class);
    }

    public function isInStock($quantity = 1) : bool
    {
        return $this->stock_quantity >= $quantity;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_email',
        'quantity',
        'unit_price',
        'total_price',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',    
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    //relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

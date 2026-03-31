<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'location',
        'reorder_level',
        'last_restocked'
    ];

    protected $casts = [
        'last_restocked' => 'date',
        'quantity' => 'integer',
        'reorder_level' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
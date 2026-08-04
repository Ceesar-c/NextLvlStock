<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'purchase_price',
        'sale_price',
        'stock',
        'minimum_stock',
        'has_tax',
        'tax_percentage',
        'is_active',
        'category_id',
        'brand_id',
        'supplier_id',
    ];

    protected $casts = [
        'has_tax' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

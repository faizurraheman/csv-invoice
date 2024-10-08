<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'part_type',
        'part_description',
        'product_info',
        'color',
        'quantity',
        'part_number',
        'single_price',
        'bulk_price',
    ];
}

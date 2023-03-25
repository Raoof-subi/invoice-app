<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'quantity', 'unit_price', 'tax', 'total', 'total_without_tax', 'total_with_tax', 'discount'
    ];
}

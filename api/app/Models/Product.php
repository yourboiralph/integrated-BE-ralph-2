<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'quantity', 'price', 'photo'
    ];

    public function formattedPrice()
    {
        return '$' . number_format($this->price, 2);
    }

    public function scopeAvailable($query)
    {
        return $query->where('quantity', '>', 0);
    }
}

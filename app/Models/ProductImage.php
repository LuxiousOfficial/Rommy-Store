<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        'product_id',
        'image',
        'is_thumbnail'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

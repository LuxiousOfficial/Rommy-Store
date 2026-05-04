<?php

namespace App\Models;

// use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasUuids;
    protected $fillable = [
        'transaction_id',
        'product_id',
        'rating',
        'review'
    ];

    public function transaction() 
    {
        return $this->belongsTo(Transaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

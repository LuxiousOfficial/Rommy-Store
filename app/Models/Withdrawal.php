<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasUuids, HasFactory;
    protected $fillable = [
        'store_ballance_id',
        'amount',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'proof',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('storeBallance.store', function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        });
    }

    public function storeBallance()
    {
        return $this->belongsTo(StoreBallance::class);
    }
}

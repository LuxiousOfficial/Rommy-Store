<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreBallanceHistory extends Model
{
    use UUID, HasFactory;
    protected $fillable = [
        'store_ballance_id',
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'remarks'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function storeBallance()
    {
        return $this->belongsTo(StoreBallance::class);
    }
}

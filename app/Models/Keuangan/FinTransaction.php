<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class FinTransaction extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'category',
        'description',
        'transaction_date',
        'attachment',
        'user_id'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2'
    ];

    //
}

<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinClaimLog extends Model
{
    use HasFactory;

    protected $table = 'fin_claim_logs';
    protected $fillable = ['fin_claim_id', 'status', 'notes', 'user_id'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

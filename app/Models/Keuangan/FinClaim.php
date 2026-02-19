<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class FinClaim extends Model
{
    protected $fillable = ['provider', 'amount', 'status', 'submitted_at', 'attachment'];

    public function logs()
    {
        return $this->hasMany(FinClaimLog::class)->orderBy('created_at', 'desc');
    }
}

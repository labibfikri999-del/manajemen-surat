<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class FinBudget extends Model
{
    protected $fillable = ['department', 'limit_amount'];
}

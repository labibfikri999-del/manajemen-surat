<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class FinAccount extends Model
{
    protected $fillable = ['name', 'type', 'balance', 'description'];
}

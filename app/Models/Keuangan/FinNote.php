<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class FinNote extends Model
{
    protected $fillable = [
        'title',
        'content',
        'date',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

<?php

namespace App\Models\Aset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'aset_id',
        'type',
        'person_in_charge',
        'origin_location',
        'destination_location',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}

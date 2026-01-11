<?php

namespace App\Models\Aset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category',
        'brand',
        'model',
        'location',
        'condition',
        'purchase_date',
        'price',
        'photo',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function mutations()
    {
        return $this->hasMany(AsetMutation::class);
    }

    public function maintenances()
    {
        return $this->hasMany(AsetMaintenance::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'unit',
        'has_serial_number',
        'qty',
        'description',
    ];

    protected $casts = [
        'has_serial_number' => 'boolean',
    ];

    public function ins(): HasMany
    {
        return $this->hasMany(AssetIn::class);
    }

    public function outs(): HasMany
    {
        return $this->hasMany(AssetOut::class);
    }

    public function serialNumbers(): HasMany
    {
        return $this->hasMany(AssetSerialNumber::class);
    }

    public function availableSerialNumbers(): HasMany
    {
        return $this->serialNumbers()->where('status', 'in');
    }
}

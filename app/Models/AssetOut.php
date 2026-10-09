<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetOut extends Model
{
    protected $fillable = ['asset_id', 'qty', 'date', 'recipient', 'notes', 'created_by'];

    protected $casts = ['date' => 'date'];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function serialNumbers(): HasMany
    {
        return $this->hasMany(AssetSerialNumber::class, 'asset_out_id');
    }
}

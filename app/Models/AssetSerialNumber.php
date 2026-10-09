<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetSerialNumber extends Model
{
    protected $fillable = ['asset_id', 'serial_number', 'status', 'asset_in_id', 'asset_out_id'];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assetIn(): BelongsTo
    {
        return $this->belongsTo(AssetIn::class);
    }

    public function assetOut(): BelongsTo
    {
        return $this->belongsTo(AssetOut::class);
    }
}

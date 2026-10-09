<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameItem extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'asset_id',
        'stok_fisik',
        'stok_sistem',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Selisih = stok_fisik - stok_sistem (bisa negatif)
     */
    public function getSelisihAttribute(): int
    {
        return $this->stok_fisik - $this->stok_sistem;
    }
}

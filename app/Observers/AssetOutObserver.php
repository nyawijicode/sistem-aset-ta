<?php

namespace App\Observers;

use App\Models\AssetOut;

class AssetOutObserver
{
    // Saat Aset Keluar dibuat -> kurangi qty di Daftar Aset
    public function created(AssetOut $assetOut): void
    {
        $assetOut->asset()->decrement('qty', $assetOut->qty);
    }

    // Saat Aset Keluar diedit -> sesuaikan selisih (delta) qty di Daftar Aset
    public function updated(AssetOut $assetOut): void
    {
        if ($assetOut->wasChanged('qty')) {
            $delta = $assetOut->qty - $assetOut->getOriginal('qty');
            // qty out naik -> stok berkurang lagi, qty out turun -> stok kembali
            $assetOut->asset()->decrement('qty', $delta);
        }
    }

    // Saat Aset Keluar dihapus -> kembalikan qty ke Daftar Aset & SN jadi 'in' lagi
    public function deleting(AssetOut $assetOut): void
    {
        $assetOut->asset()->increment('qty', $assetOut->qty);
        $assetOut->serialNumbers()->update([
            'status' => 'in',
            'asset_out_id' => null,
        ]);
    }
}

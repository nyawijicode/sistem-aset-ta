<?php

namespace App\Observers;

use App\Models\AssetIn;
use Illuminate\Validation\ValidationException;

class AssetInObserver
{
    // Saat Aset Masuk dibuat -> tambah qty di Daftar Aset
    public function created(AssetIn $assetIn): void
    {
        $assetIn->asset()->increment('qty', $assetIn->qty);
    }

    // Saat Aset Masuk diedit -> sesuaikan selisih (delta) qty di Daftar Aset
    public function updated(AssetIn $assetIn): void
    {
        if ($assetIn->wasChanged('qty')) {
            $delta = $assetIn->qty - $assetIn->getOriginal('qty');
            $assetIn->asset()->increment('qty', $delta);
        }
    }

    // Saat Aset Masuk dihapus -> kembalikan (kurangi) qty di Daftar Aset
    public function deleting(AssetIn $assetIn): void
    {
        // Jangan izinkan hapus kalau SN dari transaksi ini sudah ada yang keluar
        $sudahKeluar = $assetIn->serialNumbers()->where('status', 'out')->exists();

        if ($sudahKeluar) {
            throw ValidationException::withMessages([
                'record' => 'Tidak bisa dihapus, sebagian Serial Number pada transaksi ini sudah tercatat keluar.',
            ]);
        }

        $assetIn->asset()->decrement('qty', $assetIn->qty);
        $assetIn->serialNumbers()->delete();
    }
}

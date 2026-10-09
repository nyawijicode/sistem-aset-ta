<?php

use Illuminate\Support\Facades\Route;
use App\Models\Asset;
use App\Models\AssetIn;
use App\Models\AssetOut;
use App\Models\StockOpname;
use Illuminate\Database\Eloquent\Builder;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // Laporan Daftar Aset
    Route::get('/laporan/aset', function () {
        $query = Asset::with('serialNumbers')->orderBy('name');

        if (request('category')) {
            $query->where('category', request('category'));
        }
        if (request('has_serial_number') !== null && request('has_serial_number') !== '') {
            $query->where('has_serial_number', filter_var(request('has_serial_number'), FILTER_VALIDATE_BOOLEAN));
        }
        if (request('stok') === 'tersedia') {
            $query->where('qty', '>', 0);
        } elseif (request('stok') === 'habis') {
            $query->where('qty', 0);
        }

        $filters = [
            'category'           => request('category'),
            'has_serial_number'  => request('has_serial_number'),
            'stok'               => request('stok'),
        ];

        return view('laporan.asset', [
            'data'    => $query->get(),
            'filters' => $filters,
        ]);
    })->name('laporan.asset');

    // Laporan Aset Masuk
    Route::get('/laporan/aset-masuk', function () {
        $query = AssetIn::with(['asset', 'creator', 'serialNumbers'])->latest('date');

        if (request('date_from')) {
            $query->whereDate('date', '>=', request('date_from'));
        }
        if (request('date_to')) {
            $query->whereDate('date', '<=', request('date_to'));
        }
        if (request('asset_id')) {
            $query->where('asset_id', request('asset_id'));
        }
        if (request('supplier')) {
            $query->where('supplier', request('supplier'));
        }

        $filters = [
            'date_from' => request('date_from'),
            'date_to'   => request('date_to'),
            'asset_id'  => request('asset_id'),
            'supplier'  => request('supplier'),
        ];

        $assetLabel = null;
        if (request('asset_id')) {
            $asset = Asset::find(request('asset_id'));
            $assetLabel = $asset ? "{$asset->code} — {$asset->name}" : null;
        }

        return view('laporan.asset-in', [
            'data'       => $query->get(),
            'filters'    => $filters,
            'assetLabel' => $assetLabel,
        ]);
    })->name('laporan.asset-in');

    // Laporan Aset Keluar
    Route::get('/laporan/aset-keluar', function () {
        $query = AssetOut::with(['asset', 'creator', 'serialNumbers'])->latest('date');

        if (request('date_from')) {
            $query->whereDate('date', '>=', request('date_from'));
        }
        if (request('date_to')) {
            $query->whereDate('date', '<=', request('date_to'));
        }
        if (request('asset_id')) {
            $query->where('asset_id', request('asset_id'));
        }
        if (request('recipient')) {
            $query->where('recipient', request('recipient'));
        }

        $filters = [
            'date_from' => request('date_from'),
            'date_to'   => request('date_to'),
            'asset_id'  => request('asset_id'),
            'recipient' => request('recipient'),
        ];

        $assetLabel = null;
        if (request('asset_id')) {
            $asset = Asset::find(request('asset_id'));
            $assetLabel = $asset ? "{$asset->code} — {$asset->name}" : null;
        }

        return view('laporan.asset-out', [
            'data'       => $query->get(),
            'filters'    => $filters,
            'assetLabel' => $assetLabel,
        ]);
    })->name('laporan.asset-out');

    // Laporan Stock Opname
    Route::get('/laporan/stock-opname/{id}', function ($id) {
        $opname = StockOpname::with(['items.asset', 'creator'])->findOrFail($id);
        return view('laporan.stock-opname', compact('opname'));
    })->name('laporan.stock-opname');
});

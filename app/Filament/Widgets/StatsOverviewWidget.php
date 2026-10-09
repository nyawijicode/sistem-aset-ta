<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\AssetIn;
use App\Models\AssetOut;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalJenis = Asset::count();
        $totalStok  = (int) Asset::sum('qty');
        $stokHabis  = Asset::where('qty', 0)->count();

        $masukBulanIni  = (int) AssetIn::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('qty');

        $keluarBulanIni = (int) AssetOut::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('qty');

        // Trend chart 7 hari untuk masuk
        $masukChart = collect(range(6, 0))->map(
            fn($d) =>
            AssetIn::whereDate('date', now()->subDays($d))->sum('qty')
        )->toArray();

        $keluarChart = collect(range(6, 0))->map(
            fn($d) =>
            AssetOut::whereDate('date', now()->subDays($d))->sum('qty')
        )->toArray();

        return [
            Stat::make('Total Jenis Aset', $totalJenis)
                ->description('Jenis aset terdaftar')
                ->descriptionIcon('heroicon-m-archive-box')
                ->color('primary'),

            Stat::make('Total Stok', number_format($totalStok))
                ->description($stokHabis > 0 ? "{$stokHabis} jenis stok habis" : 'Semua stok tersedia')
                ->descriptionIcon($stokHabis > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($stokHabis > 0 ? 'warning' : 'success'),

            Stat::make('Aset Masuk Bulan Ini', number_format($masukBulanIni))
                ->description('Total qty masuk ' . now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->chart($masukChart),

            Stat::make('Aset Keluar Bulan Ini', number_format($keluarBulanIni))
                ->description('Total qty keluar ' . now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-arrow-up-tray')
                ->color('danger')
                ->chart($keluarChart),
        ];
    }
}

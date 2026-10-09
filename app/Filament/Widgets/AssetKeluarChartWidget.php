<?php

namespace App\Filament\Widgets;

use App\Models\AssetOut;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AssetKeluarChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Aset Keluar — 30 Hari Terakhir';

    protected static ?string $maxHeight = '220px';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $days   = collect(range(29, 0))->map(fn($d) => now()->subDays($d));
        $labels = $days->map(fn(Carbon $d) => $d->format('d/m'))->toArray();

        $data = $days->map(
            fn(Carbon $d) =>
            (int) AssetOut::whereDate('date', $d->toDateString())->sum('qty')
        )->toArray();

        return [
            'datasets' => [
                [
                    'label'           => 'Qty Keluar',
                    'data'            => $data,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor'     => 'rgb(239, 68, 68)',
                    'borderWidth'     => 2,
                    'tension'         => 0.3,
                    'fill'            => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

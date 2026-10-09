<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AssetResource;
use App\Models\Asset;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AssetStokKritisWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = '⚠️ Aset Stok Habis';

    public static function canView(): bool
    {
        return Asset::where('qty', 0)->exists();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Asset::query()->where('qty', 0)->orderBy('name'))
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Aset'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('unit')
                    ->label('Satuan'),
                Tables\Columns\IconColumn::make('has_serial_number')
                    ->label('SN?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Stok')
                    ->badge()
                    ->color('danger'),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn(Asset $record) => AssetResource::getUrl('view', ['record' => $record])),
            ])
            ->paginated(false);
    }
}

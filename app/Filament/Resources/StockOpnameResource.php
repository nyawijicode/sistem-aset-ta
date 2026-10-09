<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockOpnameResource\Pages;
use App\Models\Asset;
use App\Models\StockOpname;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockOpnameResource extends Resource
{
    protected static ?string $model = StockOpname::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Stock Opname';

    protected static ?string $modelLabel = 'Stock Opname';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'stock-opname';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Informasi Stock Opname')
                ->schema([
                    Infolists\Components\TextEntry::make('tanggal')
                        ->label('Tanggal')
                        ->date('d/m/Y'),
                    Infolists\Components\TextEntry::make('creator.name')
                        ->label('Dibuat Oleh'),
                    Infolists\Components\TextEntry::make('keterangan')
                        ->label('Keterangan')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Detail Stok Aset')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('items')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('asset.code')
                                ->label('Kode Aset'),
                            Infolists\Components\TextEntry::make('asset.name')
                                ->label('Nama Aset'),
                            Infolists\Components\TextEntry::make('stok_fisik')
                                ->label('Stok Fisik')
                                ->badge()
                                ->color('info'),
                            Infolists\Components\TextEntry::make('stok_sistem')
                                ->label('Stok Sistem')
                                ->badge()
                                ->color('gray'),
                            Infolists\Components\TextEntry::make('selisih')
                                ->label('Selisih')
                                ->badge()
                                ->color(fn($state) => $state < 0 ? 'danger' : ($state > 0 ? 'warning' : 'success'))
                                ->formatStateUsing(fn($state) => ($state > 0 ? '+' : '') . $state),
                        ])
                        ->columns(5),
                ]),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('tanggal')
                ->label('Tanggal Opname')
                ->required()
                ->default(now()),

            Forms\Components\Textarea::make('keterangan')
                ->label('Keterangan')
                ->columnSpanFull(),

            Forms\Components\Repeater::make('items')
                ->label('Detail Stok Per Aset')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('asset_id')
                        ->label('Aset')
                        ->options(fn() => Asset::orderBy('name')->get()->mapWithKeys(
                            fn($a) => [$a->id => "{$a->code} — {$a->name}"]
                        ))
                        ->searchable()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Set $set, ?int $state) {
                            if ($state) {
                                $asset = Asset::find($state);
                                $set('stok_sistem', $asset?->qty ?? 0);
                            } else {
                                $set('stok_sistem', 0);
                            }
                        })
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('stok_fisik')
                        ->label('Stok Fisik')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->default(0),

                    Forms\Components\TextInput::make('stok_sistem')
                        ->label('Stok Sistem')
                        ->numeric()
                        ->default(0)
                        ->readOnly()
                        ->helperText('Otomatis terisi dari stok sistem saat ini'),
                ])
                ->columns(4)
                ->addActionLabel('Tambah Aset')
                ->reorderable(false)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->label('Jumlah Aset')
                    ->counts('items')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('tanggal', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(fn(StockOpname $record) => route('laporan.stock-opname', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->isPimpinan()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->isPimpinan()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStockOpnames::route('/'),
            'create' => Pages\CreateStockOpname::route('/create'),
            'edit'   => Pages\EditStockOpname::route('/{record}/edit'),
            'view'   => Pages\ViewStockOpname::route('/{record}'),
        ];
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->isPimpinan();
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Daftar Aset';

    protected static ?string $modelLabel = 'Daftar Aset';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'daftar-aset';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Informasi Aset')
                ->schema([
                    Infolists\Components\TextEntry::make('code')->label('Kode Aset'),
                    Infolists\Components\TextEntry::make('name')->label('Nama Aset'),
                    Infolists\Components\TextEntry::make('category')->label('Kategori')->placeholder('-'),
                    Infolists\Components\TextEntry::make('unit')->label('Satuan'),
                    Infolists\Components\TextEntry::make('qty')
                        ->label('Stok Saat Ini')
                        ->badge()
                        ->color(fn(int $state) => $state > 0 ? 'success' : 'danger'),
                    Infolists\Components\IconEntry::make('has_serial_number')
                        ->label('Memiliki Serial Number?')
                        ->boolean(),
                    Infolists\Components\TextEntry::make('description')
                        ->label('Keterangan')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Daftar Serial Number')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('serialNumbers')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('serial_number')->label('Serial Number'),
                            Infolists\Components\TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->color(fn(string $state) => $state === 'in' ? 'success' : 'danger')
                                ->formatStateUsing(fn(string $state) => $state === 'in' ? 'Tersedia' : 'Keluar'),
                        ])
                        ->columns(2),
                ])
                ->visible(fn($record) => $record->has_serial_number)
                ->collapsible(),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')
                ->label('Kode Aset')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(50),

            Forms\Components\TextInput::make('name')
                ->label('Nama Aset')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('category')
                ->label('Kategori')
                ->maxLength(255),

            Forms\Components\TextInput::make('unit')
                ->label('Satuan')
                ->default('unit')
                ->required(),

            Forms\Components\Toggle::make('has_serial_number')
                ->label('Aset ini memiliki Serial Number?')
                ->helperText('Jika aktif, setiap transaksi masuk/keluar WAJIB mengisi Serial Number per unit.')
                ->disabled(fn(?Asset $record) => $record !== null)
                ->default(false),

            Forms\Components\TextInput::make('qty')
                ->label('Stok Saat Ini')
                ->numeric()
                ->default(0)
                ->disabled()
                ->dehydrated(fn(?Asset $record) => $record === null)
                ->helperText('Stok otomatis berubah lewat menu Aset Masuk / Aset Keluar.'),

            Forms\Components\Textarea::make('description')
                ->label('Keterangan')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Kode')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Nama Aset')->sortable(),
                Tables\Columns\TextColumn::make('category')->label('Kategori'),
                Tables\Columns\IconColumn::make('has_serial_number')->label('SN?')->boolean(),
                Tables\Columns\TextColumn::make('qty')->label('Stok')->sortable()->badge()
                    ->color(fn(int $state) => $state > 0 ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('unit')->label('Satuan'),
                Tables\Columns\TextColumn::make('updated_at')->label('Update Terakhir')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(fn() => Asset::whereNotNull('category')
                        ->where('category', '!=', '')
                        ->distinct()
                        ->orderBy('category')
                        ->pluck('category', 'category')
                        ->toArray())

                    ->placeholder('Semua Kategori'),

                TernaryFilter::make('has_serial_number')
                    ->label('Serial Number')
                    ->trueLabel('Punya SN')
                    ->falseLabel('Tanpa SN')
                    ->placeholder('Semua'),

                SelectFilter::make('stok')
                    ->label('Kondisi Stok')
                    ->options([
                        'tersedia' => 'Tersedia (Stok > 0)',
                        'habis'    => 'Habis (Stok = 0)',
                    ])
                    ->placeholder('Semua Stok')
                    ->query(fn(Builder $query, array $data) => match ($data['value'] ?? null) {
                        'tersedia' => $query->where('qty', '>', 0),
                        'habis'    => $query->where('qty', 0),
                        default    => $query,
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->headerActions([
                Tables\Actions\Action::make('cetak_laporan')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(function ($livewire) {
                        $filters = $livewire->tableFilters ?? [];
                        $params = [];

                        if (! empty($filters['category']['value'])) {
                            $params['category'] = $filters['category']['value'];
                        }
                        if (isset($filters['has_serial_number']['value']) && $filters['has_serial_number']['value'] !== '') {
                            $params['has_serial_number'] = $filters['has_serial_number']['value'];
                        }
                        if (! empty($filters['stok']['value'])) {
                            $params['stok'] = $filters['stok']['value'];
                        }

                        return route('laporan.asset', $params);
                    })
                    ->openUrlInNewTab(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat'),
                Tables\Actions\EditAction::make(),
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
            'index'  => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit'   => Pages\EditAsset::route('/{record}/edit'),
            'view'   => Pages\ViewAsset::route('/{record}'),
        ];
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->isPimpinan();
    }
}

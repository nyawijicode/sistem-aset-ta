<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetInResource\Pages;
use App\Models\Asset;
use App\Models\AssetIn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AssetInResource extends Resource
{
    protected static ?string $model = AssetIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    protected static ?string $navigationLabel = 'Aset Masuk';

    protected static ?string $modelLabel = 'Aset Masuk';

    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'aset-masuk';


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Detail Aset Masuk')
                ->schema([
                    Infolists\Components\TextEntry::make('date')
                        ->label('Tanggal Masuk')
                        ->date('d/m/Y'),
                    Infolists\Components\TextEntry::make('asset.code')
                        ->label('Kode Aset'),
                    Infolists\Components\TextEntry::make('asset.name')
                        ->label('Nama Aset'),
                    Infolists\Components\TextEntry::make('qty')
                        ->label('Jumlah Masuk')
                        ->badge()
                        ->color('success'),
                    Infolists\Components\TextEntry::make('supplier')
                        ->label('Supplier / Sumber')
                        ->placeholder('-'),
                    Infolists\Components\TextEntry::make('creator.name')
                        ->label('Diinput oleh'),
                    Infolists\Components\TextEntry::make('notes')
                        ->label('Catatan')
                        ->placeholder('-')
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Serial Number yang Masuk')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('serialNumbers')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('serial_number')->label('Serial Number'),
                            Infolists\Components\TextEntry::make('status')
                                ->label('Status Saat Ini')
                                ->badge()
                                ->color(fn(string $state) => $state === 'in' ? 'success' : 'danger')
                                ->formatStateUsing(fn(string $state) => $state === 'in' ? 'Tersedia' : 'Keluar'),
                        ])
                        ->columns(2),
                ])
                ->visible(fn($record) => $record->serialNumbers()->count() > 0)
                ->collapsible(),
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('asset_id')
                ->label('Aset')
                ->relationship('asset', 'name')
                ->getOptionLabelFromRecordUsing(fn(Asset $record) => "{$record->code} — {$record->name}")
                ->searchable(['code', 'name'])
                ->preload()
                ->required()
                ->live()
                ->disabled(fn(?AssetIn $record) => $record !== null) // asset tidak bisa diganti saat edit
                ->afterStateUpdated(fn(Set $set) => $set('serial_numbers', []))
                ->createOptionForm([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('code')
                                ->label('Kode Aset')
                                ->required()
                                ->unique('assets', 'code')
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
                                ->required()
                                ->default('unit'),

                            Forms\Components\Toggle::make('has_serial_number')
                                ->label('Aset ini memiliki Serial Number?')
                                ->helperText('Aktifkan jika setiap unit harus diidentifikasi dengan SN unik.')
                                ->default(false)
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('description')
                                ->label('Keterangan')
                                ->columnSpanFull(),
                        ]),
                ])

                ->createOptionUsing(function (array $data) {
                    return Asset::create(array_merge($data, ['qty' => 0]))->getKey();
                })
                ->createOptionAction(fn($action) => $action->modalHeading('Tambah Aset Baru')),

            Forms\Components\DatePicker::make('date')
                ->label('Tanggal Masuk')
                ->required()
                ->default(now()),

            Forms\Components\TextInput::make('supplier')
                ->label('Supplier / Sumber')
                ->maxLength(255),

            Forms\Components\TextInput::make('qty')
                ->label('Jumlah Masuk')
                ->numeric()
                ->minValue(1)
                ->required()
                ->live()
                // Jika aset punya SN, qty dikunci -> otomatis mengikuti jumlah SN yang diisi
                ->disabled(fn(Get $get) => static::assetHasSerial($get('asset_id')))
                ->dehydrated(true),

            Forms\Components\Repeater::make('serial_numbers')
                ->label('Serial Number (SN) — wajib diisi sejumlah qty')
                ->schema([
                    Forms\Components\TextInput::make('serial_number')
                        ->label('SN')
                        ->required()
                        ->maxLength(255),
                ])
                ->live()
                ->afterStateUpdated(function (Set $set, ?array $state) {
                    $set('qty', count($state ?? []));
                })
                ->addActionLabel('Tambah SN')
                ->reorderable(false)
                ->grid([
                    'default' => 1,
                    'md' => 2,
                    'lg' => 3,
                ])
                ->columnSpanFull()
                ->visible(fn(Get $get) => static::assetHasSerial($get('asset_id')))
                ->helperText('Setiap unit wajib punya SN unik. Contoh: 10 laptop masuk = isi 10 SN berbeda.'),

            Forms\Components\Textarea::make('notes')
                ->label('Catatan')
                ->columnSpanFull(),
        ]);
    }

    protected static function assetHasSerial(?int $assetId): bool
    {
        if (! $assetId) {
            return false;
        }

        return (bool) Asset::find($assetId)?->has_serial_number;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->label('Tanggal')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('asset.code')->label('Kode Aset'),
                Tables\Columns\TextColumn::make('asset.name')->label('Nama Aset'),
                Tables\Columns\TextColumn::make('qty')->label('Qty')->badge()->color('success'),
                Tables\Columns\TextColumn::make('supplier')->label('Supplier'),
                Tables\Columns\TextColumn::make('creator.name')->label('Diinput oleh'),
            ])
            ->filters([
                Filter::make('rentang_tanggal')
                    ->label('Rentang Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('Dari Tanggal')
                            ->displayFormat('d/m/Y'),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('Sampai Tanggal')
                            ->displayFormat('d/m/Y'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['date_from'] ?? null, fn($q, $v) => $q->whereDate('date', '>=', $v))
                            ->when($data['date_to'] ?? null, fn($q, $v) => $q->whereDate('date', '<=', $v));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['date_from'] ?? null) {
                            $indicators[] = 'Dari: ' . \Carbon\Carbon::parse($data['date_from'])->format('d/m/Y');
                        }
                        if ($data['date_to'] ?? null) {
                            $indicators[] = 'Sampai: ' . \Carbon\Carbon::parse($data['date_to'])->format('d/m/Y');
                        }
                        return $indicators;
                    }),

                SelectFilter::make('asset_id')
                    ->label('Aset')
                    ->relationship('asset', 'name')
                    ->getOptionLabelFromRecordUsing(fn(Asset $record) => "{$record->code} — {$record->name}")

                    ->preload()
                    ->placeholder('Semua Aset'),

                SelectFilter::make('supplier')
                    ->label('Supplier')
                    ->options(fn() => \App\Models\AssetIn::whereNotNull('supplier')
                        ->where('supplier', '!=', '')
                        ->distinct()
                        ->orderBy('supplier')
                        ->pluck('supplier', 'supplier')
                        ->toArray())

                    ->placeholder('Semua Supplier'),
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

                        if (! empty($filters['rentang_tanggal']['date_from'])) {
                            $params['date_from'] = $filters['rentang_tanggal']['date_from'];
                        }
                        if (! empty($filters['rentang_tanggal']['date_to'])) {
                            $params['date_to'] = $filters['rentang_tanggal']['date_to'];
                        }
                        if (! empty($filters['asset_id']['value'])) {
                            $params['asset_id'] = $filters['asset_id']['value'];
                        }
                        if (! empty($filters['supplier']['value'])) {
                            $params['supplier'] = $filters['supplier']['value'];
                        }

                        return route('laporan.asset-in', $params);
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
            'index' => Pages\ListAssetIns::route('/'),
            'create' => Pages\CreateAssetIn::route('/create'),
            'edit' => Pages\EditAssetIn::route('/{record}/edit'),
            'view' => Pages\ViewAssetIn::route('/{record}'),
        ];
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->isPimpinan();
    }
}

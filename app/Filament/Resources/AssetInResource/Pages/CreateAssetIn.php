<?php

namespace App\Filament\Resources\AssetInResource\Pages;

use App\Filament\Resources\AssetInResource;
use App\Models\Asset;
use App\Models\AssetSerialNumber;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetIn extends CreateRecord
{
    protected static string $resource = AssetInResource::class;

    protected array $pendingSerials = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        $asset = Asset::find($data['asset_id']);

        if ($asset && $asset->has_serial_number) {
            $serials = collect($data['serial_numbers'] ?? [])
                ->pluck('serial_number')
                ->filter()
                ->values();

            if ($serials->count() !== (int) $data['qty'] || $serials->count() === 0) {
                Notification::make()
                    ->danger()
                    ->title('Jumlah SN tidak sesuai qty')
                    ->body('Aset ini wajib SN. Jumlah SN yang diisi harus sama dengan qty.')
                    ->send();

                $this->halt();
            }

            if ($serials->duplicates()->isNotEmpty()) {
                Notification::make()->danger()->title('Ada SN duplikat, cek kembali.')->send();
                $this->halt();
            }

            $existing = AssetSerialNumber::where('asset_id', $asset->id)
                ->whereIn('serial_number', $serials)
                ->exists();

            if ($existing) {
                Notification::make()->danger()->title('Ada SN yang sudah terdaftar sebelumnya untuk aset ini.')->send();
                $this->halt();
            }

            $this->pendingSerials = $serials->all();
        }

        unset($data['serial_numbers']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! empty($this->pendingSerials)) {
            foreach ($this->pendingSerials as $sn) {
                AssetSerialNumber::create([
                    'asset_id' => $this->record->asset_id,
                    'serial_number' => $sn,
                    'status' => 'in',
                    'asset_in_id' => $this->record->id,
                ]);
            }
        }
    }
}

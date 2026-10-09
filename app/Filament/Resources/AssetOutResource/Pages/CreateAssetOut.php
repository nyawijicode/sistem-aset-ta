<?php

namespace App\Filament\Resources\AssetOutResource\Pages;

use App\Filament\Resources\AssetOutResource;
use App\Models\Asset;
use App\Models\AssetSerialNumber;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAssetOut extends CreateRecord
{
    protected static string $resource = AssetOutResource::class;

    protected array $pendingSerialIds = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        $asset = Asset::find($data['asset_id']);

        if (! $asset) {
            $this->halt();
        }

        if ($asset->has_serial_number) {
            $ids = collect($data['serial_number_ids'] ?? [])->filter()->values();

            if ($ids->count() !== (int) $data['qty'] || $ids->count() === 0) {
                Notification::make()->danger()->title('Pilih SN sejumlah qty.')->send();
                $this->halt();
            }

            $validCount = AssetSerialNumber::whereIn('id', $ids)
                ->where('asset_id', $asset->id)
                ->where('status', 'in')
                ->count();

            if ($validCount !== $ids->count()) {
                Notification::make()->danger()->title('Ada SN yang sudah tidak tersedia, silakan pilih ulang.')->send();
                $this->halt();
            }

            $this->pendingSerialIds = $ids->all();
        } else {
            if ((int) $data['qty'] > $asset->qty) {
                Notification::make()->danger()->title("Qty melebihi stok tersedia ({$asset->qty}).")->send();
                $this->halt();
            }
        }

        unset($data['serial_number_ids']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if (! empty($this->pendingSerialIds)) {
            AssetSerialNumber::whereIn('id', $this->pendingSerialIds)->update([
                'status' => 'out',
                'asset_out_id' => $this->record->id,
            ]);
        }
    }
}

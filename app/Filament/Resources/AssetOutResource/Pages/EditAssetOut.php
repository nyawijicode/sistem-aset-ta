<?php

namespace App\Filament\Resources\AssetOutResource\Pages;

use App\Filament\Resources\AssetOutResource;
use App\Models\AssetSerialNumber;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAssetOut extends EditRecord
{
    protected static string $resource = AssetOutResource::class;

    protected array $pendingSerialIds = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->visible(fn() => auth()->user()->isPimpinan()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['serial_number_ids'] = $this->record->serialNumbers()->pluck('id')->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $asset = $this->record->asset;

        if ($asset->has_serial_number) {
            $ids = collect($data['serial_number_ids'] ?? [])->filter()->values();

            if ($ids->count() !== (int) $data['qty'] || $ids->count() === 0) {
                Notification::make()->danger()->title('Pilih SN sejumlah qty.')->send();
                $this->halt();
            }

            $currentIds = $this->record->serialNumbers()->pluck('id');

            $stillAvailableOrCurrent = AssetSerialNumber::whereIn('id', $ids)
                ->where('asset_id', $asset->id)
                ->where(function ($q) use ($currentIds) {
                    $q->where('status', 'in')->orWhereIn('id', $currentIds);
                })
                ->count();

            if ($stillAvailableOrCurrent !== $ids->count()) {
                Notification::make()->danger()->title('Ada SN yang tidak valid/tidak tersedia.')->send();
                $this->halt();
            }

            $this->pendingSerialIds = $ids->all();
        } else {
            $availableQty = $asset->qty + $this->record->qty; // kembalikan qty lama dulu sebelum divalidasi
            if ((int) $data['qty'] > $availableQty) {
                Notification::make()->danger()->title("Qty melebihi stok tersedia ({$availableQty}).")->send();
                $this->halt();
            }
        }

        unset($data['serial_number_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        $asset = $this->record->asset;

        if ($asset->has_serial_number) {
            // Lepas semua SN lama milik transaksi ini -> jadi 'in'
            AssetSerialNumber::where('asset_out_id', $this->record->id)->update([
                'status' => 'in',
                'asset_out_id' => null,
            ]);

            // Set SN terbaru jadi 'out'
            AssetSerialNumber::whereIn('id', $this->pendingSerialIds)->update([
                'status' => 'out',
                'asset_out_id' => $this->record->id,
            ]);
        }
    }
}

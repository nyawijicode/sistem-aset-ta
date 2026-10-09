<?php

namespace App\Filament\Resources\AssetInResource\Pages;

use App\Filament\Resources\AssetInResource;
use App\Models\AssetSerialNumber;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAssetIn extends EditRecord
{
    protected static string $resource = AssetInResource::class;

    protected array $pendingSerials = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->visible(fn() => auth()->user()->isPimpinan()),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['serial_numbers'] = $this->record->serialNumbers()
            ->pluck('serial_number')
            ->map(fn($sn) => ['serial_number' => $sn])
            ->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $asset = $this->record->asset;

        if ($asset->has_serial_number) {
            $serials = collect($data['serial_numbers'] ?? [])
                ->pluck('serial_number')
                ->filter()
                ->values();

            if ($serials->count() !== (int) $data['qty'] || $serials->count() === 0) {
                Notification::make()
                    ->danger()
                    ->title('Jumlah SN tidak sesuai qty')
                    ->send();

                $this->halt();
            }

            // Jangan izinkan menghapus SN yang statusnya sudah 'out'
            $lockedSerials = $this->record->serialNumbers()->where('status', 'out')->pluck('serial_number');
            $missingLocked = $lockedSerials->diff($serials);

            if ($missingLocked->isNotEmpty()) {
                Notification::make()
                    ->danger()
                    ->title('Tidak bisa mengubah SN yang sudah tercatat keluar: ' . $missingLocked->implode(', '))
                    ->send();

                $this->halt();
            }

            $this->pendingSerials = $serials->all();
        }

        unset($data['serial_numbers']);

        return $data;
    }

    protected function afterSave(): void
    {
        $asset = $this->record->asset;

        if ($asset->has_serial_number) {
            // Hapus SN lama yang statusnya masih 'in' lalu buat ulang sesuai input terbaru
            $this->record->serialNumbers()->where('status', 'in')->delete();

            foreach ($this->pendingSerials as $sn) {
                AssetSerialNumber::firstOrCreate(
                    ['asset_id' => $asset->id, 'serial_number' => $sn],
                    ['status' => 'in', 'asset_in_id' => $this->record->id]
                );
            }
        }
    }
}

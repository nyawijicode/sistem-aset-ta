<?php

namespace App\Filament\Resources\AssetOutResource\Pages;

use App\Filament\Resources\AssetOutResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssetOut extends ViewRecord
{
    protected static string $resource = AssetOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

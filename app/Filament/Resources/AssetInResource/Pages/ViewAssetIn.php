<?php

namespace App\Filament\Resources\AssetInResource\Pages;

use App\Filament\Resources\AssetInResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssetIn extends ViewRecord
{
    protected static string $resource = AssetInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

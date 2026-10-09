<?php

namespace App\Filament\Resources\AssetInResource\Pages;

use App\Filament\Resources\AssetInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetIns extends ListRecords
{
    protected static string $resource = AssetInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\AssetOutResource\Pages;

use App\Filament\Resources\AssetOutResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetOuts extends ListRecords
{
    protected static string $resource = AssetOutResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

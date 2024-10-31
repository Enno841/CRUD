<?php

namespace App\Filament\Resources\ProvincesResource\Pages;

use App\Filament\Resources\ProvincesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProvinces extends ViewRecord
{
    protected static string $resource = ProvincesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

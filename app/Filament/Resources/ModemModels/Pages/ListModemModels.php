<?php

namespace App\Filament\Resources\ModemModels\Pages;

use App\Filament\Resources\ModemModels\ModemModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModemModels extends ListRecords
{
    protected static string $resource = ModemModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

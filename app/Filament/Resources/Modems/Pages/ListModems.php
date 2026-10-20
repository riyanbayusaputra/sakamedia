<?php

namespace App\Filament\Resources\Modems\Pages;

use App\Filament\Resources\Modems\ModemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModems extends ListRecords
{
    protected static string $resource = ModemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

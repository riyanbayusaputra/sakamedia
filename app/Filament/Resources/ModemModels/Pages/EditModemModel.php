<?php

namespace App\Filament\Resources\ModemModels\Pages;

use App\Filament\Resources\ModemModels\ModemModelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModemModel extends EditRecord
{
    protected static string $resource = ModemModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

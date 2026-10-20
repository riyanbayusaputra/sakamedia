<?php

namespace App\Filament\Resources\Modems\Pages;

use App\Filament\Resources\Modems\ModemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditModem extends EditRecord
{
    protected static string $resource = ModemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

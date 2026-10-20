<?php

namespace App\Filament\Resources\Modems\Pages;

use App\Filament\Resources\Modems\ModemResource;
use App\Models\Modem;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateModem extends CreateRecord
{
    protected static string $resource = ModemResource::class;

    protected int $addedCount = 0;

    protected int $skippedCount = 0;

    protected function handleRecordCreation(array $data): Model
    {
        // Pecah per baris, rapikan (huruf besar, tanpa spasi), buang duplikat
        $serials = collect(preg_split('/[\r\n,;]+/', (string) ($data['serial_numbers_bulk'] ?? '')))
            ->map(fn ($sn) => Str::upper(preg_replace('/\s+/', '', (string) $sn)))
            ->filter()
            ->unique()
            ->values();

        $existing = Modem::whereIn('serial_number', $serials)->pluck('serial_number');
        $fresh = $serials->diff($existing)->values();

        $this->skippedCount = $serials->count() - $fresh->count();

        if ($fresh->isEmpty()) {
            Notification::make()
                ->warning()
                ->title('Tidak ada unit baru')
                ->body('Semua SN yang dimasukkan sudah terdaftar.')
                ->send();

            $this->halt();
        }

        $first = null;

        foreach ($fresh as $serial) {
            $unit = Modem::create([
                'modem_model_id' => $data['modem_model_id'],
                'serial_number' => $serial,
                'status' => 'tersedia',
            ]);

            $first ??= $unit;
        }

        $this->addedCount = $fresh->count();

        return $first;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        $title = "{$this->addedCount} unit modem ditambahkan";

        if ($this->skippedCount > 0) {
            $title .= " ({$this->skippedCount} SN dilewati karena sudah terdaftar)";
        }

        return $title;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
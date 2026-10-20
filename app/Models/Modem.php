<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modem extends Model
{
    protected $fillable = [
        'modem_model_id',
        'serial_number',
        'status',
        'subscription_id',
        'installed_at',
    ];

    protected $casts = [
        'installed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Unit yang bukan "terpasang" tidak boleh terikat ke pelanggan mana pun
        static::saving(function (Modem $modem) {
            if ($modem->status !== 'terpasang') {
                $modem->subscription_id = null;
                $modem->installed_at = null;
            }
        });
    }

    public function modemModel()
    {
        return $this->belongsTo(ModemModel::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
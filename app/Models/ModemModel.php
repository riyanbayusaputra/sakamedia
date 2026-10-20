<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModemModel extends Model
{
    protected $fillable = ['name', 'type'];

    public function modems()
    {
        return $this->hasMany(Modem::class);
    }

    // Contoh: "ZTE F609 (GPON)"
    public function getLabelAttribute(): string
    {
        return $this->name . ' (' . strtoupper($this->type) . ')';
    }
}
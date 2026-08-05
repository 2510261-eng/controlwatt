<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consumption extends Model
{
    protected $fillable = [
        'device_id',
        'kwh',
        'date',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}

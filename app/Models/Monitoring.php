<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monitoring extends Model
{
    protected $fillable = [

        'registration_id',

        'nurse_id',

        'monitoring_date',

    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MonitoringItem::class);
    }
}

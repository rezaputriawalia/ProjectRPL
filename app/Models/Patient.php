<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'medical_record_number',
        // 'doctor_id',
        // 'room_id',
        'name',
        'nik',
        'gender',
        'birth_date',
        'address',
        'phone',
        // 'status',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    // public function doctor(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'doctor_id');
    // }

    // public function room(): BelongsTo
    // {
    //     return $this->belongsTo(Room::class);
    // }

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    // public function cppts(): HasMany
    // {
    //     return $this->hasMany(Cppt::class);
    // }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }
}

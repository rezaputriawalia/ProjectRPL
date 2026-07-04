<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'nurse_id',
        'room_id',
        'admission_date',
        'discharge_date',
        'status',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function cppts()
    {
        return $this->hasMany(Cppt::class);
    }

    public function monitorings()
    {
        return $this->hasMany(Monitoring::class);
    }
}

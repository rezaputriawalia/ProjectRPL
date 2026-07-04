<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cppt extends Model
{
    protected $fillable = [

        'registration_id',

        'doctor_id',

        'nurse_id',

        'subjective',

        'objective',

        'assessment',

        'plan',

        'selected_actions',

        'monitoring_note',

        'photo',

        'verification_status',

        'verified_by',

        'verified_at',

        'doctor_note',

    ];

    protected $casts = [
        'selected_actions' => 'array',
    ];

    protected function casts(): array
    {
        return [

            'verified_at' => 'datetime',

            'selected_actions' => 'array',

        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function actionPhotos()
    {
        return $this->hasMany(CpptActionPhoto::class);
    }
}

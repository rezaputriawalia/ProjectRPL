<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpptActionPhoto extends Model
{
    protected $fillable = [

        'cppt_id',

        'action_name',

        'category',

        'photo',

    ];

    public function cppt(): BelongsTo
    {
        return $this->belongsTo(Cppt::class);
    }
}
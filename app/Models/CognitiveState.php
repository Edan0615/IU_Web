<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CognitiveState extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'primary_function',
        'secondary_function',
        'loop_detected',
        'rotation_applied',
        'emotional_clarity_score',
    ];

    protected $casts = [
        'emotional_clarity_score' => 'float',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }
}

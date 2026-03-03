<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriber extends Model
{
    protected $fillable = [
        'telegraph_bot_id',
        'chat_id',
        'first_name',
        'last_name',
        'username',
    ];

    protected $casts = [
        'telegraph_bot_id' => 'integer',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class, 'telegraph_bot_id');
    }
}

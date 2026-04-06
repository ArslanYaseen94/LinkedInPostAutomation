<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkedInAppCredential extends Model
{
    protected $table = 'linkedin_app_credentials';

    protected $fillable = [
        'user_id',
        'client_id',
        'client_secret',
        'redirect_uri',
    ];

    protected $casts = [
        'client_id' => 'encrypted',
        'client_secret' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $table = 'posts';
    
    protected $fillable = [
        'user_id',
        'text',
        'link_url',
        'image_path',
        'image_alt_text',
        'video_path',
        'video_alt_text',
        'status',
        'scheduled_for',
        'sent_at',
        'linkedin_urn',
        'last_error',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'retry_count',
        'max_retries',
        'impressions',
        'reactions',
        'comments',
        'clicks',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(PostAuditLog::class);
    }

    public function canRetry(): bool
    {
        return $this->status === 'failed' && $this->retry_count < $this->max_retries;
    }

    public function incrementRetryCount(): void
    {
        $this->increment('retry_count');
    }

    public function logStatusChange(string $newStatus, string $reason = null, string $errorDetails = null): void
    {
        PostAuditLog::create([
            'post_id' => $this->id,
            'previous_status' => $this->status,
            'new_status' => $newStatus,
            'reason' => $reason,
            'error_details' => $errorDetails,
        ]);

        $this->update(['status' => $newStatus]);
    }

    public function getEngagementRate(): float
    {
        if ($this->impressions === 0) {
            return 0;
        }
        return (($this->reactions + $this->comments) / $this->impressions) * 100;
    }
}


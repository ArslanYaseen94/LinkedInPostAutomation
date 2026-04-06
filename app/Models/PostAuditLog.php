<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostAuditLog extends Model
{
    protected $table = 'post_audit_logs';

    protected $fillable = [
        'post_id',
        'previous_status',
        'new_status',
        'reason',
        'error_details',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}

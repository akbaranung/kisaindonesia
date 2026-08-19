<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PremiumStoryRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'bank_name',
        'account_number',
        'account_holder_name',
        'total_chapters_submitted',
        'estimated_kisa_bean_per_chapter',
        'author_notes',
        'status',
        'rejection_reason',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}

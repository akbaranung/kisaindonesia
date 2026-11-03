<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'user_id',
        'contract_version',
        'duration_years',
        'signer_name',
        'ktp_number',
        'pob',
        'dob',
        'address',
        'signed_at',
        'expires_at',
        'signature_ip'
    ];

    protected $casts = [
        'dob' => 'date',
        'signed_at' => 'datetime',
        'expires_at' => 'datetime',
        'duration_years' => 'integer',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDurationYearsAttribute($value): int
    {
        return $value ?? 5;
    }

    public function getExpiresAtAttribute($value): ?Carbon
    {
        if ($value) {
            return Carbon::parse($value);
        }

        return $this->signed_at ? Carbon::parse($this->signed_at)->addYears($this->duration_years ?? 5) : null;
    }

    public function isExpired(): bool
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }
}

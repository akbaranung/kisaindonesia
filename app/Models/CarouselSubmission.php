<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'cost_in_beans',
        'banner_image',
        'status',
        'admin_note',
        'starts_at',
        'expires_at',
        'referenceCode',
        'days'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now());
    }
}

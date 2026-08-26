<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'synopsis',
        'status',
        'slug',
        'cover_path',
        'monetization_type',
        'type',
        'pen_name_id'
    ];
    use HasFactory;

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order_number', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'category');
    }

    public function characters()
    {
        return $this->hasMany(StoryCharacter::class, 'story_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->ratings()->avg('rating'), 1) ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->ratings()->count();
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'libraries')->withTimestamps();
    }

    public function premiumRequests(): HasMany
    {
        return $this->hasMany(PremiumStoryRequest::class);
    }

    public function isPremium(): bool
    {
        return $this->monetization_type === 'premium';
    }

    public function hasPendingPremiumRequest(): bool
    {
        return $this->premiumRequests()->where('status', 'pending')->exists();
    }

    public function penName()
    {
        return $this->belongsTo(PenName::class);
    }
}

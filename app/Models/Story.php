<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'pen_name_id',
        'is_editor_choice',
        'is_mature',
        'periodic_views_count',
        'vies_ranking',
        'is_blocked',
        'rejection_reason',
        'completion_status',
        'completion_requested_at',
        'completion_reviewed_at',
        'completion_reviewed_by',
        'completion_note',
        'completion_chapters_count',
    ];

    protected $casts = [
        'is_editor_choice' => 'boolean',
        'is_blocked' => 'boolean',
    ];

    use HasFactory;

    public const MIN_CHAPTERS_FOR_TAMAT = 30;
    public const MAX_CHAPTERS_FOR_TAMAT = 60;

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
        return $this->belongsTo(PenName::class, 'pen_name_id')->withDefault([
            'name' => 'Anonim',
            'slug' => 'anonim',
        ]);
    }

    public function hasTypeChat(): bool
    {
        return $this->chapters()->where('type', 'chat')->exists();
    }

    public function carouselSubmissions()
    {
        return $this->hasMany(CarouselSubmission::class);
    }

    public function contract()
    {
        return $this->hasOne(StoryContract::class)->latest();
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'story_badge')->withPivot('order')->withTimestamps();
    }

    public function publishedChaptersCount(): int
    {
        return $this->chapters()->where('status', 'published')->count();
    }

    public function completionBlockReason(): ?string
    {
        if ($this->status !== 'published') {
            return 'Cerita harus sudah dipublikasikan.';
        }

        if ($this->is_blocked) {
            return 'Cerita sedah diblokir';
        }

        if ($this->monetization_type !== 'premium') {
            return 'Cerita harus sudah bestatus monetisasi (premium) untuk mengajukan tamat.';
        }

        if (!in_array($this->completion_status, ['ongoing', 'rejected'], true)) {
            return 'Cerita sudah diajukan tamat atau sudah tamat.';
        }

        $count = $this->publishedChaptersCount();

        if ($count < self::MIN_CHAPTERS_FOR_TAMAT) {
            return "Minimal " . self::MIN_CHAPTERS_FOR_TAMAT . " bab terbit (saat ini {$count}.";
        }

        if ($count > self::MAX_CHAPTERS_FOR_TAMAT) {
            return "Maksimal " . self::MAX_CHAPTERS_FOR_TAMAT . " bab terbit (saat ini {$count}).";
        }

        return null;
    }

    public function canRequestCompletion(): bool
    {
        return $this->completionBlockReason() === null;
    }

    public function isCompleted(): bool
    {
        return $this->completion_status === 'tamat';
    }

    public function isChapterLocked(): bool
    {
        return in_array($this->completion_status, ['pending', 'tamat'], true);
    }
}

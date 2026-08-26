<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'social_id',
        'social_type',
        'avatar',
        'kisa_bean_balance',
        'email_verified_at',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function readHistories()
    {
        return $this->hasMany(ReadHistory::class);
    }

    public function stories()
    {
        return $this->hasMany(Story::class, 'user_id');
    }

    public function savedStories()
    {
        return $this->belongsToMany(Story::class, 'libraries')->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(UserTransaction::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path && Storage::disk('public')->exists($this->profile_photo_path)) {
            return asset('storage/' . $this->profile_photo_path);
        }

        if (!empty($this->avatar)) {
            return $this->avatar;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function isFollowing(int|string $authorId): bool
    {
        if (!$authorId) {
            return false;
        }

        return $this->followings()->where('following_id', $authorId)->exists();
    }

    public function toggleFollow(int|string $authorId): array
    {
        if ($this->id == $authorId) {
            return ['status' => 'self', 'attached' => false];
        }

        $result = $this->followings()->toggle($authorId);
        $isFollowing = count($result['attached']) > 0;

        return [
            'status' => $isFollowing ? 'followed' : 'unfollowed',
            'attached' => $isFollowing
        ];
    }

    public function getFollowingCountAttribute(): int
    {
        return $this->followedPenNames()->count();
    }

    public function getTotalFollowersCountAttribute(): int
    {
        return DB::table('follows')
            ->whereIn('following_id', $this->penNames()->pluck('id'))
            ->distinct('follower_id')
            ->count('follower_id');
    }

    public function penNames()
    {
        return $this->hasMany(PenName::class);
    }

    public function followedPenNames()
    {
        return $this->belongsToMany(PenName::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    public function defaultPenName()
    {
        return $this->hasOne(PenName::class)->where('is_default', true);
    }
}

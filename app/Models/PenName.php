<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenName extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'avatar',
        'bio',
        'is_default',
    ];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function isFollowedBy(?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        return $this->followers()->where('follower_id', $user->id)->exists();
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}

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

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}

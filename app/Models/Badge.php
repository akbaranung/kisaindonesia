<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color_theme'];

    public function stories()
    {
        return $this->belongsToMany(Story::class, 'story_badge')->withPivot('order')->withTimestamps();
    }
}

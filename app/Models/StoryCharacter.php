<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryCharacter extends Model
{
    use HasFactory;

    protected $fillable = ['story_id', 'name', 'avatar_path', 'default_position'];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}

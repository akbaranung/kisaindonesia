<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadHistory extends Model
{
    protected $fillable = ['user_id', 'story_id', 'chapter_id', 'visible_chat_count'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}

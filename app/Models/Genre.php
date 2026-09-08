<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['parent_id', 'name', 'type'];

    public function parent()
    {
        return $this->belongsTo(Genre::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Genre::class, 'parent_id');
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}

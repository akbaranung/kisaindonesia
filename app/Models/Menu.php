<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'route_name', 'icon', 'order'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'menu_user');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visibility extends Model
{
    use HasFactory;

    public function posts()
    {
        return $this->hasMany(Post::class, 'visibility_id');
    }

    public function places()
    {
        return $this->hasMany(Place::class, 'visibility_id');
    }
}

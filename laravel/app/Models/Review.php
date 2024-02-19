<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'rating', 'user_id', 'place_id'];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['filepath', 'filesize'];

    protected $casts = [
        'name',
        'description',
        'file_id',
        'latitude',
        'longitude',
        'author_id',
    ];

    public function favorited() 
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
    
}
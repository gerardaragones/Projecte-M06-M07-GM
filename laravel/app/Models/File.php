<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = ['filepath', 'filesize'];

    protected $casts = [
        'filepath' => 'string',
        'filesize' => 'integer',
    ];
    public function post()
    {
        return $this->hasOne(Post::class);
    }
}   
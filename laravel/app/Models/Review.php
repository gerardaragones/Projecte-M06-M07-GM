<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['comment', 'rating', 'user_id', 'place_id'];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function canCreate(User $user)
    {
        // Verificar si el usuario actual es el autor del lugar asociado a esta revisión
        return $user->id === $this->place->author_id;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Movie;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'movie_id',
        'user_id',
        'comment_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'synopsis',
        'poster',
        'review',
        'release_date',
    ];

    /**
     * Get the comments for the movie.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

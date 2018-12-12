<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id', 'comment_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->belongsTo(Comment::class);
    }
}

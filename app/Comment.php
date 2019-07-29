<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'message', 'user_id', 'parent_id',
    ];

    public function users()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getRatingCount()
    {
        return $this->ratings()->count();
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'name', 'detail', 'year'
    ];

    public function users()
    {
        return $this->BelongsToMany(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Link;

class Category extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function links(){
        return $this->hasMany(Link::class);
    }
}

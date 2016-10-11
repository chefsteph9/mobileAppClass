<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Belief extends Model
{
    public function verses()
    {
        return $this->hasMany('App\Verse');
    }
}

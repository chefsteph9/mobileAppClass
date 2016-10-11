<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    public function beliefs()
    {
        return $this->hasMany('App\Belief');
    }
}

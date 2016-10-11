<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userName', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This mutator automatically hashes the password.
     *
     * @var string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function devices()
    {
        return $this->hasMany('App\Device');
    }

    public function licenses()
    {
        return $this->hasMany('App\License');
    }

    public function topics()
    {
        return $this->hasMany('App\Topic');
    }

    public function beliefs()
    {
        return $this->hasMany('App\Belief');
    }

    public function verses()
    {
        return $this->hasMany('App\Verse');
    }
}

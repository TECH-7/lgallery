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
        'name', 'email', 'password',
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
     * The user can have many Articles
     * 
     * @return type
     */    
    public function albums() {
        return $this->hasMany('App\Album');
    }
    
    /**
     * The user can have many Comments
     * 
     * @return type
     */    
    public function comments() {
        return $this->hasMany('App\Comment');
    }    
}

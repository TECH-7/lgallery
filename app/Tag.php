<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];    
    /**
     * Get the photos associated with the given tag.
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsToMany
     */
    public function photos() {
        return $this->belongsToMany('App\Photo');
    }   
}

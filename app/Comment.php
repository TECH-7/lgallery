<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'status',
        'photo_id',
    ];


    /**
     * A Comment belongs to a Photo
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsTo
     */
    public function photo() {
        return $this->belongsTo('App\Photo');
    }
    
    /**
     * A Comment belongs to a User
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}

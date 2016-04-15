<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'description',
        'shareable',
    ];
    
    /**
     * An Album is owned by a User
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
    
    /**
     * An Album belongs to a Category
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsTo
     */
    public function category() {
        return $this->belongsTo('App\Category');
    }
    
    /**
     * An Album has many Photos
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\HasMany
     */
    public function photos() {
        return $this->hasMany('App\Photo');
    }    
    
    /**
     * List of tag IDs associated with Article
     * @return array
     */
    public function getTagListAttribute() {
        return $this->tags->lists('id')->toArray();
    }
    
}

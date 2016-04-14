<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'sort_order'
    ];
    
    /**
     * Get the albums associated with the given tag.
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsToMany
     */
    public function albums() {
        return $this->hasMany('App\Album');
    }        
}

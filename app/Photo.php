<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Image;

class Photo extends Model
{
    protected $fillable = [
        'alt_text',
        'caption',
        'sort_order',
    ];
    
    const IMAGE_FOLDER = 'uploads/photos';
    const THUMBNAIL_FOLDER = 'uploads/photos/thumbs';
    
    /**
     * A Photo belongs to a Album
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsTo
     */
    public function album() {
        return $this->belongsTo('App\Album');
    }
    
    /**
     * A Photo can have many Comments
     * 
     * @return type
     */    
    public function comments() {
        return $this->hasMany('App\Comment');
    }
    
    /**
     * Get Tags associated with the given article
     * 
     * @return \Illuminate\Database\Eloquent\Relationships\BelongsToMany
     */
    public function tags() {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
    
    /**
     * List of tag IDs associated with Article
     * @return array
     */
    public function getTagListAttribute() {
        return $this->tags->lists('id')->toArray();
    }    
    
    public function getUrlAttribute() {
        return self::IMAGE_FOLDER . '/' . $this->filename;
    }    
    
    public function getThumbnailUrlAttribute() {
        if (!($url = $this->generateThumbnail())) {
            return false;
        }
        return $url;
    }
    
    public function generateThumbnail($force = false) {
        $url = self::THUMBNAIL_FOLDER  . '/' . $this->filename;
        
        if (!file_exists(self::IMAGE_FOLDER . '/' . $this->filename)) {
            return false;
        }
        
        if (file_exists(self::THUMBNAIL_FOLDER . '/' . $this->filename) && !$force) {
            return $url;
        }
        
        if (file_exists(self::THUMBNAIL_FOLDER)) {
            if (!is_dir(self::THUMBNAIL_FOLDER)) {
                return false;
            }
        } else {
            if (!mkdir(self::THUMBNAIL_FOLDER)) {
                return false;
            }
        }
        $img = Image::make(self::IMAGE_FOLDER  . '/' . $this->filename)
            ->fit(200, 200, function ($constraint) {
            //$constraint->aspectRatio();
            //$constraint->upsize();
        });
        $img->save($url);
        
        return $url;
    }
    
    public function scopeRanked($query) {
        $query->orderBy('sort_order', 'desc');
    }
}

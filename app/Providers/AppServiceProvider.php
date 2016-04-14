<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Photo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Photo::deleted(function ($photo) {
            unlink(Photo::IMAGE_FOLDER . '/' . $photo->filename);
            unlink(Photo::THUMBNAIL_FOLDER . '/' . $photo->filename);
        });        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

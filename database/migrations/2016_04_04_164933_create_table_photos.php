<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('album_id')->unsigned();            
            $table->string('filename');
            $table->string('alt_text');
            $table->text('caption');
            $table->integer('sort_order');
            $table->timestamps();
            // ignore: tags
            
            $table->foreign('album_id')
                ->references('id')
                ->on('albums');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('photos');
    }
}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default panel-albums">
                <div class="panel-heading">
                    <h1>Albums</h1>
                    @if (Auth::check())
                    <a href="{{ action('AlbumsController@create') }}" class="btn btn-info pull-right"><i class="fa fa-btn fa-plus-circle"></i> Create</a>
                    @endif
                    <a href="{{ action('PhotosController@index') }}" class="btn btn-info pull-right"><i class="fa fa-btn fa-eye"></i> See All Photos</a>
                </div>
                <div class="panel-body">
                    <section id="albums">
                        <ul class="row grid">
                        @foreach ($albums as $album)
                            <li class="col-xs-10 col-md-4 col-xs-offset-1 col-md-offset-0">
                                <a href="{{ action('AlbumsController@show', [$album->id]) }}">
                                    <article class="item">
                                        <header>
                                            <h1>{{ $album->name }}</h1>
                                            <h4>by {{ $album->user->name }}</h4>
                                            <h3>({{ $album->category->name }})</h3>
                                        </header>
                                        <section class="photo-wrap">
                                        @if ($firstPhoto = $album->photos()->ranked()->first())
                                            <img src="{{ $firstPhoto->thumbnailUrl }}">
                                        @else
                                            <img src="http://placehold.it/300x300&text=no%20image">
                                        @endif
                                        </section>
                                    </article>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </section>
                    {!! $albums->links() !!}
                </div>
            </div>
        </div>
    </div>
</div>                
@stop

@section('footer')
<script>
$( document ).ready(function() {
    // Make sure Title fits on current window size
    // and adjust font-size if necessary
    $( ".item > header" ).each(function( index ) {
      var h1 = $(this).find("h1");
      var width = $(this).width();
        if (h1.width() > width) {
          var fs = h1.css('font-size');
          if (fs.indexOf('px') > -1) {
            fs = parseInt(fs.replace('px', ''));
            h1.css('font-size', ( (fs-1)*(width/h1.width()) ) + 'px');
          }
        }
    });
});
</script>
@append
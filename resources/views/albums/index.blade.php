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
                                            <h2>{{ $album->name }}</h2>
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
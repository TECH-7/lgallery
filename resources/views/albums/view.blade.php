@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>{{ $album->name }}</h1>
                    <h3>&nbsp;by {{ $album->user->name }}</h3>
                @if (Auth::check() && Auth::user()->id == $album->user_id)
                    <a class="btn btn-info pull-right" href="{{ action('AlbumsController@edit', [$album->id]) }}"><i class="fa fa-btn fa-pencil-square-o"></i> Edit</a>
                @endif
                    <a class="btn btn-info pull-right" href="{{ action('AlbumsController@index') }}"><i class="fa fa-btn fa-arrow-circle-o-left"></i> Back</a>
                </div>
                <div class="panel-body">
                    <article>
                        <section>
                            <p>{{ $album->description }}</p>
                        </section>
                    </article>
                    <hr>
                    @include('partials.gallery')
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
@append

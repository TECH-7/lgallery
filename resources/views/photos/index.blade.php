@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Photos{{ isset($tag) ? ' with tag: ' . $tag : '' }}</h1>
                @if (isset($tag))
                    <a href="{{ action('PhotosController@index') }}" class="btn btn-info pull-right"><i class="fa fa-btn fa-eye"></i> See All Photos</a>
                @endif
                    <a href="{{ action('AlbumsController@index') }}" class="btn btn-info pull-right"><i class="fa fa-btn fa-book"></i> Browse Albums</a>
                </div>
                <div class="panel-body">
                @include('partials.gallery')
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
@append

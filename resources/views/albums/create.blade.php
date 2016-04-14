@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create Album</div>
                <div class="panel-body">
                    {!! Form::open( array('class' => 'form-horizontal', 'role' => 'form', 'url' => action('AlbumsController@store')) ) !!}
                        @include('albums._form', ['submitButtonText' => 'Add Album', 'cancelLink' => action('AlbumsController@index') ])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>                
@endsection
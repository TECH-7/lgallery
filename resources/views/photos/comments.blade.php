@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Comments</h1>
                    <a class="btn btn-info pull-right" href="{{ $backUrl }}"><i class="fa fa-btn fa-arrow-circle-o-left"></i> Back</a>
                </div>
                <div class="panel-body">
                    <article class="row photo-comments">
                        <section class="col-md-10">
                            <figure class="photo-wrap col-md-offset-3">
                                <img src="{{ $photo->url }}">
                            </figure>
                        </section>
                        <section class="col-md-10">
                        @if (Auth::check())
                            {!! Form::open( array('class' => 'form-horizontal', 'role' => 'form', 'url' => action('PhotosController@storeComment', $photo->id)) ) !!}
                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label class="col-md-5 control-label">Leave a Comment</label>

                                <div class="col-md-7">
                                    {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => 5]) !!}

                                    @if ($errors->has('content'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('content') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                   {!! Form::button('<i class="fa fa-btn fa-save"></i> Submit', ['type' => 'submit', 'class' => 'btn btn-primary pull-right']) !!}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @else
                            <a href="{{ action('Auth\AuthController@showLoginForm') }}">Login here to leave a comment.</a>
                        @endif
                        </section>
                        <section class="col-md-10">
                            <h2>{{ $comments->count() }} comment{{ $comments->count() == 1 ? '' : 's' }}</h2>
                        @foreach ($comments as $comment)
                            <blockquote>
                                <p>{{ $comment->content}}</p>
                                <footer>{{ $comment->user->name }}</footer>
                            </blockquote>
                        @endforeach
                        </section>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>                          
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h1 class="panel-heading">Edit Photo Information</h1>
                
                <div class='panel-body'>
                    <section class="col-md-10">
                        <div class="photo-wrap col-md-offset-3">
                            <img src="{{ $photo->url }}">
                        </div>
                    </section>
                    <section>
                        {!! Form::model($photo, ['class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'action' => ['PhotosController@update', $photo->id]]) !!}
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group{{ $errors->has('alt_text') ? ' has-error' : '' }}">
                                        {!! Form::label('alt_text', 'Alt Text', ['class' => 'col-md-4 control-label']) !!}

                                        <div class="col-md-8">
                                            {!! Form::text('alt_text', null, ['class' => 'form-control']) !!}
                                            @if ($errors->has('alt_text'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('alt_text') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {!! Form::label('tag_list', 'Tags', ['class' => 'col-md-4 control-label']) !!}

                                        <div class="col-md-8">
                                            {!! Form::select('tag_list[]', $tags, null, ['class' => 'form-control', 'id' => 'tag_list','multiple', 'style' => 'width:100%']) !!}
                                            @if ($errors->has('tag_list'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('tag_list') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>                                    

                                    <div class="form-group{{ $errors->has('sort_order') ? ' has-error' : '' }}">
                                        {!! Form::label('sort_order', 'Sort Rank', ['class' => 'col-md-4 control-label']) !!}

                                        <div class="col-md-8">
                                            {!! Form::text('sort_order', null, ['class' => 'form-control']) !!}
                                            @if ($errors->has('sort_order'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('sort_order') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>                                
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group{{ $errors->has('caption') ? ' has-error' : '' }}">
                                        {!! Form::label('caption', 'Caption', ['class' => 'col-md-2']) !!}

                                        <div class="col-md-10">
                                            {!! Form::textarea('caption', null, ['class' => 'form-control', 'rows' => 7]) !!}
                                            @if ($errors->has('caption'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('caption') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>                                
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-12">
                                    <a href="{{ action('AlbumsController@edit', $photo->album->id) }}" class="btn btn-primary"><i class="fa fa-btn fa-ban"></i> Cancel</a>
                                    {!! Form::button('<i class="fa fa-btn fa-save"></i> Save', ['type' => 'submit', 'class' => 'btn btn-primary pull-right']) !!}
                                </div>
                            </div>                        
                        {!! Form::close() !!}
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script>
  $('#tag_list').select2({
      tags: true,
      placeholder: 'Choose a Tag'
  });
</script>
@append

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h1 class="panel-heading">Edit {!! $album->title !!}</h1>
                
                <div class='panel-body'>
                    {!! Form::model($album, ['id' => 'album-edit', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'action' => ['AlbumsController@update', $album->id]]) !!}
                        @include('albums._form', ['submitButtonText' => 'Save', 'cancelLink' => action('AlbumsController@show', $album->id)])
                    {!! Form::close() !!}
                    <hr>

                    {!! Form::open(['method' => 'POST', 'action' => ['AlbumsController@upload', $album->id], 'class' => 'dropzone', 'id' => 'photo-dropzone']) !!}{!! Form::close() !!}
                    <div class="spacer20"></div>
                    <div class="pull-right">Click until all images are processed:&nbsp;&nbsp;<button class="btn btn-success" id="processQueue" disabled>Process Uploads</button></div>
                    <div class="spacer20"></div>
                    
                    {!! Form::open(['method' => 'DELETE', 'action' => ['PhotosController@massDestroy'], 'id' => 'photo-mass-delete', 'class' => 'pull-right']) !!}
                        <input type="text" class="ids-field" name="ids" style="display:none">
                        <button id="mass-delete-button" type="button" title="Delete Selected" data-toggle="modal" class="btn btn-danger" data-target="#confirmDelete" data-title="Multiple Photo Delete" data-message="Are you sure you want to delete the selected photo?" disabled>Delete Selected</button>
                    {!! Form::close() !!}
                @if (count(Auth::user()->albums) > 1)
                    {!! Form::open(['method' => 'POST', 'action' => ['AlbumsController@massMove'], 'id' => 'photo-mass-move', 'class' => 'pull-right']) !!}
                        <input type="text" class="ids-field" name="ids" style="display:none">
                        {!! Form::select('album_id', $ownerAlbums, null, ['id' => 'album_id']) !!}
                        <button id="mass-move-button" type="button" title="Move Selected" data-toggle="modal" class="btn btn-danger" data-target="#confirmDelete" data-title="Multiple Photo Move" data-message="Are you sure you want to move the selected photo?" disabled>Move Selected</button>
                    {!! Form::close() !!}
                @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Quick Tags</th>
                                <th>Caption</th>
                                <th>Alt Text</th>
                                <!--<th>Tag</th>-->
                                <th>Sort Rank</th>
                                <th>Action</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($photos as $photo)
                            <tr>
                                <td width="200"><img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->alt_text }}"></td>
                                <td width="20%">
                                    {!! Form::model($photo, ['class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'action' => ['PhotosController@update', $photo->id]]) !!}
                                        {!! Form::select('tag_list[]', $tags, null, ['class' => 'form-control tag_list', 'multiple', 'style' => 'width:100%']) !!}
                                        @if ($errors->has('tag_list'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('tag_list') }}</strong>
                                            </span>
                                        @endif
                                    {!! Form::close() !!}
                                </td>
                                <td>{{ $photo->caption }}</td>
                                <td>{{ $photo->alt_text }}</td>
                                <!--<td>&nbsp;</td>-->
                                <td>{{ $photo->sort_order }}</td>
                                <td>
                                    <a href="{{ action('PhotosController@edit', [$photo->id]) }}" title="Edit" class="action-links"><i class="fa fa-edit fa-2x"></i></span></a>
                                    {!! Form::model($photo, ['method' => 'DELETE', 'action' => ['PhotosController@destroy', $photo->id], 'class' => 'photo-delete']) !!}
                                    <button type="button" title="Delete" data-toggle="modal" class="btn-empty" data-target="#confirmDelete" data-title="Delete Photo" data-message="Are you sure you want to delete this photo?">
                                        <i class="fa fa-remove fa-2x"></i></span>
                                    </button>
                                    {{ Form::close() }}
                                </td>
                                <td>
                                    <div class="checkbox">
                                        <label>                                    
                                            <input type="checkbox" value="{{ $photo->id }}" class="select-checkbox">
                                        </label>
                                    </div>
                                </td>                                    
                            </tr>
                        @endforeach
                        </tbody>
                    </table>                
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.modal_delete')
@endsection

@section('footer')
<div style="display:none" id="ajax-msg"></div>
<script src="{{ asset('js/dropzone.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script>
    function stripParams(url) {
        var qMarkInd = url.indexOf('?');
        return (qMarkInd > -1 ? url.substr(0,qMarkInd) : url);
    }

    // Quick Tags for each photo saves immediate with Ajax
    $('.tag_list').select2({
        tags: true,
        placeholder: 'Choose a Tag'
    }).on("change", function(e) {
        var $form = $(this).closest("form");
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            success: function(responseText, statusText, xhr) {
                $("div#ajax-msg")
                        .html('<div class="text-success"><h2><i class="fa fa-check-circle-o" aria-hidden="true"></i> Saved.</h2></div>')
                        .slideDown()
                        .delay(1500)        
                        .fadeOut("slow");
            },
            error: function(xhr, statusText, errorThrown) {
                $("div#ajax-msg")
                        .html('<div class="text-danger"><h2><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Error:<p>' + errorThrown + ': ' + statusText +  '</p></h2></div>')
                        .slideDown()
                        .delay(1500)        
                        .fadeOut("slow");
            }
        });        
        
    });    
    
    // Common Delete / Mass Move Confirmation
    $('#confirmDelete').on('show.bs.modal', function (e) {
        $message = $(e.relatedTarget).attr('data-message');
        $(this).find('.modal-body p').text($message);
        $title = $(e.relatedTarget).attr('data-title');
        $(this).find('.modal-title').text($title);

        // Pass form reference to modal for submission on yes/ok
        var form = $(e.relatedTarget).closest('form');
        $(this).find('.modal-footer #confirm').data('form', form);
    });

    <!-- Form confirm (yes/ok) handler, submits form -->
    $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
        $(this).data('form').submit();
    });

  // Dropzone bulk file uploader
  Dropzone.options.photoDropzone = {
    acceptedFiles: 'image/*',
    autoProcessQueue: false,
    parallelUploads: 4,
    maxFilesize: 4000,
    dictDefaultMessage: '<h3>Drop Images here or click to upload</h3>Max. 4Mb',
    

    init: function() {
        var _this = this;
        var button = document.getElementById('processQueue');
          button.addEventListener("click", function(e) {
              _this.processQueue();
          });
        this.on("addedfile", function(file) {

          // Create the remove button
          var removeButton = Dropzone.createElement("<button>Remove file</button>");


          // Capture the Dropzone instance as closure.
          var _this = this;

          // Listen to the click event
          removeButton.addEventListener("click", function(e) {
            // Make sure the button click doesn't submit the form:
            e.preventDefault();
            e.stopPropagation();

            // Remove the file preview.
            _this.removeFile(file);
            // If you want to the delete the file on the server as well,
            // you can do the AJAX request here.
          });

          // Add the button to the file preview element.
          file.previewElement.appendChild(removeButton);
        });
      
        this.on("reset", function(file) {
          $(button).attr('disabled','');
        });
        
        this.on("addedfile", function(file) {
          $(button).removeAttr('disabled');
        });        

        this.on("queuecomplete", function() {
            //location.reload();
            window.location.href = stripParams(window.location.href) + "?success=1";
        });

    
    }
  };
  
  $(document).ready(function($) {
    // Album create successful modal to only show once
    var url = window.location.href;
    if (url.indexOf('?') > -1) {
       if (window.history != undefined && window.history.pushState != undefined) {
         window.history.pushState({}, document.title, window.location.pathname);
       }
    }
    
    // Collect selected Photo IDs for Mass Move / Delete buttons
    $('.select-checkbox').click(function() {
        var selected = [];
        $('.select-checkbox').each(function(i,el) {
            if ($(el).is(':checked')) {
                selected.push($(el).val());
            }
        });
        if (selected.length > 0) {
            $('#mass-delete-button').removeAttr('disabled');
            $('#mass-delete-button').attr('data-message', 'Are you sure you want to delete ' + selected.length + ' selected photos?');
            $('#mass-move-button').attr('data-message', 'Are you sure you want to move ' + selected.length + ' selected photos?');
        
            if ($('#album_id').val() > 0)
                $('#mass-move-button').removeAttr('disabled');
        }
        else {
            $('#mass-delete-button, #mass-move-button').attr('disabled','');
        }
        // ID collection fields .ids-field, one for each individual form
        // but both updated at the same time
        $('.ids-field').val(selected.join());
    });
    
    // To make sure the Mass move button is only activated when Album is selected
    $('#album_id').change(function() {
        if ($('#album_id').val() > 0 && $('.ids-field').val().length > 0)
            $('#mass-move-button').removeAttr('disabled');
        else
            $('#mass-move-button').attr('disabled','');
    });
  });
</script>
@append

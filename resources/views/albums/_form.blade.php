<div class="row">
    <div class="col-md-6">
        
        <div class="spacer30"></div>
        
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', 'Name', ['class' => 'col-md-2 control-label']) !!}

            <div class="col-md-10">
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <!--<div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('shareable', true) !!} Shareable
                    </label>
                </div>
            </div>
        </div>-->                    

        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
            {!! Form::label('category_id', 'Category', ['class' => 'col-md-2 control-label']) !!}

            <div class="col-md-10">
                {!! Form::select('category_id', $categories, null, ['class' => 'form-control']) !!}
                @if ($errors->has('category_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>                    

    </div>

    <div class='col-md-6'>
        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', 'Description', ['class' => 'col-md-3']) !!}

            <div class="col-md-12">
                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 7]) !!}
                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
        </div>                                 
    </div>
</div>
    
<div class="form-group">
    <div class="col-md-12">
        <a href="{{ $cancelLink }}" class="btn btn-primary"><i class="fa fa-btn fa-arrow-circle-o-left"></i> Back</a>
        {!! Form::button('<i class="fa fa-btn fa-save"></i>' . $submitButtonText, ['type' => 'submit', 'class' => 'btn btn-primary pull-right']) !!}
        {!! Form::button('<i class="fa fa-btn fa-save"></i>' . $submitButtonText . ' &amp; Continue', ['type' => 'submit', 'class' => 'btn btn-primary pull-right', 'name' => 'continue', 'value' => 'true']) !!}
        <div id="changed-warning" class="text-danger pull-right" style="display: none;">Save changes first before leaving or uploading to album:</div>
    </div>
</div>


@section('footer')
<script>
    // Show warning message when form is changed
    $("form#album-edit :input").change(function() {
        $("form#album-edit #changed-warning").show();
    });
</script>
@append
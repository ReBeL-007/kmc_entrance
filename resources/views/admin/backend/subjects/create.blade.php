@extends('admin.backend.layouts.master')
@section('title','Add Subject')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('backend/datetime-picker/jquery.datetimepicker.min.css') }}"/>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.add') }} {{ trans('cruds.subject.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.subjects.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                {{-- <label class="" for="subject">{{ trans('cruds.question.fields.subject') }}</label> --}}
                {!! Form::label('program_id', trans('cruds.program.title_singular'), ['class' => 'control-label required']) !!}
                {!! Form::select('program_id', $programs, old('program_id'), ['class' => 'form-control select2']) !!}
                @if($errors->has('program_id'))
                    <span class="text-danger">{{ $errors->first('program_id') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subject.fields.program_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="grade_id">{{ trans('cruds.subject.fields.grade') }}</label>
                <select class="form-control select2 {{ $errors->has('grade_id') ? 'is-invalid' : '' }}" name="grade_id" id="grade_id" required>
                        <option value="">Please select grade...</option>
                </select>
                @if($errors->has('grade_id'))
                    <span class="text-danger">{{ $errors->first('grade_id') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subject.fields.grade_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="" for="subject_code">{{ trans('cruds.subject.fields.subject_code') }}</label>
                <input class="form-control {{ $errors->has('subject_code') ? 'is-invalid' : '' }}" type="text" name="subject_code" id="subject_code" value="{{ old('subject_code', '') }}">
                @if($errors->has('subject_code'))
                    <span class="text-danger">{{ $errors->first('subject_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subject.fields.subject_code_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.subject.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subject.fields.title_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="" for="description">{{ trans('cruds.subject.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description" cols="30" rows="10">{{ old('description', '') }}</textarea>
                {{-- <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}"> --}}
                @if($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subject.fields.description_helper') }}</span>
            </div>

            <div class="form-group row">
                <div class="form-group">
                    {!! Form::label('deadline', 'Answer Submission Deadline:', ['class' => 'control-label']) !!}
                    {!! Form::text('deadline', old('deadline'), ['class' => '', 'placeholder' => 'select deadline' , 'id' => 'deadline', 'required', 'disabled']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('deadline'))
                        <p class="help-block">
                            {{ $errors->first('deadline') }}
                        </p>
                    @endif
                </div>
                <div class="form-group col-lg-1">
                    <input type="checkbox" name="" id="enable_deadline"> Enable
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    {{ trans('global.save') }}
                </button>
                <a href="{{route('admin.subjects.index')}}" class="btn btn-danger">{{ trans('global.cancel') }}</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // if changes is made on porgram selection
    $("#program_id").change(function(){
        var selected_id = $(this).val();
        $.ajax({
            cache:false,
            url: '{{ route('admin.subjects.getspecificgrades') }}',
            type: 'get',
            data: {programId:selected_id},
            dataType: 'json',
            beforeSend: function(request) {
                                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                            },
            success:function(data){
                var len = data.length;
                $("#grade_id").empty();

                var grade = $("#grade_id").append("<option value=''>Please select grade...</option>");

                for( var i = 0; i<len; i++){
                    var id = data[i]['id'];
                    var name = data[i]['title'];
                    grade.append("<option value='"+id+"'>"+name+"</option>");
                }
            }
        });
    });
</script>
<script src="{{ asset('backend/datetime-picker/jquery.datetimepicker.full.min.js')}}"></script>
<script>
    $('#deadline').datetimepicker({
        format:'Y-m-d H:i',
        minDate:'0', //for today use 0 or -1970/01/01)
    });
</script>
<script>
    // $('#start_at').checked()
    // $("#open").removeAttr('disabled');
    $(document).ready(function(){
        $('#enable_deadline'). click(function(){
            if($(this). is(":checked")){
            $("#deadline").removeAttr('disabled');
            }
            else if($(this). is(":not(:checked)")){
            $("#deadline").attr('disabled','true');
            }
        });
    });
</script>
@endsection

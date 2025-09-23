@extends('admin.layouts.master')


@section('content')


{{ Form::model('', ['route' => ['admin.profileicon.createsave'], 'method' => 'post']) }}

<div class="small-grid">
    @include('admin.common.message')
    <h2 class="pb-2 border-bottom a-center">Create New Profile Icon</h2> <br>
    
    <div class="form-row">
        <label for="name" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" >
    </div>

    <div class="form-row a-center btnaction">
        <button type="submit" class="btn btn-primary btn-lg">Continue</button> &nbsp; 
        <a href="{{ route('admin.profileicon.index') }}" class="btn btn-secondary btn-lg backbtn">Back</a>
    </div>
</div>
    
{{ Form::close() }}


@endsection




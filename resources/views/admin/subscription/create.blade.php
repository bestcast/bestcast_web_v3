@extends('admin.layouts.master')


@section('content')


{{ Form::model('', ['route' => ['admin.subscription.createsave'], 'method' => 'post']) }}

<div class="small-grid">
    @include('admin.common.message')
    <h2 class="pb-2 border-bottom a-center">Create New Subscription</h2>

    <div class="form-row">
        <label for="excerpt" class="form-label">Status</label>
        <div class="mb-3 form-check form-switch">
          {{Form::hidden('status',0)}}<input class="form-check-input" type="checkbox" name="status" role="switch" @if(old('status')) checked="" @endif />
        </div>
    </div>
    
    <div class="form-row">
        <label for="name" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" >
    </div>
    

    <div class="form-row a-center btnaction">
        <a href="{{ route('admin.subscription.index') }}" class="btn btn-secondary btn-lg backbtn">Back</a> &nbsp; 
        <button type="submit" class="btn btn-primary btn-lg">Continue</button>
    </div>
</div>
    
{{ Form::close() }}


@endsection




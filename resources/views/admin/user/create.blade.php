@extends('admin.layouts.master')


@section('content')




{{ Form::model('', ['route' => ['admin.user.createsave'], 'method' => 'post']) }}
<div class="small-grid">
    @include('admin.common.message')
    <h2 class="pb-2 border-bottom a-center">Create New User</h2>

    <div class="form-row">
        <label for="firstname">First Name</label>
        <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') }}" >
    </div>

    <div class="form-row">
        <label for="lastname">Last Name</label>
        <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') }}" >
    </div>

    <div class="form-row">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" >
    </div>

    <div class="form-row">
      <label for="excerpt" class="form-label">Type</label>
      {{ Form::select('type', App\User::type(), old('type'), array('class' => 'form-select')); }}
    </div>


    <div class="form-row a-center">
        <button type="submit" class="btn btn-primary btn-lg">Continue</button> &nbsp; 
        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary btn-lg backbtn">Back</a>
    </div>
</div>
{{ Form::close() }}
@endsection




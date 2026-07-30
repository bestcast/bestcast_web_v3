@extends('admin.layouts.master')

@section('content')
<div class="container">
    @include('admin.common.message')
    <h2 class="pb-2 border-bottom">Site Settings</h2>

    {{ Form::open(['route' => 'admin.settings.save', 'method' => 'post']) }}
        <div class="form-row mt-4">
            <label class="form-label" for="new_release_days_limit">New Release — Days Limit<em>*</em></label>
            @php ($val = old('new_release_days_limit', $newReleaseDays))
            <select class="form-select selectbox-default" name="new_release_days_limit" id="new_release_days_limit">
                @php ($options = [10, 15, 25, 30])
                @foreach($options as $option)
                    <option value="{{ $option }}" @if($val == $option) selected="selected" @endif>{{ $option }} days</option>
                @endforeach
            </select>
            <small class="text-muted">Number of days a movie stays in the "New Releases" block after its release date.</small>
        </div>

        <div class="form-row col-md-12 mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    {{ Form::close() }}
</div>
@endsection
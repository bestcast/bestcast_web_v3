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
        <div class="form-row mt-4">
            <label class="form-label">Login/Register Poster Background — Release Range</label>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label" for="poster_month_from">From Month</label>
                    @php ($mFrom = old('poster_month_from', $posterMonthFrom))
                    <select class="form-select selectbox-default" name="poster_month_from" id="poster_month_from">
                        <option value="">-- Month --</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @if($mFrom == $m) selected="selected" @endif>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="poster_year_from">From Year</label>
                    @php ($yFrom = old('poster_year_from', $posterYearFrom))
                    <select class="form-select selectbox-default" name="poster_year_from" id="poster_year_from">
                        <option value="">-- Year --</option>
                        @for($y = date('Y'); $y >= 2000; $y--)
                            <option value="{{ $y }}" @if($yFrom == $y) selected="selected" @endif>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="poster_month_to">To Month</label>
                    @php ($mTo = old('poster_month_to', $posterMonthTo))
                    <select class="form-select selectbox-default" name="poster_month_to" id="poster_month_to">
                        <option value="">-- Month --</option>
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" @if($mTo == $m) selected="selected" @endif>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="poster_year_to">To Year</label>
                    @php ($yTo = old('poster_year_to', $posterYearTo))
                    <select class="form-select selectbox-default" name="poster_year_to" id="poster_year_to">
                        <option value="">-- Year --</option>
                        @for($y = date('Y'); $y >= 2000; $y--)
                            <option value="{{ $y }}" @if($yTo == $y) selected="selected" @endif>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <small class="text-muted">Only movies with a release date in this month/year range are used in the login/register background. Leave blank to include all.</small>
        </div>

        <div class="form-row col-md-12 mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    {{ Form::close() }}
</div>
@endsection
@extends('admin.layouts.master')


@section('content')
    @include('admin.common.message')

    <div class="card">
      <div class="hLTImg"><img src="{{ Lib::publicImgSrc($model->urlkey)  }}" /></div>
      <div class="card-body">
        <h5 class="card-title">Title: {{ $model->title  }}</h5>
        <i>{{ $model->alt  }}</i>
        <p class="card-text">{!! $model->excerpt  !!}</p>
        <p class="card-text">Original Filename: {!! $model->filename  !!}</p>
        <p class="card-text">URL: {!! Lib::publicImgSrc($model->urlkey) !!}</p>
        @include('admin.common.userinfo') 
        <div class="form-row mt-3">
            <a href="{{ route('admin.media.index') }}" class="btn btn-secondary backbtn">Back</a> &nbsp;
            <a href="{{ Lib::publicImgSrc($model->urlkey)  }}" target="_blank" class="btn btn-info">View Image</a>
            <a href="{{ route('admin.media.edit',$model->id) }}" class="btn btn-primary">Edit</a> &nbsp;
        </div>
      </div>
    </div>


@endsection




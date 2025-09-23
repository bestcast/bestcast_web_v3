@extends('admin.layouts.master')


@section('content')
<section class="hLTSec"><div class="hLTGrid">
    @include('admin.common.message')

    <h2 class="pb-2 border-bottom">{{ $model->title  }} </h2>

    <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
        <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary">URL Key: {{ ($model->urlkey)  }}</strong>
            <strong class="d-inline-block mb-2 text-primary">Type: {{ ($model->type)  }}</strong>
            <strong class="d-inline-block mb-2 text-primary">Status: {{ ($model->status)  }}</strong>
            <div class="content">
                <p class="card-text mb-auto">{!! $model->excerpt  !!}</p>
            </div>
            @include('admin.common.userinfo') 
            <div class="form-row">
                <a href="{{ route('admin.post.index') }}" class="btn btn-primary backbtn">Back</a> &nbsp;
                <a href="{{ route('admin.post.edit',$model->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
        <div class="col-auto d-none d-lg-block">
          <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em"></text></svg>
        </div>
      </div>
</div></section>
@endsection




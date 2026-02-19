@extends('admin.layouts.master')

@section('content')

@include('admin.common.message')

<h2 class="pb-3 border-bottom d-flex justify-content-between align-items-center">
    <span>
        Episodes - {{ $season->title }}
    </span>

    <a href="{{ route('admin.episodes.autoCreate',$season->id) }}"
       class="btn btn-success">
       + Add Episode
    </a>
</h2>

@if($model->count() > 0)

<div class="txtcard image">
    <div class="row">
        @foreach($model as $item)
        <div class="col-4 col-xxl-3">
            <div class="fw-bold" style="text-align: center;">
                {{ $item->title }}
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-image"
                        style="background-image: url({{
                            !empty($season->webseries->thumbnail)
                            ? Lib::publicImgSrc($season->webseries->thumbnail->urlkey)
                            : 0
                        }});">
                        <!-- <div class="d-flex">
                            @if($item->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </div> -->
                    </div>
                    <div class="card-act">
                        <a href="{{ route('admin.episodes.edit', [$season->webseries->id,$season->id,$item->id]) }}"class="btn btn-primary btn-sm">Edit Episode</a>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endif

@endsection

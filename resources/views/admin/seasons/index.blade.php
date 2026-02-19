@extends('admin.layouts.master')
@section('content')
{{ Form::model($webseries, ['route' => ['admin.webseries.createsave'], 'method' => 'post']) }}
    <h2 class="pb-3 border-bottom">
      Seasons <a href="{{ route('admin.seasons.autoCreate', $webseries->id) }}"
           class="btn btn-success float-right addnewbtn">
           + Add Season
        </a>
    </h2>
    @if($webseries->seasons->count() > 0)
        <div class="txtcard image">
            <div class="row">
                @foreach($webseries->seasons as $item)
                    <div class="col-4 col-xxl-3">
                        <div class="card-text fw-bold">
                            {{ $item->title }}
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-image"
                                    style="background-image: url({{
                                        !empty($webseries->thumbnail)
                                        ? Lib::publicImgSrc($webseries->thumbnail->urlkey)
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
                                    <!-- <a href="{{ route('admin.webseries.edit',$item->id) }}"
                                       class="btn btn-primary btn-sm">
                                       Edit
                                    </a> -->
                                    <a href="{{ route('admin.episodes.index',$item->id) }}"
                                       class="btn btn-primary btn-sm">
                                       Episodes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
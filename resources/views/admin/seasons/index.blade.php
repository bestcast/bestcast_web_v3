@extends('admin.layouts.master')
@section('content')
{{ Form::model($webseries, ['route' => ['admin.webseries.createsave'], 'method' => 'post']) }}
    <h2 class="pb-3 border-bottom d-flex justify-content-between align-items-center">
        <span>
            Seasons
        </span>

        <div  class="d-flex gap-2">
            <a href="{{ route('admin.seasons.autoCreate', $webseries->id) }}"
               class="btn btn-success float-right addnewbtn">
               + Add Season
            </a>
            <a href="{{ route('admin.webseries.index') }}"
               class="btn btn-secondary">
               <- Back
            </a>
        </div>
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
                                    <!-- <a href="{{ route('admin.seasons.delete', [$webseries->id, $item->id]) }}"
                                       class="btn btn-outline-danger btn-sm btn-delete-copy-{{ $item->id }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#delete{{ $item->id }}">
                                       Delete
                                    </a> -->

                                    @php
                                        $delid  = $item->id;
                                        $delurl = route('admin.seasons.delete', [$webseries->id, $item->id]);
                                        $episodeCount = $item->episodes()->count();
                                        $deleteMessage = "Deleting this season will also remove <strong>{$episodeCount}</strong> episodes.";
                                    @endphp

                                    @include('admin.common.modaldelete')
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
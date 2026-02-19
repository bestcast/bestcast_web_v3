@extends('admin.layouts.master')
@section('content')
    @include('admin.common.message')
    <h2 class="pb-3 border-bottom">
        Web Series <a href="{{ route('admin.webseries.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
        @if(Request::getQueryString())
        <a href="{{ route('admin.webseries.index') }}" class="btn btn-warning float-right addnewbtn">Clear Filter</a>
        @endif
    </h2>
    <div class="row g-2 applyfilter">
      @include('admin.common.filter.searchlist')
      @include('admin.common.filter.genre')
      @include('admin.common.filter.language')
      @include('admin.common.filter.sortorder')
    </div>
    @if(!empty($model->total()))
        <div class="txtcard image">
            <div class="row">
                @foreach($model->items() as $item)
                    <div class="col-4 col-xxl-3 ">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-image" style="background-image: url({{ !empty($item->thumbnail)?Lib::publicImgSrc($item->thumbnail->urlkey):0 }});">
                                    <div class="d-flex">                        
                                        @if($item->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Disabled</span>
                                        @endif                        
                                    </div>
                                </div>
                                <div class="card-text">{{ $item->title }}</div>
                                <div class="card-act">
                                    <a href="{{ route('admin.webseries.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="{{ route('admin.seasons.index', $item->id) }}" 
                                       class="btn btn-info btn-sm">
                                       Add Seasons
                                    </a>
                                    <a href="{{ route('admin.webseries.delete',$item->id) }}" class="btn btn-outline-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>   
                                    @php
                                        $delid = $item->id;
                                        $delurl = route('admin.webseries.delete',$item->id);
                                        $seasonCount = $item->seasons()->count();
                                        $episodeCount = \App\Models\Episode::whereHas('season', function($q) use ($item) {
                                            $q->where('webseries_id', $item->id);
                                        })->count();
                                        $deleteMessage = "Deleting this will remove 
                                        <strong>{$seasonCount}</strong> seasons and 
                                        <strong>{$episodeCount}</strong> episodes.";
                                    @endphp
                                    @include('admin.common.modaldelete')
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="d-flex justify-content-center mt-5 paginationCt">
            <div class="d-flex">
                {{ $model->onEachSide(1)->links() }}
            </div>
        </div>
    @else
        @include('admin.common.noresult')
    @endif
@endsection
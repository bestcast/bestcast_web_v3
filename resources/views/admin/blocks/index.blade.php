@extends('admin.layouts.master')
@section('content')
    @include('admin.common.message')
    <h2 class="pb-3 border-bottom">
        Blocks <a href="{{ route('admin.blocks.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>
    
    <div class="row g-2 applyfilter">
      @include('admin.common.filter.searchlist', ['searchLabel' => 'Block Name'])
      @include('admin.common.filter.pagefilter')
    </div>
    @if(!empty($model->total()))
    <div class="txtcard image txt-right">
        <div class="row">
        @foreach($model->items() as $item)
        <div class="col-4 col-xxl-3 ">
            @php($imgurl=!empty($item->thumbnail)?Lib::publicImgSrc($item->thumbnail->urlkey):Lib::placeholder('movie'))
            <div class="card">
              <div class="card-body">
                <div class="card-title card-image" style="background-image:url({{ $imgurl  }});position:relative;">
                    <span class="sortorder-tag" style="position:absolute;top:8px;left:8px;color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;">Sort order: {{ $item->sortorder }}</span>
                    <span class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}" style="position:absolute;top:8px;right:8px;">
                        {{ $item->status ? 'Active' : 'Disabled' }}
                    </span>
                    <div class="d-flex overlay justify-content-center ">
                        <div class="d-flex align-self-center">
                            {{ $item->title }}
                        </div>
                    </div>
                </div>
                <div class="card-act d-flex justify-content-between align-items-center">
                    <span class="text-muted">Page: {{ !empty($item->page) ? $item->page->title : '-' }}</span>
                    <span>
                        <a href="{{ route('admin.blocks.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        @php($protectedTitles = ['continue watching', 'watch it again'])
                        @if(!in_array(strtolower($item->title), $protectedTitles))
                            <a href="{{ route('admin.blocks.delete',$item->id) }}" class="btn btn-outline-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>
                        @endif
                    </span>
                </div>
                  @php ($delid=$item->id)
                  @php ($delurl=route('admin.blocks.delete',$item->id))
                  @include('admin.common.modaldelete')
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
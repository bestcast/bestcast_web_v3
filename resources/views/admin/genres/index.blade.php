@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Genres <a href="{{ route('admin.genres.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>
    

    <div class="row g-2 applyfilter">
      @include('admin.common.filter.searchlist')
    </div>

    @if(!empty($model->total()))
    <div class="txtcard image tiny txt-right">
        <div class="row">
        @foreach($model->items() as $item)
        <div class="col-4 col-xxl-3 ">
            <div class="card">
              <div class="card-body">
                <div class="card-title card-image" style="background-image:url({{ Lib::placeholder('movie')  }})"><div class="d-flex">
                    @if($item->status)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-danger">Disabled</span>
                    @endif
                    {{ $item->title }}
                </div></div>
                <div class="card-act">
                    <a href="{{ route('admin.genres.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <a href="{{ route('admin.genres.delete',$item->id) }}" class="btn btn-outline-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>
                </div>
                  @php ($delid=$item->id)
                  @php ($delurl=route('admin.genres.delete',$item->id))
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




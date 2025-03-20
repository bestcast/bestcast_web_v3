@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Media <a href="{{ route('admin.media.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>

    
    <div class="row g-2 applyfilter">
      @include('admin.common.filter.searchlist')
    </div>
    
    @if(!empty($model->total()))

        <div class="txtcard image small media">
            <div class="row">
            @foreach($model->items() as $item)
            <div class="col-4 col-xxl-3 ">
                <div class="card">
                  <div class="card-body">
                    <div class="card-image" style="background-image: url({{ !empty($item->urlkey)?Lib::publicImgSrc($item->urlkey):0 }});"><div class="d-flex overlay justify-content-center "><div class="d-flex align-self-center">
                        <div class="card-act">
                            <a href="{{ route('admin.media.view',$item->id) }}" class="btn btn-success btn-sm">View</a>
                            <a href="{{ route('admin.media.delete',$item->id) }}" class="btn btn-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>  
                            <a href="{{ route('admin.media.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a> 
                              @php ($delid=$item->id)
                              @php ($delurl=route('admin.media.delete',$item->id))
                              @include('admin.common.modaldelete')
                        </div>
                    </div></div></div>
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




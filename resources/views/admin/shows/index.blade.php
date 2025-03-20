@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Shows <a href="{{ route('admin.shows.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>
    
    @if(!empty($model->total()))
    <div class="txtcard">
        @foreach($model->items() as $item)
            <div class="card">
              <div class="card-body">
                <div class="card-title"><div class="d-flex">
                    @if($item->status)
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-danger">Disabled</span>
                    @endif
                    {{ $item->title }}
                </div></div>
                <div class="card-act">
                <a href="{{ route('admin.shows.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                <a href="{{ route('admin.shows.delete',$item->id) }}" class="btn btn-outline-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>   
                </div>
                  @php ($delid=$item->id)
                  @php ($delurl=route('admin.shows.delete',$item->id))
                  @include('admin.common.modaldelete')
              </div>
            </div>
        @endforeach
    </div>
    @endif


@endsection




@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Subscription <a href="{{ route('admin.subscription.create') }}" class="btn btn-secondary float-right addnewbtn">+ Add New</a>
    </h2>
    
    @if(!empty($model->total()))
        <div class="pricebox">
          <div class="card-deck mb-3 mt-4">

            <div class="row">
            @foreach($model->items() as $item)
            <div class="col-4 col-xxl-3 ">
                <div class="card mb-4 box-shadow  text-center">
                  <div class="card-header">
                    <h4 class="my-0 font-weight-normal">{{ $item->title }}</h4>
                  </div>
                  <div class="card-body">
                    @if($item->status)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Disabled</span>
                    @endif
                    @if($item->tagtext)
                        <span class="badge bg-secondary">{{ $item->tagtext }}</span>
                    @endif
                    @if(!empty($item->before_price) && $item->before_price>$item->price)
                        <h3 class="card-title pricing-card-title"><span class="strike">₹{{ $item->before_price }}</span></h3>
                    @endif
                    <h3 class="card-title pricing-card-title">₹{{ $item->price }} <small class="text-muted">/ {{ App\Models\Subscription::getDurationText($item) }}</small></h3>
                    @if(!empty($item->content))
                        <div class="content">
                            {!! $item->content !!}
                        </div>
                    @endif
                    <div class="btnaction">
                        <a href="{{ route('admin.subscription.edit',$item->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('admin.subscription.delete',$item->id) }}" class="btn btn-outline-danger btn-sm btn-delete-copy-{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#delete{{ $item->id }}">Delete</a>   
                          @php ($delid=$item->id)
                          @php ($delurl=route('admin.subscription.delete',$item->id))
                          @include('admin.common.modaldelete')
                    </div>
                  </div>
                </div>
            </div>
            @endforeach
            </div>
          </div>
        </div>

    @endif


@endsection




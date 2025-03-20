@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Transaction
    </h2>

    @if(!empty($data->total()))
            <div class="txtcard image">
                <div class="row"><div class="col-12">
                <table  class="table">
                  <tr class="header">
                    <td>Title</td>
                    <td>Status</td>
                    <td>Razorpay ID</td>
                    <td>Price</td>
                    <!-- <td>Counts</td> -->
                    <td>Action</td>
                  </tr>
                    @foreach($data->items() as $item)
                      <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ App\Models\Transaction::status($item->status) }}</td>
                        <td>{{ empty($item->razorpay_subscription_id)?$item->razorpay_order_id:$item->razorpay_subscription_id }}</td>
                        <td>{{ $item->price }}</td>
                        <!-- <td>{{ $item->counts }}</td> -->
                        <td><a href="{{ route('admin.user.edit',$item->user_id) }}" class="btn btn-primary btn-sm">User Details</a> <a href="{{ route('admin.subscription.edit',$item->subscription_id) }}" class="btn btn-primary btn-sm">Plan</a></td>
                      </tr>
                    @endforeach
                </table>
                </div>
            </div></div>
            <div class="d-flex justify-content-center mt-5 paginationCt">
                <div class="d-flex">
                    {{ $data->onEachSide(1)->links() }}
                </div>
            </div>
    @endif


@endsection




@extends('admin.layouts.master')


@section('content')


    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        Transaction
    </h2>

    @if(!empty($data->total()))
            <div class="txtcard image">
                <form method="GET" action="" id="searchForm" class="mb-3">
                    <div class="d-flex justify-content-end">
                        <div style="width: 300px;">
                            <input 
                                type="text" 
                                name="search" 
                                id="searchInput"
                                class="form-control" 
                                placeholder="Search by name, phone, title..." 
                                value="{{ request('search') }}"
                            >
                        </div>
                    </div>
                </form>

                <div class="row"><div class="col-12">
                <table  class="table">
                  <tr class="header">
                    <td>Title</td>
                    <td>Status</td>
                    <td>Name</td>
                    <td>Phone Number</td>
                    <td>Created At</td>
                    <td>Expiry Date</td>
                    <td>Razorpay ID</td>
                    <td>Price</td>
                    <!-- <td>Counts</td> -->
                    <td>Action</td>
                  </tr>
                    @foreach($data->items() as $item)
                      <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ App\Models\Transaction::status($item->status) }}</td>
                        <td>{{ $item->user->name ?? 'N/A' }} </td>
                        <td>{{ $item->user->phone ?? 'N/A' }} </td>
                        <td><small>Txn At: {{ $item->created_at }}</small></td>
                        <td>Exp: {{ $item->user->plan_expiry ?? 'N/A' }} </td>
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

<script>
    let timer = null;

    document.getElementById('searchInput').addEventListener('keyup', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            document.getElementById('searchForm').submit();
        }, 400); //Delay (400ms) to avoid too many requests
    });
</script>

@endsection




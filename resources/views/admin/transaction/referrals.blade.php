@extends('admin.layouts.master')

@section('content')

    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">
        BMP Referral Attribution
    </h2>

    @if(!empty($data->total()))
        <div class="txtcard image">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <span class="text-dark">Total Referred: {{ $data->total() }}</span>
                    <span class="text-success"> | Paid: {{ $paidCount }}</span>
                    <span class="text-warning"> | Unpaid: {{ $data->total() - $paidCount }}</span>
                </h4>
                <form method="GET" action="" id="searchForm" class="mb-3">
                    <div class="d-flex justify-content-end">
                        <div style="width: 300px;">
                            <input
                                type="text"
                                name="search"
                                id="searchInput"
                                class="form-control"
                                placeholder="Search by referral code, name, phone..."
                                value="{{ request('search') }}"
                            >
                        </div>&nbsp;&nbsp;
                        <div style="width: 180px;">
                            <select name="event_filter" class="form-control" onchange="this.form.submit()">
                                <option value="">All</option>
                                <option value="sent" {{ request('event_filter') == 'sent' ? 'selected' : '' }}>Event Sent</option>
                                <option value="pending" {{ request('event_filter') == 'pending' ? 'selected' : '' }}>Event Pending</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row"><div class="col-12">
            <table class="table">
              <tr class="header">
                <td>S.No</td>
                <td>Customer</td>
                <td>Phone</td>
                <td>Plan</td>
                <td>Amount</td>
                <td>Payment Status</td>
                <td>Referral Code</td>
                <td>Event Sent</td>
                <td>Event Time</td>
              </tr>
                @foreach($data->items() as $item)
                  <tr>
                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                    <td>{{ $item->user->name ?? 'N/A' }}</td>
                    <td>{{ $item->user->phone ?? 'N/A' }}</td>
                    <td>{{ $item->title }}</td>
                    <td>₹{{ $item->price }}</td>
                    <td>
                        @if($item->bmp_paid_event_sent)
                            <span style="background:green;color:white;padding:4px 8px;border-radius:5px;">Paid</span>
                        @else
                            <span style="background:orange;color:white;padding:4px 8px;border-radius:5px;">Unpaid</span>
                        @endif
                    </td>
                    <td>{{ $item->referral_code }}</td>
                    <td>
                        @if($item->bmp_paid_event_sent)
                            <span style="background:green;color:white;padding:4px 8px;border-radius:5px;">Yes</span>
                        @else
                            <span style="background:grey;color:white;padding:4px 8px;border-radius:5px;">No</span>
                        @endif
                    </td>
                    <td>{{ $item->bmp_event_sent_at ? $item->bmp_event_sent_at->format('d M Y, h:i A') : '-' }}</td>
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
    @else
        <p>No BMP-referred transactions found.</p>
    @endif

<script>
    let timer = null;
    document.getElementById('searchInput').addEventListener('keyup', function () {
        clearTimeout(timer);
        timer = setTimeout(function () {
            document.getElementById('searchForm').submit();
        }, 400);
    });
</script>

@endsection
@extends('layouts.myaccount')

@section('header-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/auth/logout.js') }}?v=1" defer></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.swal2-popup .swal-pagination {
    text-align: center;
    margin-top: 15px;
}

.swal2-popup .swal-pagination button {
    margin: 3px;
    padding: 6px 12px;
    border: none;
    background: #007bff;
    color: white;
    cursor: pointer;
}

.swal2-popup .swal-pagination button.active {
    background: #28a745;
}
.swal2-close {
    color: #000 !important;
    font-size: 28px !important;
    font-weight: bold;
}

.swal2-close:hover {
    color: #333 !important;
}

</style>
@endsection

@section('content')
<section class="hLTSec account twocolmn accpage"><div class="hLTGrid">
        	<div class="hLTColm hLTColm100 content-area"><div class="in">
        		@include('common.message')
        	<style>
        		.pfMenu > .icon{background-color:#000000 !important;background-image: url({{ url('/') }}/img/icon/website/account-white.png) !important;}
        		.item.backarrow,.item.manage,.item.account,.item.help{display: none !important;}
        		.edu-blog.blog-type-2,.edu-blog.blog-type-2:hover{background: #000;}
        		.edu-blog.blog-type-2 .inner .content .title{margin-top: 0px;}
        	</style>

            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-12">
                    	<h1>Movies List</h1>
                    </div>
                    <div class="col-lg-12">
                        <div class="row g-5 mb--20">
                        	@foreach($movies as $movie)
                            <!-- Start Blog Grid  -->
                            <div class="col-lg-4 col-md-6 col-12" dataid="{{ $movie->id }}">
                                <div class="edu-blog blog-type-2 radius-small">
                                    <div class="inner">
                                        <div class="thumbnail">
                                                <img src="{{ Lib::img($movie->thumbnail->urlkey) }}" alt="">
                                        </div>
                                        <div class="content">
                                            <h5 class="title">{{ $movie->title }}</h5>
                                            @php
                                                $stats = App\Models\UsersMovies::getProducerMovieCount($movie->id);
                                            @endphp
                                            
                                            <div class="blog-card-bottom">
                                                    <span>Total Watch Time: {{ $stats['watch_minutes'] }} Minutes</span>
                                                    <span>Views: {{ $stats['views_count'] }}</span>
                                                    <button class="btn btn-primary getReportBtn mt-2"
                                                        style="font-size:14px;padding:10px 11px;border-radius:8px;"
                                                        data-movie-id="{{ $movie->id }}"
                                                        data-movie-title="{{ $movie->title }}">
                                                        <i class="fa fa-chart-bar"></i> Get Report
                                                    </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Blog Grid  -->
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        	</div></div>
</div></section>

<script>
$(document).on('click', '.getReportBtn', function () {

    let movieId = $(this).data('movie-id');
    let movieTitle = $(this).data('movie-title');

    loadReport(movieId, movieTitle, 1);

});
function applyDateFilter(movieId, movieTitle)
{
    let fromDate = document.getElementById('from_date').value;
    let toDate = document.getElementById('to_date').value;

    loadReport(movieId, movieTitle, 1, fromDate, toDate);
}
function clearDateFilter(movieId, movieTitle)
{
    document.getElementById('from_date').value = '';
    document.getElementById('to_date').value = '';

    // reload report without filters
    loadReport(movieId, movieTitle, 1, '', '');
}
function loadReport(movieId, movieTitle, page, fromDate = '', toDate = '')
{
     $.get(`/producer/movie-report/${movieId}`, {
            page: page,
            from_date: fromDate,
            to_date: toDate
    }, function(res){

        let startSerial = (res.current_page - 1) * res.per_page;

        let html = `

        <div style="background:#fff;padding:10px;border-radius:5px;">

        <!-- DATE FILTER -->
        <div class="row align-items-end mb-3 g-2">

            <div class="col-md-4">
                <label for="from_date" class="form-label">From</label>
                <input type="date" id="from_date" value="${fromDate}" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="to_date" class="form-label">To</label>
                <input type="date" id="to_date" value="${toDate}" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
    
                <button type="button"
                    class="btn btn-success"
                    style="height:40px;flex:2;"
                    onclick="applyDateFilter(${movieId}, '${movieTitle}')">
                    Search
                </button>

                <button type="button"
                    class="btn btn-secondary"
                    style="height:40px;flex:2;"
                    onclick="clearDateFilter(${movieId}, '${movieTitle}')">
                    Clear
                </button>

                <button type="button"
                    class="btn btn-primary"
                    style="height:40px; flex:2; white-space: nowrap;"
                    onclick="downloadReport(${movieId}, '${fromDate}', '${toDate}')">
                    Download
                </button>
            </div>
        </div>

        <table class="table table-bordered table-striped">

        <thead>
        <tr>
            <th>S.No</th>
            <th>User ID</th>
            <th>Total Streaming Time</th>
            <th>Watch %</th>
            <th>From Date</th>
            <th>To Date</th>
            <th>Status</th>
        </tr>
        </thead>

        <tbody>
        `;

        // Table rows
        res.data.forEach(function(row, index){
            let rowClass = row.is_view == 1 ? 'view-row' : '';
            html += `
            <tr class="${rowClass}">
                <td>${startSerial + index + 1}</td>
                <td>${row.user_id}</td>
                <td>${new Date(row.watch_time * 1000).toISOString().substr(11, 8)}</td>
                <td>${row.watch_percentage}%</td>
                <td>${row.created_at.split(' ')[0]}</td>
                <td>${row.updated_at.split(' ')[0]}</td>
                <td>
                    ${row.is_view == 1 
                        ? '<span style="color:green;font-weight:bold;">✔ View</span>' 
                        : '<span style="color:#999;">--</span>'}
                </td>
            </tr>
            `;

        });

        // CLOSE TABLE
        html += `</tbody></table></div>`;

        // ADD PAGINATION
        html += `<div class="swal-pagination">`;

        // Previous button
        if(res.current_page > 1)
        {
            html += `
            <button onclick="loadReport(${movieId}, '${movieTitle}', ${res.current_page - 1}, '${fromDate}', '${toDate}')">
                Previous
            </button>`;
        }

        // Page numbers
        for(let i = 1; i <= res.last_page; i++)
        {
            let active = i == res.current_page ? "active" : "";
            html += `
                <button class="${active}"
                onclick="loadReport(${movieId}, '${movieTitle}', ${i},
                '${fromDate}', '${toDate}')">
                ${i}
                </button>`;
        }

        // Next button
        if(res.current_page < res.last_page)
        {
            html += `
            <button onclick="loadReport(${movieId}, '${movieTitle}', ${res.current_page + 1})">
                Next
            </button>`;
        }

        html += `</div>`;
        html += `
        <div style="margin-top:15px; padding:10px; background:#f8f9fa; border-left:4px solid #28a745; font-size:14px;">
            <b>Note:</b><br>
            A view is counted when a full movie is streamed for at least one hour. 
            For movies shorter than one hour, a complete stream counts as one view. 
            Revenue is calculated based on the agreed terms and conditions at the time of signing.
        </div>
        `;
        // SHOW POPUP
        Swal.fire({
            title: movieTitle + " Report",
             html: `
                <div style="font-size:16px;margin-bottom:10px;">
                    <b>Views:</b> ${res.views_count} &nbsp;&nbsp;
                </div>

                ${html}
            `,
            width: 800,
            background: "#ffffff",
            color: "#000000",
            showConfirmButton: false,
            showCloseButton: true
        });

    });
}
function downloadReport(movieId, fromDate = '', toDate = '')
{
    let url = `/producer/movie-report-download/${movieId}?from_date=${fromDate}&to_date=${toDate}`;
    // Trigger download
    window.open(url, '_blank');
}
</script>
@endsection
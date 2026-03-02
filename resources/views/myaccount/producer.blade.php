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
                                            <!-- Views: {{ $stats['views_count'] }} -->
                                            <div class="blog-card-bottom">
                                                Watch Time: {{ $stats['watch_minutes'] }} Minutes<br>
                                                <br>
                                                <button class="btn btn-primary btn-lg getReportBtn"
                                                    style="font-size:12px;padding:2px 11px;border-radius:8px;"
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
        <div class="row align-items-end mb-3">

            <div class="col-md-4">
                <label for="from_date" class="form-label">From</label>
                <input type="date" id="from_date" value="${fromDate}" class="form-control">
            </div>

            <div class="col-md-4">
                <label for="to_date" class="form-label">To</label>
                <input type="date" id="to_date" value="${toDate}" class="form-control">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-success w-100" style="height:40px;font-size: 18px;" onclick="applyDateFilter(${movieId}, '${movieTitle}')">
                    Search
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
        </tr>
        </thead>

        <tbody>
        `;

        // Table rows
        res.data.forEach(function(row, index){

            html += `
            <tr>
                <td>${startSerial + index + 1}</td>
                <td>${row.user_id}</td>
                <td>${new Date(row.watch_time * 1000).toISOString().substr(11, 8)}</td>
                <td>${row.watch_percentage}%</td>
                <td>${row.created_at}</td>
                <td>${row.updated_at}</td>
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
            showConfirmButton: false
        });

    });
}
</script>
@endsection



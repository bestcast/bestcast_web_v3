@extends('admin.layouts.master')

@section('style-theme')
<style type="text/css">
    .pgCont {padding: 40px 30px;}
    .pgCont > .container-fluid{ background:transparent; padding:0px; box-shadow:none;}
</style>
<!--apexchart js-->
<script type='text/javascript' src="{{ asset('admin/js/apexcharts.js') }}"></script>
<!-- https://apexcharts.com/javascript-chart-demos/line-charts/data-labels/ -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  var counters = document.querySelectorAll('.counter-value');  
  counters.forEach(function(counter) {
    var target = +counter.dataset.count;
    var count = 0;
    var speed = 100; // Change this value to adjust animation speed
    
    var updateCount = function() {
      var increment = target / speed;
      count += increment;
      if (count < target) {
        counter.textContent = Math.ceil(count);
        setTimeout(updateCount, 1);
      } else {
        counter.textContent = target;
      }
    };    
    updateCount();
  });
});
</script>
<style type="text/css">
.counter {text-align: center;margin-bottom: 20px;}
.counter-value { font-size: 24px;color: #333; margin-bottom: 5px; animation: count 2s ease-in-out forwards;}
@keyframes count {
  from {  opacity: 0;transform: translateY(-20px);  }
  to { opacity: 1;transform: translateY(0);  }
}
</style>

@endsection

@section('title')
    Dashboard
@endsection

@section('content')

    @include('admin.common.message')



    <div class="countsBox row">
        @if (Route::has('admin.genres.index'))
        <div class="col-sm-3 color-1">
            <a href="{{ route('admin.genres.index') }}">     
            <div class="card justify-content-center icon-genre">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\Models\Genres::count() }}">0</div>
                    <div class="card-label">Genres</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.languages.index'))
        <div class="col-sm-3 color-2">
            <a href="{{ route('admin.languages.index') }}">  
            <div class="card justify-content-center icon-language">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\Models\Languages::count() }}">0</div>
                    <div class="card-label">Languages</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.movies.index'))
        <div class="col-sm-3 color-3">
            <a href="{{ route('admin.movies.index') }}">  
            <div class="card justify-content-center icon-movie">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\Models\Movies::count() }}">0</div>
                    <div class="card-label">Movies</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.page.index'))
        <div class="col-sm-3 color-4">
            <a href="{{ route('admin.page.index') }}">  
            <div class="card justify-content-center icon-page">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\Models\Post::count() }}">0</div>
                    <div class="card-label">Pages</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.user.index'))
        <div class="col-sm-3 color-5">
            <a href="{{ route('admin.user.index') }}">  
            <div class="card justify-content-center icon-users">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\User::where('type',0)->count() }}">0</div>
                    <div class="card-label">Users</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.user.producer'))
        <div class="col-sm-3 color-6">
            <a href="{{ route('admin.user.producer') }}">  
            <div class="card justify-content-center icon-user">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\User::where('type',3)->count() }}">0</div>
                    <div class="card-label">Producer</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.transactions.index'))
        <div class="col-sm-3 color-7">
            <a href="{{ route('admin.transactions.index') }}">  
            <div class="card justify-content-center icon-payment ">
                <div class="card-section counter">
                    <div class="card-count counter-value" data-count="{{ App\Models\Transaction::getList()->total() }}">0</div>
                    <div class="card-label">Transactions</div>
                </div>
            </div>
            </a>
        </div>
        @endif
        @if (Route::has('admin.transactions.index'))
        <div class="col-sm-3 color-8">
            <a href="{{ route('admin.transactions.index') }}">  
            <div class="card justify-content-center icon-transactions">
                <div class="card-section">
                    <div class="card-count">{{ App\Models\Transaction::getRevenueTotal() }}</div>
                    <div class="card-label">Revenue</div>
                </div>
            </div>
            </a>
        </div>
        @endif
    </div>


    <div class="row">
        <div class="col-6">
        <div class="card shdowbx">
            <div class="card-body">
                <div class="card-title">
                    <h6 class="mb-3 text-15 grow">Users Subscribed</h6>
                </div>
                <div id="chart"></div>
                <style>#chart { max-width: 500px; margin: 35px auto;}</style>

                <?php
$tList=App\Models\Transaction::getUserList();
$tarr=[];
foreach($tList as $tData){
    $tarr[$tData->year."-".$tData->month]=ceil($tData->total);
}
//dd($tarr);
$monthlyData = $monthlyVal=[];

$currentMonth = date('n');
$currentYear = date('Y');

$monthNames = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
    7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
];

for ($i = 0; $i < 12; $i++) {
    $month = ($currentMonth - $i) % 12; // Using modulo to handle wraparound
    $year = $currentYear - floor(($currentMonth - $i - 1) / 12);

    // Format the month and year as 'M YYYY'
    $monthT=$month;$yearT=$year;
    if($month<=0){
        $monthT=$month+12;
        $yearT=$year-2;
    }
    if($i==0 && $currentMonth==12 && $month==0){
        $monthT=$month+12;
        $yearT=$year;
    }
    $monthNames[$monthT]=empty($monthNames[$monthT])?'Unk':$monthNames[$monthT];
    $formattedDate = $monthNames[$monthT] . ' ' . $yearT;

    // Add the formatted date to the array
    $monthlyData[] = $formattedDate;
    $monthlyVal[] = empty($tarr[$yearT.'-'.$monthT])?0:$tarr[$yearT.'-'.$monthT];
}
$monthlyData=array_reverse($monthlyData);
$monthlyVal=array_reverse($monthlyVal);
$monthlyData="['".implode("','",$monthlyData)."']";
$monthlyVal="[".implode(",",$monthlyVal)."]";

?>
                <script type="text/javascript">
                    var options = {
                      chart: {  type: 'bar'  },//line
                      series: [{
                        name: 'Total Users',
                        data: {!! $monthlyVal !!}
                      }],
                      xaxis: {
                        categories: {!! $monthlyData !!}
                      },
                      toolbar:false
                    }
                    var chart = new ApexCharts(document.querySelector("#chart"), options);
                    chart.render();
                </script>

            </div>
        </div>
        </div>
        <div class="col-6">
        <div class="card shdowbx">
            <div class="card-body">
                <div class="card-title">
                    <h6 class="mb-3 text-15 grow">Revenue</h6>
                </div>
                <div id="chart2"></div>
                <style>#chart2 { max-width: 500px; margin: 35px auto;}</style>


<?php
$tList=App\Models\Transaction::getRevenueMonthList();
$tarr=[];
foreach($tList as $tData){
    $tarr[$tData->year."-".$tData->month]=ceil($tData->total);
}
//dd($tarr);
$monthlyData = $monthlyVal=[];

$currentMonth = date('n');
$currentYear = date('Y');

$monthNames = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
    7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
];

for ($i = 0; $i < 12; $i++) {
    $month = ($currentMonth - $i) % 12; // Using modulo to handle wraparound
    $year = $currentYear - floor(($currentMonth - $i - 1) / 12);

    // Format the month and year as 'M YYYY'
    $monthT=$month;$yearT=$year;
    if($month<=0){
        $monthT=$month+12;
        $yearT=$year-2;
    }
    if($i==0 && $currentMonth==12 && $month==0){
        $monthT=$month+12;
        $yearT=$year;
    }
    $monthNames[$monthT]=empty($monthNames[$monthT])?'Unk':$monthNames[$monthT];
    $formattedDate = $monthNames[$monthT] . ' ' . $yearT;

    // Add the formatted date to the array
    $monthlyData[] = $formattedDate;
    $monthlyVal[] = empty($tarr[$yearT.'-'.$monthT])?0:$tarr[$yearT.'-'.$monthT];
}
$monthlyData=array_reverse($monthlyData);
$monthlyVal=array_reverse($monthlyVal);
$monthlyData="['".implode("','",$monthlyData)."']";
$monthlyVal="[".implode(",",$monthlyVal)."]";

?>
                <script type="text/javascript">
                    var options = {
                      chart: {  type: 'line'  },//line
                      series: [{
                        name: 'Total Amount',
                        data: {!! $monthlyVal !!}
                      }],
                      xaxis: {
                        categories: {!! $monthlyData !!}
                      },
                      toolbar:false
                    }
                    var chart = new ApexCharts(document.querySelector("#chart2"), options);
                    chart.render();
                </script>

            </div>
        </div>
        </div>
    </div>


<script async src="https://www.googletagmanager.com/gtag/js?id=G-SBNWB8F9Y6"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-SBNWB8F9Y6');
</script>
@endsection





@extends('admin.layouts.master')

@section('content')
@include('admin.common.message')

<h2 class="pb-3 border-bottom">Guest Trailer Views</h2>

<form method="GET" action="{{ route('admin.trailerviews.index') }}" class="mb-4">
    <div class="form-row form-select2">
        <label class="form-label">Select Trailer</label>
        {{ Form::select(
            'trailer_id',
            ['' => 'Select any one'] + $trailers->toArray(),
            null,
            ['id' => 'trailer_id', 'class' => 'form-select']
        ) }}
    </div>
    <script>
        jQuery(function($){
            const $select = $('#trailer_id').select2({
                placeholder: 'Choose Trailer...'
                /*allowClear: true*/
            });

            @if(!$selectedTrailerId)
                $select.val('').trigger('change');   // ensure placeholder shows
            @endif
        });

    </script>
</form>

<div class="card p-3">
    <canvas id="trailerChart" height="100"></canvas>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
jQuery(function($){
    const $select = $('#trailer_id');
    if(!$select.length) return;

    if(window.ChartDataLabels) Chart.register(ChartDataLabels);

    let chart = null;

    // Build months without moment.js
    const months = [];
    const now = new Date();
    const start = new Date(now.getFullYear(), now.getMonth() - 11, 1);
    for (let i = 0; i < 12; i++) {
        const d = new Date(start.getFullYear(), start.getMonth() + i, 1);
        months.push(
            d.toLocaleString('en-US', { month: 'short', year: 'numeric' })
        );
    }

    function renderEmptyChart(){
        const ctx = document.getElementById('trailerChart').getContext('2d');
        if(chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Views',
                    data: Array(months.length).fill(0),
                    backgroundColor: '#36a2eb',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false }, datalabels: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    function loadChart(id){
        if(!id){ renderEmptyChart(); return; }
        const url = `{{ route('admin.trailerviews.data') }}?trailer_id=${encodeURIComponent(id)}`;
        fetch(url)
            .then(r => r.json())
            .then(({labels, data})=>{
                if(chart) chart.destroy();
                const ctx = document.getElementById('trailerChart').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Views',
                            data: data,
                            backgroundColor: '#36a2eb',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            datalabels: {
                                color: '#fff',
                                anchor: 'end',
                                align: 'start',
                                font: { weight: 'bold' }
                            }
                        },
                        scales: { y: { beginAtZero:true } }
                    },
                    plugins: [ChartDataLabels]
                });
            });
    }

    $select.on('change', e => loadChart(e.target.value));
    $select.on('select2:select', e => loadChart(e.params.data.id));

    renderEmptyChart();
});
</script>
@endpush

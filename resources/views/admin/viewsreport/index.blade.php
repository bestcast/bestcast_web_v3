@extends('admin.layouts.master')

@section('content')
    @include('admin.common.message')

    <h2 class="pb-3 border-bottom">Views Report</h2>

    <form method="GET" action="{{ route('admin.viewsreport.index') }}" class="mb-4">
        <div class="form-row form-select2" id="movies_dropdown">
            <h3>Movie List</h3>
            {{ Form::select(
                'movies_id',
                ['' => 'Select any one'] + $movies->toArray(),
                null,
                ['id' => 'movies_id', 'class' => 'form-select']
            ) }}
        </div>

        <script>
            jQuery(function($) {
                const $select = $('#movies_id');
                $select.select2({
                    placeholder: "Choose Movie..."
                    /*minimumInputLength: 0,
                    ajax: {
                        url: function (params) {
                            return "{{ route('admin.movies.searchbytitle') }}/" + params.term;
                        },
                        dataType: 'json',
                        processResults: function (data) {
                            return { results: data };
                        },
                        cache: true
                    }*/
                });

                // Clear dropdown on page load
                $select.val('').trigger('change');
            });
        </script>
    </form>

    <div class="card p-3">
        <canvas id="viewsChart" height="100"></canvas>
    </div>

    @if(!is_null($totalViews))
        <div class="mt-4">
            <h4>Total Views: <strong>{{ $totalViews }}</strong></h4>
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<script>
jQuery(function($){
    const $select = $('#movies_id');
    if(!$select.length) return;

    if(window.ChartDataLabels){
        Chart.register(ChartDataLabels);
    }

    let chart = null;

    // Last 12 months (for empty chart)
    const months = [];
    const now = new Date();
    const startDate = new Date(now.getFullYear(), now.getMonth() - 11, 1); // 12 months ago

    for (let i = 0; i < 12; i++) {
        const d = new Date(startDate.getFullYear(), startDate.getMonth() + i, 1);
        months.push(
            d.toLocaleString('en-US', { month: 'short', year: 'numeric' })
        );
    }

    function renderEmptyChart(){
        const ctx = document.getElementById('viewsChart').getContext('2d');
        if(chart) chart.destroy();
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Trailer',
                        data: Array(months.length).fill(0),
                        backgroundColor: '#36a2eb',
                        borderRadius: 6
                    },
                    {
                        label: 'Movie',
                        data: Array(months.length).fill(0),
                        backgroundColor: '#4e73df',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    datalabels: { display:false }
                },
                scales: {
                    x: {
                        ticks: { autoSkip:false, maxRotation:45, minRotation:45 }
                    },
                    y: { beginAtZero:true }
                }
            }
        });
    }

    function loadChart(movieId){
        if(!movieId){
            renderEmptyChart();
            return;
        }

        const url = `{{ route('admin.viewsreport.data') }}?movie_id=${encodeURIComponent(movieId)}`;
        fetch(url)
            .then(r => r.json())
            .then(({labels = [], trailerData = [], movieData = []}) => {
                if(chart) chart.destroy();
                const ctx = document.getElementById('viewsChart').getContext('2d');

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Trailer',
                                data: trailerData,
                                backgroundColor: '#36a2eb',
                                borderRadius: 6
                            },
                            {
                                label: 'Movie',
                                data: movieData,
                                backgroundColor: '#4e73df',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            datalabels: {
                                color: '#fff',
                                anchor: 'end',
                                align: 'start',
                                font: { weight: 'bold' },
                                formatter: value => value
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    autoSkip:false,
                                    maxRotation:45,
                                    minRotation:45
                                }
                            },
                            y: { beginAtZero:true }
                        }
                        // To stack bars instead of side-by-side, uncomment:
                        // scales: {
                        //     x: { stacked: true },
                        //     y: { stacked: true, beginAtZero:true }
                        // }
                    },
                    plugins: [ChartDataLabels]
                });
            })
            .catch(err => console.error('Chart fetch error', err));
    }

    // Events
    $select.on('change', e => loadChart(e.target.value));
    $select.on('select2:select', e => loadChart(e.params.data.id));

    // Initial render
    @if($selectedMovieId)
        loadChart("{{ $selectedMovieId }}");
    @else
        renderEmptyChart();
    @endif
});
</script>
@endpush

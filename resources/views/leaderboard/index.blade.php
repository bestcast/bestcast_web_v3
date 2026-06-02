@extends('layouts.myaccount')

@section('header-script')
<style>
    body {
        font-family: Arial;
        background: #000;
        color: #fff;
    }

    .leaderboard-title {
        text-align: center;
        color: #ff0000;
        margin: 30px 0;
        font-size: 26px;
        font-weight: bold;
    }

    table {
        width: 80%;
        margin: 0 auto 60px;
        border-collapse: collapse;
        background: #000;
        box-shadow: 0 0 20px rgba(0,0,0,0.6);
    }

    th, td {
        padding: 12px;
        text-align: center;
        border: 1px solid #991b1b;
    }

    th {
        background: #ff0000;
        color: #fff;
        text-transform: uppercase;
    }

    tr:nth-child(even) {
        background: #111;
    }

    tr:nth-child(odd) {
        background: #1f1f1f;
    }

    tr:hover {
        background: #7f1d1d;
        transition: 0.3s;
    }

    .gold {
        color: #facc15;
        font-weight: bold;
    }

    .silver {
        color: #e5e7eb;
        font-weight: bold;
    }

    .bronze {
        color: #fb923c;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-fluid blkCtr">
    <div class="row">
        <div class="col-lg-12">
            <div style="width:80%; margin:0 auto 40px;">
                <ul style="list-style:none; padding:0; display:flex; flex-wrap:wrap; gap:15px;">
                    <li>
                        <a href="{{ route('leaderboard.index') }}"
                           style="color: {{ empty($movieId) ? '#facc15' : '#fff' }}; font-weight:bold;">
                            <img src="{{ asset('img/icon/quiz_application/action.png') }}" width="24" alt="action"> All Movies
                        </a>
                    </li>

                    @foreach($movies as $movie)
                        <li>
                            <a href="{{ route('leaderboard.index', ['movie_id' => $movie->id]) }}"
                               style="color: {{ $movieId == $movie->id ? '#facc15' : '#fff' }};">
                                {{ $movie->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="leaderboard-title">
                <img src="{{ asset('img/icon/quiz_application/trophy.png') }}"
                     width="24"
                     alt="winner">

                @if($selectedMovie)
                    {{ $selectedMovie->title }} Movie Leaderboard
                @else
                    Overall Leaderboard
                @endif
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User</th>

                        @if(empty($movieId))
                            <th>Movie</th>
                        @endif

                        <th>Score</th>
                        <th>Time (sec)</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($leaderboard as $row)
                        <tr>
                            <td class="{{
                                $loop->iteration == 1 ? 'gold' :
                                ($loop->iteration == 2 ? 'silver' :
                                ($loop->iteration == 3 ? 'bronze' : ''))
                            }}">
                                {{ $loop->iteration }}
                            </td>

                            <td>{{ $row->user->name }}</td>

                            @if(empty($movieId))
                                <td>{{ $row->movie->title ?? '-' }}</td>
                            @endif

                            <td>{{ $row->score }}/18
                                @if($row->score == 18)
                                    <span style="color:#facc15;">🏆 Winner</span>
                                @endif
                            </td>
                            <td>{{ $row->total_answered_seconds }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ empty($movieId) ? 5 : 4 }}"
                                style="color:#facc15;">
                                No quiz attempts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>
</div>
@endsection

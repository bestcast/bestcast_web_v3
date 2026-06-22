@extends('layouts.myaccount')

@section('header-script')
<head>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<style>
    html, body {
        min-height: 100%;
        margin: 0;
        padding: 0;
    }
    body::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: -1;
    }

    body {
        font-family: Arial, sans-serif;
        background: url('{{ asset("img/icon/quiz_application/leaderboard_bg.webp") }}') no-repeat center center fixed;
        background-size: cover;
        background-attachment: fixed;   /* add this */
        min-height: 100vh;              /* add this */
        color: #fff;
    }

    /* Dark overlay */
    .blkCtr {
        background: rgba(0,0,0,0.75);
        /*background: transparent;*/
        min-height: 100vh;
        padding-top: 20px;
        padding-bottom: 80px;
    }
    .leaderboard-title{
        text-align:center;
        margin:20px 0 30px;
    }
    

    .movie-name {
        font-family: 'Lexend', sans-serif;
        font-size: 48px;
        font-weight: 700;
        color: #FFFFFF;
        letter-spacing: 2px;
        /*text-transform: uppercase;*/
        text-shadow:
            0 0 10px #ffd700,
            0 0 20px #ff9900,
            0 0 30px #ff0000;
    }
    .leaderboard-text{
        color:#ffd700;
        font-family: 'Cinzel', serif;
        font-size:40px;
        font-weight:bold;
        text-shadow:
            0 0 10px #ffd700,
            0 0 20px #ff9900,
            0 0 30px #ff0000;
    }
    table {
        width: 85%;
        margin: 0 auto 60px;
        border-collapse: separate;
        border-spacing: 0;
        background: transparent;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: none;
    }
    th, td {
        padding: 16px;
        text-align: center;
        border: none;
    }
    th {
        background: transparent;
        color: #ffd700;
        padding: 18px;
        text-transform: uppercase;
        font-size: 18px;
        font-weight: bold;
        text-shadow: 0 0 8px rgba(255,215,0,0.8);
    }

    td {
        background: rgba(0,0,0,0.35);
        color: #fff;
    }

    tr:nth-child(even),
    tr:nth-child(odd) {
        background: transparent;
    }

    tr:hover {
        background: rgba(255,215,0,0.12);
        transition: all 0.3s ease;
    }
    tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    tbody tr:last-child {
        border-bottom: none;
    }
    .gold {
        color: #ffd700;
        font-weight: bold;
        font-size: 18px;
        text-shadow: 0 0 10px #ffd700;
    }

    .silver {
        color: #e5e7eb;
        font-weight: bold;
        font-size: 18px;
    }

    .bronze {
        color: #fb923c;
        font-weight: bold;
        font-size: 18px;
    }
    .leaderboard-note {
        width: 85%;
        margin: 0 auto 40px;
        text-align: center;
        font-family: 'Lexend', sans-serif;
        font-size: 15px;
        color: #fff;
        padding: 20px 0;
    }
</style>
@endsection

@section('content')
<div class="container-fluid blkCtr">
    <div class="row">
        <div class="col-lg-12">
            <div style="width:80%; margin:0 auto 30px;">
                <form method="GET" action="{{ route('leaderboard.index') }}">
                    <div style="display:inline-flex; align-items:center; gap:0px;">
                        <img src="{{ asset('img/icon/quiz_application/action.png') }}" width="24" alt="action" style="margin-right:2px;">
                        <select name="movie_id" onchange="this.form.submit()" style=" background:#000000;color:#facc15;border:1px solid #000000;border-radius:5px;padding:10px 15px;min-width:250px;font-weight:bold;">
                            <option value="">All Movies</option>
                            @foreach($movies as $movie)
                                <option value="{{ $movie->id }}"
                                    {{ $movieId == $movie->id ? 'selected' : '' }}>
                                    {{ $movie->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="leaderboard-title">
                <div class="movie-name">
                    @if($selectedMovie)
                        {{ $selectedMovie->title }}
                    @else
                        All Movies
                    @endif
                </div>
                <div class="leaderboard-text">
                    <img src="{{ asset('img/icon/quiz_application/trophy.png') }}"
                         width="30"
                         alt="winner">

                    Leaderboard

                    <img src="{{ asset('img/icon/quiz_application/trophy.png') }}"
                         width="30"
                         alt="winner">
                </div>
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
                                    <span style="color:#facc15;"><img src="{{ asset('img/icon/quiz_application/trophy.png') }}"
                             width="24"
                             alt="winner"> Winner</span>
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
<div class="leaderboard-note">
    <img src="{{ asset('img/icon/quiz_application/trophy.png') }}" width="24" alt="winner">
    <span style="margin-left:5px;">
        Note: A score of <strong>18/18</strong> qualifies a user as a <strong>Winner</strong> in both the All Movies leaderboard and individual movie leaderboards.
    </span>
</div>
@endsection
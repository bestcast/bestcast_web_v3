<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <style>
        body {
            font-family: Arial;
            background: #000;
            color: #fff;
        }

        table {
            width: 80%;
            margin: 40px auto;
            border-collapse: collapse;
            background: #000; /* black table base */
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #991b1b; /* red border */
        }

        th {
            background: #ff0000; /* strong red header */
            color: #fff;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background: #111; /* black row */
        }

        tr:nth-child(odd) {
            background: #1f1f1f; /* dark gray/black row */
        }

        tr:hover {
            background: #7f1d1d; /* red hover */
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

</head>
<body>

<h2 style="text-align:center">🏆 Leaderboard</h2>

<table>
    <tr>
        <th>Rank</th>
        <th>User</th>
        <th>Score</th>
        <th>Time (sec)</th>
    </tr>

    @foreach($leaderboard as $row)
        <tr>
            <td class="{{ $loop->iteration == 1 ? 'gold' : ($loop->iteration == 2 ? 'silver' : ($loop->iteration == 3 ? 'bronze' : '')) }}">
                {{ $loop->iteration }}
            </td>
            <td>{{ $row->user->name }}</td>
            <td>{{ $row->score }}/9</td>
            <td>{{ $row->total_answered_seconds }}</td>
        </tr>
    @endforeach

</table>

</body>
</html>

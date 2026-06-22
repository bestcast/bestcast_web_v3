<?php

    namespace App\Exports;

    use Illuminate\Support\Facades\DB;
    use Maatwebsite\Excel\Concerns\{
        FromCollection,
        WithStyles,
        ShouldAutoSize
    };
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use PhpOffice\PhpSpreadsheet\Style\Font;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;

    class MovieReportExport implements 
        FromCollection, 
        WithStyles, 
        ShouldAutoSize
    {
        protected $movieId, $fromDate, $toDate;
        protected $counter = 0;

        public function __construct($movieId, $fromDate, $toDate)
        {
            $this->movieId = $movieId;
            $this->fromDate = $fromDate;
            $this->toDate = $toDate;
        }

        public function collection()
        {
            $query = DB::table('users_movies as um')
                ->join('movies as m', 'm.id', '=', 'um.movie_id')
                ->where('um.movie_id', $this->movieId)
                ->whereRaw('CAST(um.watch_time AS UNSIGNED) >= 60')

                ->selectRaw("
                    um.user_id,
                    SEC_TO_TIME(um.watch_time) as total_streaming_time,
                    CASE
                        WHEN CAST(um.watch_time AS UNSIGNED) >= CAST(m.duration AS UNSIGNED)
                        THEN 100
                        ELSE ROUND(
                            (
                                CAST(um.watch_time AS UNSIGNED)
                                / CAST(m.duration AS UNSIGNED)
                            ) * 100,
                            2
                        )
                    END as watch_percentage,
                    CASE
                        -- Movie >= 1 hour
                        WHEN CAST(m.duration AS UNSIGNED) >= 3600
                             AND CAST(um.watch_time AS UNSIGNED) >= 3600
                        THEN 1

                        -- Movie < 1 hour (90% rule)
                        WHEN CAST(m.duration AS UNSIGNED) < 3600
                             AND (
                                (CAST(um.watch_time AS UNSIGNED) / CAST(m.duration AS UNSIGNED)) * 100
                             ) >= 90
                        THEN 1

                        ELSE 0
                    END as is_view,
                    DATE(um.created_at) as from_date,
                    DATE(um.updated_at) as to_date
                ");

            if ($this->fromDate) {
                $query->whereDate('um.created_at', '>=', $this->fromDate);
            }

            if ($this->toDate) {
                $query->whereDate('um.updated_at', '<=', $this->toDate);
            }
            $data = $query->orderByDesc('um.updated_at')->get();
            $movieTitle = DB::table('movies')
                            ->where('id', $this->movieId)
                            ->value('title');
            // TOTAL CALCULATION
            $totalStats = DB::table('users_movies as um')
                ->join('movies as m', 'm.id', '=', 'um.movie_id')
                ->where('um.movie_id', $this->movieId)
                ->selectRaw("
                    SUM(
                        CASE
                            WHEN CAST(m.duration AS UNSIGNED) >= 3600
                                 AND CAST(um.watch_time AS UNSIGNED) >= 3600
                            THEN 3600
                            WHEN CAST(m.duration AS UNSIGNED) < 3600
                                 AND CAST(um.watch_time AS UNSIGNED) >= CAST(m.duration AS UNSIGNED)
                            THEN CAST(m.duration AS UNSIGNED)
                            ELSE 0
                        END
                    ) as total_seconds,

                    SUM(
                        CASE
                            WHEN CAST(m.duration AS UNSIGNED) >= 3600
                                 AND CAST(um.watch_time AS UNSIGNED) >= 3600
                            THEN 1
                            WHEN CAST(m.duration AS UNSIGNED) < 3600
                                 AND (
                                    (CAST(um.watch_time AS UNSIGNED) / CAST(m.duration AS UNSIGNED)) * 100
                                 ) >= 90
                            THEN 1
                            ELSE 0
                        END
                    ) as views_count
                ")
                ->first();

            $totalMinutes = floor(($totalStats->total_seconds ?? 0) / 60);
            $viewsCount   = $totalStats->views_count ?? 0;
            // Convert collection to array
            $dataArray = $data->toArray();
            $date = now()->format('d-m-Y');
            // Add rows at TOP
            array_unshift($dataArray,
                (object)[
                    'user_id' => 'Movie',
                    'total_streaming_time' => $movieTitle,
                    'watch_percentage' => '',
                    'is_view' => '',
                    'from_date' => '',
                    'to_date' => ''
                ],
                (object)[
                    'user_id' => 'Total Watch Time',
                    'total_streaming_time' => $totalMinutes . ' Minutes',
                    'watch_percentage' => '',
                    'is_view' => '',
                    'from_date' => '',
                    'to_date' => ''
                ],
                (object)[
                    'user_id' => 'Total Views',
                    'total_streaming_time' => $viewsCount,
                    'watch_percentage' => '',
                    'is_view' => '',
                    'from_date' => '',
                    'to_date' => ''
                ],
                (object)[ // empty spacer row
                    'user_id' => '',
                    'total_streaming_time' => '',
                    'watch_percentage' => '',
                    'is_view' => '',
                    'from_date' => '',
                    'to_date' => ''
                ]
            );
            $final = [];

            // Row 1: Today Date
            $final[] = ['', 'Date', $date, '', '', '', ''];

            // Row 2: Movie
            $final[] = ['', 'Movie', $movieTitle, '', '', '', ''];

            // Row 3: Total Watch Time
            $final[] = ['', 'Total Watch Time', $totalMinutes . ' Minutes', '', '', '', ''];

            // Row 4: Total Views
            $final[] = ['', 'Total Views', $viewsCount, '', '', '', ''];

            // Row 5: Empty
            $final[] = ['', '', '', '', '', '', ''];

            // Row 5: HEADINGS (IMPORTANT FIX)
            $final[] = [
                'S.No',
                'User ID',
                'Total Streaming Time',
                'Watch %',
                'From Date',
                'To Date',
                'Status'
            ];

            // Data rows
            foreach ($data as $row) {
                $final[] = [
                    ++$this->counter,
                    $row->user_id,
                    substr($row->total_streaming_time, 0, 8),
                    $row->watch_percentage,
                    $row->from_date,
                    $row->to_date,
                    $row->is_view
                ];
            }

            return collect($final);
            //return collect($dataArray);
        }

        // Add S.No + clean time format
        

        public function headings(): array
        {
            return [
                'S.No',
                'User ID',
                'Total Streaming Time',
                'Watch %',
                'From Date',
                'To Date',
                'Status',
            ];
        }

        // Styling
        public function styles(Worksheet $sheet)
        {
            // Get last row & column dynamically
            $lastRow = $sheet->getHighestRow();
            $lastColumn = $sheet->getHighestColumn(); // e.g., F

            $range = "A1:{$lastColumn}{$lastRow}";

            // Apply center alignment to ALL cells
            $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Make header bold + center (extra clarity)
            $sheet->getStyle('A1:G3')->getFont()->setBold(true);
            $sheet->getStyle('A1:G6')->getFont()->setBold(true);

            return [];
        }
    }
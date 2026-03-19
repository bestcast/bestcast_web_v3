<?php

    namespace App\Exports;

    use Illuminate\Support\Facades\DB;
    use Maatwebsite\Excel\Concerns\{
        FromCollection,
        WithHeadings,
        WithStyles,
        ShouldAutoSize,
        WithMapping
    };
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
    use PhpOffice\PhpSpreadsheet\Style\Font;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;

    class MovieReportExport implements 
        FromCollection, 
        WithHeadings, 
        WithStyles, 
        ShouldAutoSize,
        WithMapping
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
                    DATE(um.created_at) as from_date,
                    DATE(um.updated_at) as to_date
                ");

            if ($this->fromDate) {
                $query->whereDate('um.created_at', '>=', $this->fromDate);
            }

            if ($this->toDate) {
                $query->whereDate('um.updated_at', '<=', $this->toDate);
            }

            return $query->orderByDesc('um.updated_at')->get();
        }

        // Add S.No + clean time format
        public function map($row): array
        {
            return [
                ++$this->counter,
                $row->user_id,
                substr($row->total_streaming_time, 0, 8), // REMOVE .000000
                $row->watch_percentage,
                $row->from_date,
                $row->to_date,
            ];
        }

        public function headings(): array
        {
            return [
                'S.No',
                'User ID',
                'Total Streaming Time',
                'Watch %',
                'From Date',
                'To Date',
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
            $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);

            return [];
        }
    }

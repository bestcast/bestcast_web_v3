<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\CoreConfig;
use Illuminate\Http\Request;
use Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $newReleaseDays = CoreConfig::value('new_release_days_limit') ?? 14;

        // stored internally as 'YYYY-MM' — split back out for the two dropdowns
        $posterDateFrom = CoreConfig::value('poster_date_from') ?? '';
        $posterDateTo   = CoreConfig::value('poster_date_to') ?? '';

        [$posterYearFrom, $posterMonthFrom] = $this->splitYearMonth($posterDateFrom);
        [$posterYearTo, $posterMonthTo]     = $this->splitYearMonth($posterDateTo);

        return view('admin.settings.index', compact(
            'newReleaseDays',
            'posterYearFrom', 'posterMonthFrom',
            'posterYearTo', 'posterMonthTo'
        ));
    }

    public function save(Request $request)
    {
        $request->validate([
            'new_release_days_limit' => 'required|integer|in:10,15,25,30',
            'poster_month_from' => 'nullable|integer|between:1,12',
            'poster_year_from'  => 'nullable|integer|digits:4',
            'poster_month_to'   => 'nullable|integer|between:1,12',
            'poster_year_to'    => 'nullable|integer|digits:4',
        ]);

        $config = CoreConfig::firstOrNew(['path' => 'new_release_days_limit']);
        $config->value = $request->new_release_days_limit;
        $config->user_id = auth()->id();
        $config->updated_by = auth()->id();
        if (!$config->exists) {
            $config->created_by = auth()->id();
        }
        $config->save();

        // combine dropdowns into 'YYYY-MM' for storage
        $dateFrom = (!empty($request->poster_year_from) && !empty($request->poster_month_from))
            ? sprintf('%04d-%02d', $request->poster_year_from, $request->poster_month_from)
            : '';

        $dateTo = (!empty($request->poster_year_to) && !empty($request->poster_month_to))
            ? sprintf('%04d-%02d', $request->poster_year_to, $request->poster_month_to)
            : '';

        $this->saveConfig('poster_date_from', $dateFrom);
        $this->saveConfig('poster_date_to', $dateTo);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully');
    }

    private function splitYearMonth($value)
    {
        if (empty($value) || strpos($value, '-') === false) {
            return ['', ''];
        }
        [$year, $month] = explode('-', $value);
        return [$year, (int) $month]; // cast month to int so "05" matches option value 5 in the select
    }

    private function saveConfig($path, $value)
    {
        $config = CoreConfig::firstOrNew(['path' => $path]);
        $config->value = $value;
        $config->user_id = auth()->id();
        $config->updated_by = auth()->id();
        if (!$config->exists) {
            $config->created_by = auth()->id();
        }
        $config->save();
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Webseries;
use App\Models\Season;
use App\Models\Episode;

class SeasonController extends Controller
{
    /*public function index($seasonId)
    {
        $season = Season::with('episodes')->findOrFail($seasonId);

        return view('admin.episodes.index', [
            'season' => $season,
            'model'  => $season->episodes
        ]);
    }*/
    public function index($webseriesId)
    {
        $webseries = Webseries::with('seasons')->findOrFail($webseriesId);

        return view('admin.seasons.index', compact('webseries'));
    }

    public function autoCreate($webseriesId)
    {
        $webseries = Webseries::findOrFail($webseriesId);

        // get last season number
        $lastSeason = Season::where('webseries_id', $webseriesId)
                            ->orderBy('season_number', 'desc')
                            ->first();

        $nextSeasonNumber = $lastSeason
            ? $lastSeason->season_number + 1
            : 1;

        Season::create([
            'webseries_id' => $webseriesId,
            'season_number' => $nextSeasonNumber,
            'title' => 'Season ' . $nextSeasonNumber,
            'status' => 1
        ]);

        return redirect()
            ->back()
            ->with('success', 'Season ' . $nextSeasonNumber . ' created successfully');
    }
    public function delete($webseries_id, $season_id)
    {
        $season = Season::find($season_id);

        if (!$season || $season->webseries_id != $webseries_id) {
            return redirect()
                ->route('admin.seasons.index', $webseries_id)
                ->with('error', 'Season not found!');
        }

        // Delete all episodes under this season first
        Episode::where('season_id', $season_id)->delete();

        // Delete the season
        $season->delete();

        return redirect()
            ->route('admin.seasons.index', $webseries_id)
            ->with('success', 'Season and its episodes deleted successfully!');
    }
}
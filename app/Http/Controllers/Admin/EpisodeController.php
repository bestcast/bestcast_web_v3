<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Webseries;
use App\Models\Season;
use App\Models\Episode;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{
    public function index($seasonId)
    {
        $season = Season::with('episodes')->findOrFail($seasonId);

        return view('admin.episodes.index', [
            'season' => $season,
            'model'  => $season->episodes
        ]);
    }
    public function autoCreate($seasonId)
    {
        $season = Season::findOrFail($seasonId);

        $last = Episode::where('season_id',$seasonId)
                       ->orderBy('episode_number','desc')
                       ->first();

        $next = $last ? $last->episode_number + 1 : 1;

        Episode::create([
            'season_id' => $seasonId,
            'episode_number' => $next,
            'urlkey' => Str::slug($season->webseries->title.'-ep-'.$next).'-'.uniqid(),
            'title' => 'Episode '.$next,
            'status' => 1
        ]);

        return back()->with('success','Episode created');
    }
    public function edit($webseriesId, $seasonId, $episodeId)
    {
        $webseries = Webseries::findOrFail($webseriesId);
        $season = Season::where('webseries_id', $webseriesId)
                        ->findOrFail($seasonId);

        $model = Episode::where('season_id', $seasonId)
                        ->findOrFail($episodeId);

        return view('admin.episodes.edit', compact(
            'model',
            'webseries',
            'season'
        ));
    }
    public function editsave(Request $request, $webseriesId, $seasonId, $episodeId)
    {
        $model = Episode::where('season_id', $seasonId)
                        ->findOrFail($episodeId);

        $data = $request->all();

        $data['status'] = empty($data['status']) ? 0 : 1;

        $model->fill($data);
        $model->save();

        return redirect()
            ->route('admin.episodes.edit', [
                $webseriesId,
                $seasonId,
                $episodeId
            ])
            ->with('success', 'Episode Updated Successfully');
    }


}

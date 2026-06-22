<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movies;
use App\Models\Webseries;
use App\Models\Season;
use App\Models\Episode;
use App\Models\EpisodeGenres;
use App\Models\EpisodeLanguages;
use App\Models\EpisodeUsers;
use App\Models\EpisodeRelated;
use App\Models\EpisodeSubtitle;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{
    public function searchbytitle(Request $request)
    {
        $key = $request->key;

        $episodes = Episode::where('title', 'LIKE', "%{$key}%")
            ->select('id', 'title as text')
            ->limit(20)
            ->get();

        return response()->json($episodes);
    }


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
                        ->with(['genres', 'languages', 'users'])
                        ->findOrFail($episodeId);

        $autoFillData = null;

        // Check if current episode has no relations
        $isNewEpisode = $model->genres->isEmpty()
                     && $model->languages->isEmpty()
                     && $model->users->isEmpty();

        if ($isNewEpisode) {
            // Get first episode of season with relations loaded
            $referenceEpisode = Episode::where('season_id', $seasonId)
                ->where('id', '!=', $episodeId)
                ->with(['genres.genres', 'languages.languages', 'users.users'])
                ->orderBy('episode_number', 'asc')
                ->first();

            if ($referenceEpisode) {
                // Build genres array
                $genres = [];
                foreach ($referenceEpisode->genres as $g) {
                    if (!empty($g->genres)) {
                        $genres[] = [
                            'id'   => $g->genre_id,
                            'text' => $g->genres->title,
                        ];
                    }
                }

                // Build languages array
                $languages = [];
                foreach ($referenceEpisode->languages as $l) {
                    if (!empty($l->languages)) {
                        $languages[] = [
                            'id'   => $l->language_id,
                            'text' => $l->languages->title,
                        ];
                    }
                }

                // Build users array
                $users = [];
                foreach ($referenceEpisode->users as $u) {
                    if (!empty($u->users)) {
                        $users[] = [
                            'id'    => $u->user_id,
                            'text'  => $u->users->name,
                            'group' => $u->group,
                        ];
                    }
                }

                if (!empty($genres) || !empty($languages) || !empty($users)) {
                    $autoFillData = [
                        'genres'    => $genres,
                        'languages' => $languages,
                        'users'     => $users,
                    ];
                }
            }
        }

        /*dd([
            'referenceEpisode_id' => $referenceEpisode?->id,
            'genres'    => $referenceEpisode?->genres?->toArray(),
            'languages' => $referenceEpisode?->languages?->toArray(),
            'users'     => $referenceEpisode?->users?->toArray(),
            'autoFillData' => $autoFillData,
        ]);*/
        return view('admin.episodes.edit', compact(
            'model',
            'webseries',
            'season',
            'autoFillData'
        ));
    }
    public function editsave(Request $request, $webseriesId, $seasonId, $episodeId)
    {
        if($episodeId)
        {
            $model = Episode::where('season_id', $seasonId)
                ->findOrFail($episodeId);

            $rules = Episode::$rules;
            $rules['urlkey'] = $rules['urlkey'] . ',' . $episodeId . ",id";

            // Slug conversion (same like Movies)
            $request['urlkey'] = \Str::slug($request['urlkey']);

            $requestData = $request->all();

            $validatedData = $request->validate(
                $rules,
                Episode::$messages
            );
            //dd($requestData);exit;
            // Boolean flags
            $requestData['status']          = empty($requestData['status']) ? 0 : 1;
            $requestData['movie_access']    = empty($requestData['movie_access']) ? 0 : 1;
            $requestData['subtitle_status'] = empty($requestData['subtitle_status']) ? 0 : 1;
            $requestData['is_upcoming']     = empty($requestData['is_upcoming']) ? 0 : 1;
            $requestData['topten']          = empty($requestData['topten']) ? 0 : 1;

            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();

            $EpisodeGenres = EpisodeGenres::where('episode_id',$episodeId)->delete();
            if(!empty($requestData['genre_id']) && count($requestData['genre_id'])){
                foreach($requestData['genre_id'] as $key=>$itm_id){
                    if($itm_id){
                        $EpisodeGenres = new EpisodeGenres();
                        $EpisodeGenres->episode_id = $episodeId;
                        $EpisodeGenres->genre_id = $itm_id;
                        $EpisodeGenres->save();
                    }
                }
            }
            $EpisodeLanguages = EpisodeLanguages::where('episode_id',$episodeId)->delete();
            if(!empty($requestData['language_id']) && count($requestData['language_id'])){
                foreach($requestData['language_id'] as $key=>$itm_id){
                    if($itm_id){
                        $EpisodeLanguages = new EpisodeLanguages();
                        $EpisodeLanguages->episode_id = $episodeId;
                        $EpisodeLanguages->language_id = $itm_id;
                        $EpisodeLanguages->save();
                    }
                }
            }

            $EpisodeUsers = EpisodeUsers::where('episode_id',$episodeId)->delete();
            $usertype=User::groupSlug();
            foreach($usertype as $ukey=>$uname):
                if(!empty($requestData[$uname]) && count($requestData[$uname])){
                    foreach($requestData[$uname] as $key=>$itm_id){
                        if($itm_id){
                            $EpisodeUsers = new EpisodeUsers();
                            $EpisodeUsers->episode_id = $episodeId;
                            $EpisodeUsers->user_id = $itm_id;
                            $EpisodeUsers->group = $ukey;
                            $EpisodeUsers->save();
                        }
                    }
                }
            endforeach;

            $EpisodeRelated = EpisodeRelated::where('episode_id',$episodeId)->delete();
            if(!empty($requestData['related']) && count($requestData['related'])){
                foreach($requestData['related'] as $key=>$itm_id){
                    if($itm_id){
                        $EpisodeRelated = new EpisodeRelated();
                        $EpisodeRelated->episode_id = $id;
                        $EpisodeRelated->related_id = $itm_id;
                        $EpisodeRelated->save();
                    }
                }
            }

            $EpisodeSubtitle = EpisodeSubtitle::where('episode_id',$episodeId)->delete();
            if(!empty($requestData['subtitle']) && count($requestData['subtitle'])){
                $i=0;foreach($requestData['subtitle'] as $key=>$itm_id){$i++;
                    if($itm_id){
                        if(!empty($requestData['subtitle_label'][$key]) && !empty($requestData['subtitle_url'][$key])){
                            $EpisodeSubtitle = new EpisodeSubtitle();
                            $EpisodeSubtitle->episode_id = $episodeId;
                            $EpisodeSubtitle->is_active = empty($requestData['subtitle_is_active'][$key])?0:1;
                            $EpisodeSubtitle->label = $requestData['subtitle_label'][$key];
                            $EpisodeSubtitle->url = $requestData['subtitle_url'][$key];
                            $EpisodeSubtitle->save();
                        }
                    }
                }
            }
            return redirect()
                ->route('admin.episodes.edit', [
                    $webseriesId,
                    $seasonId,
                    $episodeId
                ])
                ->with('success', 'Episode Updated Successfully');
        }
    }
    public function delete($season_id, $episode_id)
    {
        $episode = Episode::find($episode_id);

        if (!$episode || $episode->season_id != $season_id) {
            return redirect()
                ->route('admin.episodes.index', $season_id)
                ->with('error', 'Episode not found!');
        }

        $episode->delete();

        return redirect()
            ->route('admin.episodes.index', $season_id)
            ->with('success', 'Episode deleted successfully!');
    }


}
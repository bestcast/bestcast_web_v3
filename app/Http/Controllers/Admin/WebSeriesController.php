<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Genres;
use App\Models\Webseries;
use App\Models\Episode;
use App\Models\EpisodeGenres;
use App\Models\EpisodeLanguages;
use App\Models\EpisodeRelated;
use App\Models\EpisodeUsers;
use App\Models\EpisodeSubtitle;
use Auth;
use Field;
use Lib;
use App\Models\Meta;
//use App\Models\Movies;

class WebSeriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function list(Request $request)
    {
        $data = Webseries::getList();
        return $data;
    }
    public function searchbytitle($key='ZXP')
    {
        //if(empty($key)){ return null;}
        $data = Webseries::select("id","title as text")->where('title','like',"%".urldecode($key)."%")->latest()->take(20)->get();
        if(!count($data)){ return Webseries::select("id","title as text")->latest()->take(20)->get();}
        return $data;
    }
    public function index()
    {
        $data = Webseries::getList();
        return view('admin.webseries.index', ['model'=>$data]);
    }
    public function create()
    {
        $model = new Webseries();
        return view('admin.webseries.create', compact('model'));
    }
    public function createsave(Request $request)
    {
        $requestData = $request->all();
        $requestData['status']=empty($requestData['status'])?0:1;
        $requestData['movie_access']=empty($requestData['movie_access'])?0:1;
        $model = new Webseries();
        //$requestData['movie_id'] = $movie->id;
        $model->fill($requestData);
        //$model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.webseries.edit', $model->id)->with('success', 'Created Successfully');
    }
    public function edit($id)
    {
        $data = Webseries::with('seasons')->find($id);

        if(empty($data)){
            return redirect()
                ->route('admin.webseries.index')
                ->with('error', 'Record does not exist! ID:'.$id);
        }

        $meta = Meta::page($id,true);

        return view('admin.webseries.edit', [
            'model'=>$data,
            'meta'=>$meta,
            'seasons'=>$data->seasons  // add this
        ]);
    }
    public function editsave(Request $request, $id)
    {
        $model = Webseries::findOrFail($id);
        $requestData = $request->all();

        // Webseries level fields
        $requestData['status']       = empty($requestData['status']) ? 0 : 1;
        $requestData['movie_access'] = empty($requestData['movie_access']) ? 0 : 1;
        $requestData['subtitle_status']=empty($requestData['subtitle_status'])?0:1;
        $requestData['is_upcoming']=empty($requestData['is_upcoming'])?0:1;
        $requestData['topten']=empty($requestData['topten'])?0:1;
        $model->fill($requestData);
        $model->updated_by = Auth::user()->id;
        $model->save();

        // Genres
        EpisodeGenres::where('episode_id', $episode->id)->delete();
        if (!empty($requestData['genre_id']) && count($requestData['genre_id'])) {
            foreach ($requestData['genre_id'] as $genre_id) {
                if ($genre_id) {
                    $eg = new EpisodeGenres();
                    $eg->episode_id = $episode->id;
                    $eg->genre_id   = $genre_id;
                    $eg->save();
                }
            }
        }

        // Languages
        EpisodeLanguages::where('episode_id', $episode->id)->delete();
        if (!empty($requestData['language_id']) && count($requestData['language_id'])) {
            foreach ($requestData['language_id'] as $language_id) {
                if ($language_id) {
                    $el = new EpisodeLanguages();
                    $el->episode_id  = $episode->id;
                    $el->language_id = $language_id;
                    $el->save();
                }
            }
        }

        // Casts
        EpisodeUsers::where('episode_id', $episode->id)->delete();
        $usertype = User::groupSlug();
        foreach ($usertype as $ukey => $uname) {
            if (!empty($requestData[$uname]) && count($requestData[$uname])) {
                foreach ($requestData[$uname] as $user_id) {
                    if ($user_id) {
                        $eu = new EpisodeUsers();
                        $eu->episode_id = $episode->id;
                        $eu->user_id    = $user_id;
                        $eu->group      = $ukey;
                        $eu->save();
                    }
                }
            }
        }

        // Related
        EpisodeRelated::where('episode_id', $episode->id)->delete();
        if (!empty($requestData['related']) && count($requestData['related'])) {
            foreach ($requestData['related'] as $related_id) {
                if ($related_id) {
                    $er = new EpisodeRelated();
                    $er->episode_id  = $episode->id;
                    $er->related_id  = $related_id;
                    $er->save();
                }
            }
        }

        // Subtitles
        EpisodeSubtitle::where('episode_id', $episode->id)->delete();
        if (!empty($requestData['subtitle']) && count($requestData['subtitle'])) {
            foreach ($requestData['subtitle'] as $key => $itm_id) {
                if ($itm_id && !empty($requestData['subtitle_label'][$key]) && !empty($requestData['subtitle_url'][$key])) {
                    $es = new EpisodeSubtitle();
                    $es->episode_id = $episode->id;
                    $es->is_active  = empty($requestData['subtitle_is_active'][$key]) ? 0 : 1;
                    $es->label      = $requestData['subtitle_label'][$key];
                    $es->url        = $requestData['subtitle_url'][$key];
                    $es->save();
                }
            }
        }
        return redirect()
            ->route('admin.webseries.edit', $model->id)
            ->with('success', 'Updated Successfully');
    }
    public function delete($id)
    {
        $webseries = Webseries::find($id);
        if (!$webseries) {
            return redirect()
                ->route('admin.webseries.index')
                ->with('error', 'Webseries not found!');
        }

        $webseries->delete(); // Soft delete

        return redirect()
            ->route('admin.webseries.index')
            ->with('success', 'Deleted Successfully');
    }
}

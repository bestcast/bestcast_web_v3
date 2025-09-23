<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Genres;
use App\Models\Movies;
use App\Models\MoviesGenres;
use App\Models\MoviesLanguages;
use App\Models\MoviesRelated;
use App\Models\MoviesUsers;
use App\Models\MoviesSubtitle;
use App\Models\Meta;
use Auth;
use Field;
use Lib;

class MoviesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request)
    {
        $data = Movies::getList();
        return $data;
    }


    public function searchbytitle($key='ZXP')
    {
        //if(empty($key)){ return null;}
        $data = Movies::select("id","title as text")->where('title','like',"%".urldecode($key)."%")->latest()->take(20)->get();
        if(!count($data)){ return Movies::select("id","title as text")->latest()->take(20)->get();}
        return $data;
    }


    public function index()
    {
        $data = Movies::getList();
        return view('admin.movies.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $rules=Movies::$rules;unset($rules['video_url']);unset($rules['release_date']);
        unset($rules['duration']);
        $validatedData = $request->validate($rules,Movies::$messages);
        
        $requestData = $request->all();
        $model = new Movies();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.movies.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Movies::find($id);
        if(empty($data)){
            return redirect()->route('admin.movies.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        $meta = Meta::page($id,true);
        return view('admin.movies.edit', ['model'=>$data,'meta'=>$meta]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Movies::find($id);
                $rules = Movies::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Movies::$messages);

            $requestData['status']=empty($requestData['status'])?0:1;
            $requestData['movie_access']=empty($requestData['movie_access'])?0:1;
            $requestData['subtitle_status']=empty($requestData['subtitle_status'])?0:1;
            $requestData['is_upcoming']=empty($requestData['is_upcoming'])?0:1;
            $requestData['topten']=empty($requestData['topten'])?0:1;
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();


            /*
            if(!empty($requestData['meta']) && count($requestData['meta'])){
                foreach($requestData['meta'] as $path=>$value){
                    //$meta = Meta::where('meta_id',$id)->where('path',$path)->where('type',1)->first();
                    $meta = Meta::page($id)->where('path',$path)->first();
                    if(empty($meta->id)){
                        $meta = new Meta();
                        $meta->user_id = Auth::user()->id;
                        $meta->meta_id = $id;
                        $meta->path = $path;
                        $meta->type = 2; //1 - post,2 -movies
                    }
                    $meta->value = $value;
                    $meta->save();
                }
            }
            */

            $MoviesGenres = MoviesGenres::where('movie_id',$id)->delete();
            if(!empty($requestData['genre_id']) && count($requestData['genre_id'])){
                foreach($requestData['genre_id'] as $key=>$itm_id){
                    if($itm_id){
                        $MoviesGenres = new MoviesGenres();
                        $MoviesGenres->movie_id = $id;
                        $MoviesGenres->genre_id = $itm_id;
                        $MoviesGenres->save();
                    }
                }
            }

            $MoviesLanguages = MoviesLanguages::where('movie_id',$id)->delete();
            if(!empty($requestData['language_id']) && count($requestData['language_id'])){
                foreach($requestData['language_id'] as $key=>$itm_id){
                    if($itm_id){
                        $MoviesLanguages = new MoviesLanguages();
                        $MoviesLanguages->movie_id = $id;
                        $MoviesLanguages->language_id = $itm_id;
                        $MoviesLanguages->save();
                    }
                }
            }

            $MoviesUsers = MoviesUsers::where('movie_id',$id)->delete();
            $usertype=User::groupSlug();
            foreach($usertype as $ukey=>$uname):
                if(!empty($requestData[$uname]) && count($requestData[$uname])){
                    foreach($requestData[$uname] as $key=>$itm_id){
                        if($itm_id){
                            $MoviesUsers = new MoviesUsers();
                            $MoviesUsers->movie_id = $id;
                            $MoviesUsers->user_id = $itm_id;
                            $MoviesUsers->group = $ukey;
                            $MoviesUsers->save();
                        }
                    }
                }
            endforeach;


            $MoviesRelated = MoviesRelated::where('movie_id',$id)->delete();
            if(!empty($requestData['related']) && count($requestData['related'])){
                foreach($requestData['related'] as $key=>$itm_id){
                    if($itm_id){
                        $MoviesRelated = new MoviesRelated();
                        $MoviesRelated->movie_id = $id;
                        $MoviesRelated->related_id = $itm_id;
                        $MoviesRelated->save();
                    }
                }
            }

            $MoviesSubtitle = MoviesSubtitle::where('movie_id',$id)->delete();
            if(!empty($requestData['subtitle']) && count($requestData['subtitle'])){
                $i=0;foreach($requestData['subtitle'] as $key=>$itm_id){$i++;
                    if($itm_id){
                        if(!empty($requestData['subtitle_label'][$key]) && !empty($requestData['subtitle_url'][$key])){
                            $MoviesSubtitle = new MoviesSubtitle();
                            $MoviesSubtitle->movie_id = $id;
                            $MoviesSubtitle->is_active = empty($requestData['subtitle_is_active'][$key])?0:1;
                            $MoviesSubtitle->label = $requestData['subtitle_label'][$key];
                            $MoviesSubtitle->url = $requestData['subtitle_url'][$key];
                            $MoviesSubtitle->save();
                        }
                    }
                }
            }

            return redirect()->route('admin.movies.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.movies.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Movies::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.movies.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.movies.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

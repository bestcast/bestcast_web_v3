<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Genres;
use App\Models\Movies;
use App\Models\Blocks;
use App\Models\BlocksMovies;
use App\Models\BlocksShows;
use App\Models\BlocksGenres;
use App\Models\BlocksLanguages;
use Auth;
use Field;
use Lib;

class BlocksController extends Controller
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
        $data = Blocks::getList();
        return $data;
    }

    public function index()
    {
        $data = Blocks::getList();
        return view('admin.blocks.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.blocks.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Blocks::$rules,Blocks::$messages);
        
        $requestData = $request->all();
        $model = new Blocks();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.blocks.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Blocks::find($id);
        if(empty($data)){
            return redirect()->route('admin.blocks.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.blocks.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Blocks::find($id);
                $rules = Blocks::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Blocks::$messages);

            $requestData['status']=empty($requestData['status'])?0:1;
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();


            $BlocksMovies = BlocksMovies::where('blocks_id',$id)->delete();
            if(empty($model->type) && !empty($requestData['movies_id']) && count($requestData['movies_id'])){
                foreach($requestData['movies_id'] as $key=>$itm_id){
                    if($itm_id){
                        $BlocksMovies = new BlocksMovies();
                        $BlocksMovies->movies_id = $itm_id;
                        $BlocksMovies->blocks_id = $id;
                        $BlocksMovies->save();
                    }
                }
            }

            $BlocksShows = BlocksShows::where('blocks_id',$id)->delete();
            if(!empty($model->type) && !empty($requestData['shows_id']) && count($requestData['shows_id'])){
                foreach($requestData['shows_id'] as $key=>$itm_id){
                    if($itm_id){
                        $BlocksShows = new BlocksShows();
                        $BlocksShows->shows_id = $itm_id;
                        $BlocksShows->blocks_id = $id;
                        $BlocksShows->save();
                    }
                }
            }


            $BlocksGenres = BlocksGenres::where('blocks_id',$id)->delete();
            if(!empty($requestData['genre_id']) && count($requestData['genre_id'])){
                foreach($requestData['genre_id'] as $key=>$itm_id){
                    if($itm_id){
                        $BlocksGenres = new BlocksGenres();
                        $BlocksGenres->genre_id = $itm_id;
                        $BlocksGenres->blocks_id = $id;
                        $BlocksGenres->save();
                    }
                }
            }

            $BlocksLanguages = BlocksLanguages::where('blocks_id',$id)->delete();
            if(!empty($requestData['language_id']) && count($requestData['language_id'])){
                foreach($requestData['language_id'] as $key=>$itm_id){
                    if($itm_id){
                        $BlocksLanguages = new BlocksLanguages();
                        $BlocksLanguages->language_id = $itm_id;
                        $BlocksLanguages->blocks_id = $id;
                        $BlocksLanguages->save();
                    }
                }
            }

            return redirect()->route('admin.blocks.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.blocks.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Blocks::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.blocks.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.blocks.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

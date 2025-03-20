<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Genres;
use App\Models\Movies;
use App\Models\Banner;
use App\Models\BannerGenres;
use App\Models\BannerLanguages;
use Auth;
use Field;
use Lib;

class BannerController extends Controller
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
        $data = Banner::getList();
        return $data;
    }

    public function index()
    {
        $data = Banner::getList();
        return view('admin.banner.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Banner::$rules,Banner::$messages);
        
        $requestData = $request->all();
        $model = new Banner();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.banner.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Banner::find($id);
        if(empty($data)){
            return redirect()->route('admin.banner.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.banner.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Banner::find($id);
                $rules = Banner::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Banner::$messages);

            $requestData['status']=empty($requestData['status'])?0:1;
            $model->fill($requestData);
            $model->movies_id = empty($model->type)?$model->movies_id:0;
            $model->shows_id = !empty($model->type)?$model->shows_id:0;
            $model->updated_by = Auth::user()->id;
            $model->save();


            $BannerGenres = BannerGenres::where('banner_id',$id)->delete();
            if(!empty($requestData['genre_id']) && count($requestData['genre_id'])){
                foreach($requestData['genre_id'] as $key=>$itm_id){
                    if($itm_id){
                        $BannerGenres = new BannerGenres();
                        $BannerGenres->genre_id = $itm_id;
                        $BannerGenres->banner_id = $id;
                        $BannerGenres->save();
                    }
                }
            }

            $BannerLanguages = BannerLanguages::where('banner_id',$id)->delete();
            if(!empty($requestData['language_id']) && count($requestData['language_id'])){
                foreach($requestData['language_id'] as $key=>$itm_id){
                    if($itm_id){
                        $BannerLanguages = new BannerLanguages();
                        $BannerLanguages->language_id = $itm_id;
                        $BannerLanguages->banner_id = $id;
                        $BannerLanguages->save();
                    }
                }
            }


            return redirect()->route('admin.banner.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.banner.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Banner::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.banner.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.banner.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

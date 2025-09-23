<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Genres;
use App\Models\Shows;
use Auth;
use Field;
use Lib;

class ShowsController extends Controller
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
        $data = Shows::getList();
        return $data;
    }


    public function searchbytitle($key='ZXP')
    {
        if(empty($key)){ return null;}
        $data = Shows::select("id","title as text")->where('title','like',"%".urldecode($key)."%")->latest()->take(20)->get();
        return $data;
    }


    public function index()
    {
        $data = Shows::getList();
        return view('admin.shows.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.shows.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Shows::$rules,Shows::$messages);
        
        $requestData = $request->all();
        $model = new Shows();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.shows.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Shows::find($id);
        if(empty($data)){
            return redirect()->route('admin.shows.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.shows.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Shows::find($id);
                $rules = Shows::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Shows::$messages);

            $requestData['status']=empty($requestData['status'])?0:1;
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();
            return redirect()->route('admin.shows.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.shows.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Shows::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.shows.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.shows.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

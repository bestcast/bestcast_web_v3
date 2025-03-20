<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Languages;
use Auth;
use Field;
use Lib;

class LanguagesController extends Controller
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
        $data = Languages::getList();
        return $data;
    }

    public function searchbytitle($key='ZXP')
    {
        //if(empty($key)){ return null;}
        $data = Languages::select("id","title as text")->where('title','like',"%".urldecode($key)."%")->latest()->take(20)->get();
        if(!count($data)){ return Languages::select("id","title as text")->latest()->take(20)->get();}
        return $data;
    }
    
    public function index()
    {
        $data = Languages::getList();
        return view('admin.languages.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Languages::$rules,Languages::$messages);
        
        $requestData = $request->all();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model = new Languages();
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.languages.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Languages::find($id);
        if(empty($data)){
            return redirect()->route('admin.languages.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.languages.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Languages::find($id);
                $rules = Languages::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Languages::$messages);

            $requestData['status']=empty($requestData['status'])?0:1;
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();
            return redirect()->route('admin.languages.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.languages.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Languages::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.languages.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.languages.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

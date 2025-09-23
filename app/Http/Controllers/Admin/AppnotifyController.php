<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Movies;
use App\Models\Appnotify;
use Auth;
use Field;
use Lib;

class AppnotifyController extends Controller
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
        $data = Appnotify::getList();
        return $data;
    }

    public function index()
    {
        $data = Appnotify::getList();
        return view('admin.appnotify.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.appnotify.create');
    }

    public function createsave(Request $request)
    {
        $rules=Appnotify::$rules;unset($rules['movie_id']);unset($rules['thumbnail_id']);
        $validatedData = $request->validate($rules,Appnotify::$messages);
        
        $requestData = $request->all();
        $model = new Appnotify();
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.appnotify.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Appnotify::find($id);
        if(empty($data)){
            return redirect()->route('admin.appnotify.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.appnotify.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $validatedData = $request->validate(Appnotify::$rules,Appnotify::$messages);

            $model = Appnotify::find($id);
            $requestData = $request->all();
            $model->fill($requestData);
            $model->save();
            return redirect()->route('admin.appnotify.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.appnotify.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Appnotify::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.appnotify.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.appnotify.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

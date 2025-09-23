<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Auth;
use Field;
use Lib;

class MenuController extends Controller
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

    public function index()
    {
        return view('admin.menu.index');
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function createsave(Request $request)
    {
        $requestData = $request->all();
        $model = new Menu();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect()->route('admin.menu.edit', $model->id);//->with('success', 'Saved Successfully')
    }

    public function edit($id)
    {
        $data = Menu::find($id);
        if(empty($data)){
            return redirect()->route('admin.menu.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.menu.edit', ['model'=>$data]);
    }
    public function editsave($id,Request $request)
    {
        if($id){
            $model = Menu::find($id);
            $requestData = $request->all();
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->status = empty($requestData['status'])?0:1;
            $model->show_loggedin_user = empty($requestData['show_loggedin_user'])?0:1;
            $model->target = empty($requestData['target'])?0:1;
            $model->post_id = empty($requestData['post_id'])?0:$requestData['post_id'];
            $model->save();
        }
        return redirect()->route('admin.menu.edit',$id)->with('success', 'Updated Successfully');
    }

    public function delete($id)
    {
        if($id){
            $model = Menu::find($id);
            if(!empty($model)){
                $model->delete();
            }
        }
        return redirect()->route('admin.menu.index')->with('success', 'Deleted Successfully');
    }

}

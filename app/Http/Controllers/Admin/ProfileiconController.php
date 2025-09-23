<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Profileicon;
use Auth;
use Field;
use Lib;
use App\Models\UsersProfile;

class ProfileiconController extends Controller
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
        $data = Profileicon::getList();
        return $data;
    }

    public function index()
    {
        $data = Profileicon::getList();
        return view('admin.profileicon.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.profileicon.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Profileicon::$rules,Profileicon::$messages);
        
        $requestData = $request->all();
        $model = new Profileicon();
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.profileicon.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Profileicon::find($id);
        if(empty($data)){
            return redirect()->route('admin.profileicon.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.profileicon.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Profileicon::find($id);
                $rules = Profileicon::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Profileicon::$messages);

            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();

            return redirect()->route('admin.profileicon.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.profileicon.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $is_linked = UsersProfile::where('profileicon_id',$id)->first();
            if(empty($is_linked)){
                $model = Profileicon::find($id);
                if(!empty($model)){
                    $model->delete();
                    return redirect()->route('admin.profileicon.index')->with('success', 'Deleted Successfully');
                }
            }else{
                return redirect()->route('admin.profileicon.index')->with('error', 'Cannot delete this record. Linked with user profile.');
            }
        }
        return redirect()->route('admin.profileicon.index')->with('error', 'Deletion Failed! Please try again!');
    }


}

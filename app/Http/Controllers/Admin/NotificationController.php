<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Notification;
use Auth;
use Field;
use Lib;

class NotificationController extends Controller
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
        $data = Notification::getList();
        return view('admin.notification.index', ['model'=>$data]);
    }

    public function markread($id,Request $request)
    {
            if(!empty($id) && $id=='markallread'){
                $model = Notification::where('type','admin')->update(['mark_read'=>1]);
                return redirect()->route('admin.notification.index')->with('success', 'Updated Successfully');
            }
            if($id){
                $model = Notification::find($id);
                $model->mark_read = ($model->mark_read)?0:1;
                $model->save();
                $text=(!$model->mark_read)?1:0;
                $totalcount=Notification::adminCount();
                return array("message"=>1,"is_read"=>$text,"totalcount"=>$totalcount);
            }
            return array("message"=>0,"text"=>'Failed');
    }
    public function editsave($id,Request $request)
    {
        if($id){
            $model = Notification::find($id);
            $requestData = $request->all();
            $requestData['mark_read']=empty($requestData['mark_read'])?0:1;
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();
            return redirect()->route('admin.notification.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.notification.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }


    public function delete($id)
    {
        if($id){
            $model = Notification::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.notification.index')->with('success', 'Delete Successfully!');
            }
        }
        return redirect()->route('admin.notification.index')->with('error', 'Failed to Update! Please try again!');
    }

}

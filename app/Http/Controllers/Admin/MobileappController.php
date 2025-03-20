<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Meta;
use App\Models\Appnotify;
use Auth;
use Field;
use Lib;

class MobileappController extends Controller
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
        $data = Meta::mobileapp(1,true);
        return view('admin.mobileapp.index', ['meta'=>$data]);
    }


    public function save($id,Request $request)
    {
        if($id){
            $requestData = $request->all();
            if(!empty($requestData['meta']) && count($requestData['meta'])){
                foreach($requestData['meta'] as $path=>$value){
                    //$meta = Meta::where('meta_id',$id)->where('path',$path)->where('type',1)->first();
                    $meta = Meta::mobileapp($id)->where('path',$path)->first();
                    if(empty($meta->id)){
                        $meta = new Meta();
                        $meta->meta_id = $id;
                        $meta->path = $path;
                        $meta->type = 4; //1 - post
                    }
                    $meta->value = $value;
                    $meta->save();
                }
            }
        }
        return redirect()->route('admin.mobileapp.index')->with('success', 'Updated Successfully');
    }

}

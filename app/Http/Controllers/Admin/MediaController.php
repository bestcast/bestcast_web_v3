<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Media;
use App\Models\CoreConfig;
use Auth;
use Lib;
use App\Models\Post;

class MediaController extends Controller
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
        $data = Menu::getList();
        return $data;
    }
    /*
    public function list(Request $request)
    {
        //if ($request->ajax()) {
            $data = Media::latest()->get();
            //$data = Media::query()->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('urlkey', function($data) {
                    if(!empty($data->urlkey)){
                        $img=Lib::publicImgSrc(Lib::getImgResizeSrc($data->urlkey,'100'));
                    }else{
                        $img=Lib::placeholder('80_80');
                    }   
                    return $img;
                })
                ->editColumn('created_at', function($data) {
                    return date("d/m/Y H:i:s",strtotime($data->created_at));
                })
                // ->editColumn('excerpt', function($data) {
                //     return strip_tags($data->excerpt);
                // })
                ->addColumn('action', 'admin.media.action')
                ->rawColumns(['action'])
                ->make(true);
        //}
    }
    */


    public function index()
    {

        $data = Media::getList();
        return view('admin.media.index', ['model'=>$data]);
    }

    public function popuplist(Request $request)
    {
        // $data = Media::latest()->get();
        // return view('admin.media.popuplist', ['model'=>$data]);
            //$data = Media::query()->orderBy('id', 'DESC');
        //where('type', 'image')->
            $media = Media::query();
            if(!empty($request->searchtext)){
                $media->where('filename','like', '%'.$request->searchtext.'%');
            }
            if(!empty($request->selectedid) && is_numeric($request->selectedid)){
                $media->orderByRaw('FIELD(id, '.$request->selectedid.') DESC');
            }
            $data = $media->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('urlkey', function($data) {
                    return Lib::publicImgSrc(Lib::getImgResizeSrc($data->urlkey,'100'));
                })
                ->editColumn('urlkeyonly', function($data) {
                    return $data->urlkey;
                })
                ->make(true);
    }

    public function view($id)
    {
        $data = Media::find($id);
        return view('admin.media.view', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.media.create');
    }
    public function createsave(Request $request)
    {
        //$validatedData = $request->validate(Media::$rules,Media::$messages);


        $requestData = $request->all();
        $model = new Media();
        $model->fill($requestData);


        $postid='';

        $success=0;
        $fname='urlkey';$tmppath=$fname."_file";if(!empty($request->$tmppath)){
            $uploadfn=Lib::SavePublicMedia($request,$fname);
            if(!empty($uploadfn)){
                $success=1;
                $model->$fname=$uploadfn;
                $model->filename=$filename=$request->$tmppath->getClientOriginalName();
                if(empty($requestData['alt'])){
                    $model->alt=substr($model->filename,0,strpos($model->filename,'.'));
                }
                $model->type=$request->$tmppath->getClientMimeType();
                if($model->type=='application/pdf'){
                    //pdf code condition
                }else{
                    $core=CoreConfig::list();
                    Lib::generateSizeFiles($model->$fname,$core);

                    if(strpos($filename,'-xxxx-')){
                        $filenameArr=explode("-xxxx-",$filename);
                        $model->alt=$filenameArr[0];
                        $postid=is_numeric($filenameArr[1])?$filenameArr[1]:'';
                    }
                }
            }
        }


        if(!$success){
            $validatedData = $request->validate(Media::$rules,Media::$messages);
        }
        $model->user_id = Auth::user()->id;
        $model->created_by = Auth::user()->id;
        $model->save();

        if(!empty($postid)){
            $post = Post::find($postid);
            if(!empty($post->id)){
                $post->image_id = $model->id;
                $post->save();
            }
        }
        return redirect()->route('admin.media.view',$model->id)->with('success', 'Saved Successfully');
    }

    public function edit($id)
    {
        $data = Media::find($id);
        if(empty($data)){
            return redirect()->route('admin.media.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.media.edit', ['model'=>$data]);
    }
    public function editsave($id,Request $request)
    {

        if($id){
            //$rules = Media::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
            //$validatedData = $request->validate($rules,Media::$messages);
            $model = Media::find($id);
            $requestData = $request->all();
            $model->fill($requestData);
            $success=0;
            $fname='urlkey';$tmppath=$fname."_file";if(!empty($request->$tmppath)){
                $uploadfn=Lib::SavePublicMedia($request,$fname);
                if(!empty($uploadfn)){
                    $model->$fname=$uploadfn;
                    $model->filename=$request->$tmppath->getClientOriginalName();
                    $model->type=$request->$tmppath->getClientMimeType();
                    if($model->type=='application/pdf'){
                        //pdf condition
                    }else{
                        $core=CoreConfig::list();
                        Lib::generateSizeFiles($model->$fname,$core);
                    }
                    $success=1;
                }
            }

            if(!empty($request->$tmppath) || !empty($request->$fname)){
                $success=1;
            }
            if(!$success){
                $validatedData = $request->validate(Media::$rules,Media::$messages);
            }
            $model->updated_by = Auth::user()->id;
            $model->save();
        }
        return redirect()->route('admin.media.edit',$id)->with('success', 'Updated Successfully');
    }

    public function delete($id)
    {
        if($id){
            $model = Media::find($id);
            if(!empty($model)){
                $model->delete();
            }
        }
        return redirect()->route('admin.media.index')->with('success', 'Deleted Successfully');
    }

}

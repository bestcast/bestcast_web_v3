<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\User;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use App\Models\Category;
use App\Models\PostCategory;
use Auth;
use Field;
use Lib;

class PostController extends Controller
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
        if ($request->ajax()) {
            $data = Post::where('type','post')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->editColumn('excerpt', function($data) {
                //     return strip_tags($data->excerpt);
                // })
                ->editColumn('created_at', function($data) {
                    return date("d/m/Y H:i:s",strtotime($data->created_at));
                })
                ->editColumn('status', function($data) {
                    return Field::getStatus($data->status);
                })
                ->addColumn('action', 'admin.post.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    //FilterCode00
    public function ajax($key='ZXP',$selectedid=0,$uuid='')
    {
        if(empty($key) || empty($uuid)){ return null;}
        $data = Post::where('type','!=','blog')->where('title','like','%'.urldecode($key).'%')->latest()->take(20)->get();
        $html='';
        $lsuuid=\Illuminate\Support\Str::uuid()->toString();
        foreach($data as $item){
            $cls='';if($item->id==$selectedid){$cls='active';}
            $html.='<div class="ls gen-'.$lsuuid.' '.$cls.'" data-val="'.$item->id.'" data-title="'.$item->title.'">'.ucwords($item->type).': '.$item->title.' ('.$item->id.')</div>';
        }
        $html=empty($html)?'<div class="ls gen-'.$lsuuid.' " data-val="0" data-title="">None</div>':$html;
        if($html){
            $html.='<script type="text/javascript">
                  jQuery(".gen-'.$lsuuid.'").on("click",function(){
                    jQuery(".'.$uuid.'").find(".ajaxdata-selected").val(jQuery(this).attr("data-val"));
                    jQuery(".'.$uuid.'").find(".ajax-generate").val(jQuery(this).attr("data-title"));
                    jQuery(".'.$uuid.'").find(".generate").html("");
                  });
                </script>';
        }
        return $html;
    }

    public function ajaxauthor($key='ZXP',$selectedid=0,$uuid='')
    {
        if(empty($key) || empty($uuid)){ return null;}
        $data = User::where('firstname','like','%'.urldecode($key).'%')->latest()->take(20)->get();
        //'
        $html='';
        $lsuuid=\Illuminate\Support\Str::uuid()->toString();
        foreach($data as $item){
            $cls='';if($item->id==$selectedid){$cls='active';}
            $html.='<div class="ls gen-'.$lsuuid.' '.$cls.'" data-val="'.$item->id.'" data-title="'.$item->firstname.' '.$item->lastname.' ('.$item->id.')">'.ucwords($item->firstname).' '.ucwords($item->lastname).' ('.$item->id.')</div>';
        }
        $html=empty($html)?'<div class="ls gen-'.$lsuuid.' " data-val="0" data-title="">None</div>':$html;
        if($html){
            $html.='<script type="text/javascript">
                  jQuery(".gen-'.$lsuuid.'").on("click",function(){
                    jQuery(".'.$uuid.'").find(".ajaxdata-selected").val(jQuery(this).attr("data-val"));
                    jQuery(".'.$uuid.'").find(".ajax-generate").val(jQuery(this).attr("data-title"));
                    jQuery(".'.$uuid.'").find(".generate").html("");
                  });
                </script>';
        }
        return $html;
    }
    //FilterCode00

    public function index()
    {
        return view('admin.post.index');
    }

    public function view($id)
    {
        $data = Post::find($id);
        return view('admin.post.view', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.post.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Post::$rules,Post::$messages);
        
        $requestData = $request->all();
        $model = new Post();
        $pubdate=$request->get('published_date');$pubdate=empty($pubdate)?date("Y-m-d H:i:s"):date("Y-m-d H:i:s",strtotime(str_replace('/','-',$pubdate).":00"));$requestData['published_date']=$pubdate;
        $model->fill($requestData);
        $model->user_id = Auth::user()->id;
        $model->created_by = Auth::user()->id;
        $model->save();

        if($model->type=="category"){
            $Category = new Category();
            $Category->post_id = $model->id;
            $Category->user_id = Auth::user()->id;
            $Category->parent_id=empty($requestData['cat_parent_id'])?0:$requestData['cat_parent_id'];
            $Category->template = empty($requestData['cat_template'])?0:$requestData['cat_template'];
            $Category->urlkey = empty($requestData['urlkey_cat'])?$model->urlkey:Lib::slug($requestData['urlkey_cat']);
            $Category->path =$Category->urlkey;
            $Category->title = $model->title;
            $Category->status = 1;
            $Category->sort = empty($requestData['cat_sort'])?0:$requestData['cat_sort'];
            $Category->created_by = Auth::user()->id;
            $Category->save();
        }


        return redirect()->route('admin.post.edit', $model->id);//->with('success', 'Saved Successfully')
    }

    public function edit($id)
    {
        //Category::generate();
        $data = Post::find($id);
        if(empty($data)){
            return redirect()->route('admin.post.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        //$meta = Meta::where('meta_id',$id)->where('type',1)->pluck('value','path');
        $meta = Meta::page($id,true);
        return view('admin.post.edit', ['model'=>$data,'meta'=>$meta]);
    }
    public function editsave($id,Request $request)
    {
        if($id){
            $model = Post::find($id);
                $rules = Post::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slugWithSlash($request['urlkey']);
                // if($model->type=='category'){
                //     unset($rules['urlkey']);
                // }
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Post::$messages);
            $pubdate=$request->get('published_date');$pubdate=empty($pubdate)?date("Y-m-d H:i:s"):date("Y-m-d H:i:s",strtotime(str_replace('/','-',$pubdate).":00"));$requestData['published_date']=$pubdate;
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();

            if(!empty($requestData['meta']) && count($requestData['meta'])){
                foreach($requestData['meta'] as $path=>$value){
                    //$meta = Meta::where('meta_id',$id)->where('path',$path)->where('type',1)->first();
                    $meta = Meta::page($id)->where('path',$path)->first();
                    if(empty($meta->id)){
                        $meta = new Meta();
                        $meta->user_id = Auth::user()->id;
                        $meta->meta_id = $id;
                        $meta->path = $path;
                        $meta->type = 1; //1 - post
                    }
                    $meta->value = $value;
                    $meta->save();
                }
            }

            $postContent = PostContent::where('post_id',$id)->first();
            if(empty($postContent->post_id)){
                $postContent = new PostContent();
                $postContent->post_id = $id;
                $postContent->user_id = Auth::user()->id;
                $postContent->created_by = Auth::user()->id;
            }else{
                $postContent->updated_by = Auth::user()->id;
            }
            $postContent->content = $requestData['content'];
            $postContent->save();



            if($model->type=="category"){
                $Category = Category::where('post_id',$id)->first();
                if(empty($Category->post_id)){
                    $Category = new Category();
                    $Category->post_id = $id;
                    //$Category->path = empty($requestData['urlkey_cat'])?$model->urlkey:Lib::slug($requestData['urlkey_cat']);
                    $Category->user_id = Auth::user()->id;
                    $Category->created_by = Auth::user()->id;
                }else{
                    //$catpath=Category::path($Category,$Category->id,$Category->urlkey);
                    //$Category->path = $catpath['path'];
                    //$Category->path_ids = $catpath['path_ids'];
                    $Category->updated_by = Auth::user()->id;
                }
                $Category->parent_id=empty($requestData['cat_parent_id'])?0:$requestData['cat_parent_id'];
                $Category->template = empty($requestData['cat_template'])?0:$requestData['cat_template'];
                $Category->urlkey = empty($requestData['urlkey_cat'])?$model->urlkey:Lib::slug($requestData['urlkey_cat']);
                $Category->title = $model->title;
                $Category->status = $model->status;
                $Category->sort = empty($requestData['cat_sort'])?0:$requestData['cat_sort'];
                $Category->save();
                Category::generate();
            }else{
                $PostCategory = PostCategory::where('post_id',$id)->delete();
                if(!empty($requestData['category']) && count($requestData['category'])){
                    foreach($requestData['category'] as $key=>$catid){
                        if($catid){
                            $PostCategory = new PostCategory();
                            $PostCategory->category_id = $catid;
                            $PostCategory->primary = 0;
                            if(!empty($requestData['category_primary'])){
                                if($catid==$requestData['category_primary']){
                                    $PostCategory->primary = 1;
                                }
                            }
                            $PostCategory->post_id = $id;
                            $PostCategory->user_id = Auth::user()->id;
                            $PostCategory->created_by = Auth::user()->id;
                            $PostCategory->save();
                        }
                    }
                }
            }



        }
        return redirect()->route('admin.post.edit',$id)->with('success', 'Updated Successfully');
    }

    public function delete($id)
    {
        if($id){
            $model = Post::find($id);
            if(!empty($model)){
                $model->delete();
            }

            if(!empty($type) && $type=="category"){
                $model = Category::where('post_id',$id)->first();
                if(!empty($model)){
                    $model->delete();
                }
                return redirect()->route('admin.post.category')->with('success', 'Deleted Successfully');
            }
            if(!empty($type) && $type=="blog"){
                return redirect()->route('admin.blog.index')->with('success', 'Deleted Successfully');
            }
            if(!empty($type) && $type=="page"){
                return redirect()->route('admin.page.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.post.index')->with('success', 'Deleted Successfully');
    }


}

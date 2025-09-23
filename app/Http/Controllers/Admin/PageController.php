<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use App\Models\Category;
use App\Models\PostCategory;
use Auth;
use Field;
use Lib;

class PageController extends Controller
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
            $data = Post::where('type','page')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->editColumn('excerpt', function($data) {
                //     return strip_tags($data->excerpt);
                // })
                ->editColumn('template', function($data) {
                    return Field::getTemplate($data->template);
                })
                ->editColumn('created_at', function($data) {
                    return date("d/m/Y H:i:s",strtotime($data->created_at));
                })
                ->editColumn('status', function($data) {
                    return Field::getStatus($data->status);
                })
                ->addColumn('action', 'admin.page.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function searchbytitle($key='ZXP')
    {
        //if(empty($key)){ return null;}
        $data = Post::select("id","title as text")->where('title','like',"%".urldecode($key)."%")->latest()->take(20)->get();
        if(!count($data)){ return Post::select("id","title as text")->latest()->take(20)->get();}
        return $data;
    }

    public function index()
    {
        return view('admin.page.index');
    }


    public function create()
    {
        return view('admin.page.create');
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

        return redirect()->route('admin.post.edit', $model->id);//->with('success', 'Saved Successfully')
    }

}

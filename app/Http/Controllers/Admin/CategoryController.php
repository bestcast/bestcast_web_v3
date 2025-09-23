<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use Auth;
use Field;
use Lib;

class CategoryController extends Controller
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

    public function categorylist(Request $request)
    {
        if ($request->ajax()) {
            $data = Post::where('type','category')->latest()->get();
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
    public function category()
    {
        return view('admin.post.category');
    }
    public function categorycreate()
    {
        return view('admin.post.categorycreate');
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Webseries;
use App\Models\Meta;


class WebSeriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = webseries::getList();
        return view('admin.webseries.index', ['model'=>$data]);
    }
    public function create()
    {
        $model = new Webseries();
        return view('admin.webseries.create', compact('model'));
    }
    public function createsave(Request $request)
    {
        $requestData = $request->all();
        $model = new Webseries();
        $requestData['status']=empty($requestData['status'])?0:1;
        $requestData['movie_access']=empty($requestData['movie_access'])?0:1;
        $model->fill($requestData);
        //$model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.webseries.edit', $model->id)->with('success', 'Created Successfully');
    }
    /*public function edit($id)
    {
        $data = Webseries::find($id);
        if(empty($data)){
            return redirect()->route('admin.webseries.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        $meta = Meta::page($id,true);
        return view('admin.webseries.edit', ['model'=>$data,'meta'=>$meta]);
    }*/
    public function edit($id)
    {
        $data = Webseries::with('seasons')->find($id);

        if(empty($data)){
            return redirect()
                ->route('admin.webseries.index')
                ->with('error', 'Record does not exist! ID:'.$id);
        }

        $meta = Meta::page($id,true);

        return view('admin.webseries.edit', [
            'model'=>$data,
            'meta'=>$meta,
            'seasons'=>$data->seasons  // 👈 add this
        ]);
    }
    public function editsave(Request $request, $id){
        $model = Webseries::findOrFail($id);

        $requestData = $request->all();

        $requestData['status'] = empty($requestData['status']) ? 0 : 1;
        $requestData['movie_access'] = empty($requestData['movie_access']) ? 0 : 1;

        $model->fill($requestData);
        $model->save();

        return redirect()
            ->route('admin.webseries.edit', $model->id)
            ->with('success', 'Updated Successfully');
    }
    public function delete($id)
    {
        /*if($id){
            $model = Webseries::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.webseries.index')->with('success', 'Deleted Successfully');
            }
        }*/
        $webseries = Webseries::find($id);
        if (!$webseries) {
            return redirect()
                ->route('admin.webseries.index')
                ->with('error', 'Webseries not found!');
        }

        $webseries->delete(); // Soft delete

        return redirect()
            ->route('admin.webseries.index')
            ->with('success', 'Deleted Successfully');
        /*$seasonCount = $webseries->seasons()->count();
        $episodeCount = \App\Models\Episode::whereHas('season', function($q) use ($webseries) {
            $q->where('webseries_id', $webseries->id);
        })->count();

        //return redirect()->route('admin.webseries.index')->with('error', 'Deletion Failed! Please try again!');
        return view('admin.webseries.index', compact('seasonCount','episodeCount'));*/
    }
}

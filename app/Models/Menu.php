<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use Lib;
use Auth;

class Menu extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='menu';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'name',
        'category',
        'classname',
        'post_id',
        'parent_id',
        'url',
        'target',
        'icon_id',
        'image_id',
        'thumbnail_id',
        'sort',
        'type',
        'content',
        'created_by',
        'updated_by'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'status'            => 'integer',
        'category'          => 'integer',
        'name'              => 'string',
        'classname'         => 'string',
        'post_id'           => 'integer',
        'parent_id'         => 'integer',
        'url'               => 'string',
        'target'            => 'integer',
        'icon_id'           => 'integer',
        'image_id'          => 'integer',
        'thumbnail_id'      => 'integer',
        'sort'              => 'integer',
        'type'              => 'integer',
        'content'           => 'string',
        'created_by'        => 'integer',
        'updated_by'        => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



    /**
     * Validation rules for the attributes
     *
     * @var array
     */
    public static $rules = [
        'urlkey' => 'required|max:1000'
    ];


    public static $messages = [
        'urlkey.required' => 'URL Key is required.'
    ];


    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Media','image_id','id');
    }
    public function icon()
    {
        return $this->belongsTo('App\Models\Media','icon_id','id');
    }
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }
    public function post()
    {
        return $this->belongsTo('App\Models\Post','post_id','id');
    }
    public function categorytable()
    {   
        return $this->belongsTo('App\Models\Category','post_id','post_id');
    }

    public static function selectList($selval=array(),$id=0){
        $cat = Menu::all()->sortBy('sort');
        $tree=array();
        foreach($cat as $item){
            $tree[$item->id]=array('id'=>$item->id,'parent_id'=>$item->parent_id,'item'=>$item);
        }
        $list=Menu::selectListOption($tree,array(),0,$selval,$id);
        return $list;
    }
    public static function selectListOption(array $elements, array $branch, $parentId=0,$selval,$cid){ 
        if(is_numeric($selval)){$selval=array($selval);}       
        // group elements by parents if it does not comes on the parameters
        if (empty($branch)) {
          $branch = array();
          foreach ($elements as $element) {
            $branch[$element["parent_id"]][$element["id"]] = $element;
          }
        }
        // echo the childs referenced by the parentId parameter
        if (isset($branch[$parentId])) {
          $output = '';
          foreach ($branch[$parentId] as $keyBranch => $itemBranch) {
            $space='';
            $parent_id=$itemBranch["item"]->parent_id;
            while($parent_id){
                $space.="   ";
                $parent_id=$elements[$parent_id]["item"]->parent_id;
            }
            $sel=$dis='';
            if(in_array($itemBranch["id"],$selval)) $sel=' selected="selected" ';
            if($itemBranch["id"]==$cid) $dis=' disabled="disabled" ';
            $output.='<option value="'.$itemBranch["id"].'" '.$sel.' '.$dis.'>'.$space.$itemBranch["item"]->name.'</option>';
            if(!$dis){
                $output.=Menu::selectListOption($elements, $branch, $itemBranch["id"],$selval,$cid);
            }
          }
          return $output;
        }
    }

    public static function divList(){
        $cat = Menu::all()->sortBy('sort');
        $tree=array();
        foreach($cat as $item){
            $tree[]=array('id'=>$item->id,'parent_id'=>$item->parent_id,'item'=>$item);
        }
        $list=Menu::divListItem($tree,array(),0);
        return $list;
    }
    public static function divListItem(array $elements, array $branch, $parentId=0){ 
        // group elements by parents if it does not comes on the parameters
        if (empty($branch)) {
          $branch = array();
          foreach ($elements as $element) {
            $branch[$element["parent_id"]][$element["id"]] = $element;
          }
        }
        // echo the childs referenced by the parentId parameter
        if (isset($branch[$parentId])) {
          $output= '<ul>';
          foreach ($branch[$parentId] as $keyBranch => $itemBranch) { $actClass=$edit='';
            $edit.='<span class="action">';
                $edit.='<a class="btn btn-primary btn-xsm" href="'.route('admin.menu.edit',$itemBranch["item"]->id).'">Edit</a>';
                if(!empty($itemBranch["item"]->status)){
                    $url=!empty($itemBranch["item"]->post)?Lib::publicUrl($itemBranch["item"]->post->urlkey):$itemBranch["item"]->url;
                    $edit.=' <a target="_blank" class="btn btn-info btn-xsm" href="'.$url.'">View</a>';
                }else{$actClass.=' disabled ';}
            $edit.='</span>';
            $child=Menu::divListItem($elements, $branch, $itemBranch["id"]);
            $output.='<li class="'.$actClass.'">'.$itemBranch["item"]->name.' '.$edit;
                $output.=$child;
            $output.='</li>';
          }
          $output.= '</ul>';
          return $output;
        }
    }



    public static function menuList($postid=0,$category=0){
        $cat = Menu::where('status',1)->where('category',$category)->orderBy('sort','asc')->get();
        $tree=array();
        $user=Auth::user();
        foreach($cat as $item){
            if(empty($user->id)){
                if($item->show_loggedin_user){
                    continue;
                }
            }
            $tree[]=array('id'=>$item->id,'parent_id'=>$item->parent_id,'item'=>$item);
        }
        $list=Menu::menuListItem($tree,array(),0,$postid);
        return $list;
    }
    public static function menuListItem(array $elements, array $branch, $parentId=0,$postid){ 
        // group elements by parents if it does not comes on the parameters
        $ulClass='submenu';
        if (empty($branch)) {
          $ulClass='mainmenu';
          $branch = array();
          foreach ($elements as $element) {
            $branch[$element["parent_id"]][$element["id"]] = $element;
          }
        }
        // echo the childs referenced by the parentId parameter
        if (isset($branch[$parentId])) {
          $output= '<ul class="'.$ulClass.'">';
              //if($ulClass!='mainmenu'){$output.= '<div class="sub-menu-wrap">';}
              foreach ($branch[$parentId] as $keyBranch => $itemBranch) { $actClass=$edit=$url='';
                $actClass.='menu-item  page-item-'.$itemBranch["item"]->post_id.'  menu-item-'.$itemBranch["item"]->id.' '.$itemBranch["item"]->classname.' ';
                $child=Menu::menuListItem($elements, $branch, $itemBranch["id"],$postid);
                if($postid==$itemBranch["item"]->post_id){
                    $actClass.=' current_page_item  ';
                }

                if(!empty($child)){
                    $actClass.=' has-droupdown  ';
                }

                //check category url start
                if(!empty($itemBranch["item"]->post->type) && $itemBranch["item"]->post->type=="category"){
                    if(!empty($itemBranch["item"]->categorytable->path)){
                        $url=Lib::publicUrl($itemBranch["item"]->categorytable->path);
                    }
                }
                //check post url start
                if(empty($url)){
                    $url=!empty($itemBranch["item"]->post)?Lib::publicUrl($itemBranch["item"]->post->urlkey):$itemBranch["item"]->url;
                }
                $target=empty($itemBranch["item"]->target)?'':' target="_blank" ';
                $output.='<li class="'.$actClass.'"><a href="'.$url.'" '.$target.' >'.$itemBranch["item"]->name.'</a>';
                    $output.=$child;
                $output.='</li>';
              }
              //if($ulClass!='mainmenu'){$output.= '</div>';}
          $output.= '</ul>';
          return $output;
        }
    }



    public static function getApiList()
    {        

        $data = Menu::with('post')->where('status',1)->orderBy('sort','asc')->get();
        //dd($data);
        //$getpageid=app('request')->input('page_id');
        return $data; 

    }
}


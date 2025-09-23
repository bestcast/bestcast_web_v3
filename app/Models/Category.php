<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use Lib;
use DB;

class Category extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='category';

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
        'user_id',
        'parent_id',
        'post_id',
        'postcount',
        'sort',
        'status',
        'urlkey',
        'title',
        'path',
        'path_ids',
        'template',
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
        'user_id'           => 'integer',
        'parent_id'         => 'integer',
        'post_id'           => 'integer',
        'postcount'         => 'integer',
        'sort'              => 'integer',
        'status'            => 'integer',
        'urlkey'            => 'string',
        'title'             => 'string',
        'path'              => 'string',
        'path_ids'          => 'string',
        'template'          => 'integer',
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

    /*
      @php ($val=empty($model->category->parent_id)?0:$model->category->parent_id)
      @php ($cid=empty($model->category->id)?0:$model->category->id)
      @php ($selval=old('template',$val)) 
      <select name="cat_parent_id" class="form-select">
          <option value="0">None</option>
          {!! App\Models\Category::selectList($selval,$cid) !!}
      </select>
    */
    public static function selectList($selval,$id,$tag='option',$arr=array()){
        $cat = Category::item();
        $tree=array();
        foreach($cat['cat'] as $item){
            $tree[]=array('id'=>$item->id,'parent_id'=>$item->parent_id,'post_id'=>$item->post_id,'title'=>$item->title,'path_ids'=>$item->path_ids,'postcount'=>$item->postcount);
        }
        $list=Category::selectListOption($tree,array(),0,$selval,$id,$tag,$arr);
        return $list;
    }
    public static function selectListOption(array $elements, array $branch, $parentId=0,$selval,$cid,$tag,$arr){ 
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
            $space=preg_replace('/[0-9]+/', '',implode("   ",explode("/",$itemBranch['path_ids'])));
            $cls=$chk=$sel=$dis='';
            if(in_array($itemBranch["id"],$selval)){ $sel=' selected="selected" '; $chk=' checked '; $cls=' selected ';   }
            if($itemBranch["id"]==$cid){ $dis=' disabled="disabled" '; }
            
            if($tag=='checkbox'){
                $output.='<div class="ls '.$cls.'">';
                    $output.='<input id="lschk-'.$itemBranch["id"].'" type="checkbox" class="checkbox" name="category[]" value="'.$itemBranch["id"].'" '.$chk.' '.$dis.'>';
                    $output.='<label for="lschk-'.$itemBranch["id"].'">'.$space.$itemBranch['title'].'</label>';
                    $primarycls=$primarychk='';
                    if(isset($arr['primary']) && !empty($arr['primary']->category_id) && $arr['primary']->category_id==$itemBranch["id"]){
                        $primarycls='selected';$primarychk=' checked ';
                    }
                    $output.='<div class="setprimary '.$cls.'">';
                        $output.='<input id="lsrad-'.$itemBranch["id"].'" type="radio" class="radio" '.$primarychk.' name="category_primary" value="'.$itemBranch["id"].'" >';
                        $output.='<label for="lsrad-'.$itemBranch["id"].'">Primary</label>';
                    $output.='</div>';
                $output.='</div>';
            }else{
                $output.='<option value="'.$itemBranch["id"].'" '.$sel.' '.$dis.'>'.$space.$itemBranch['title'].'</option>';
            }

            if(!$dis){
                $output.=Category::selectListOption($elements, $branch, $itemBranch["id"],$selval,$cid,$tag,$arr);
            }
          }
          return $output;
        }
    }


    public static function divList(){
        $cat = Category::item();
        $tree=array();
        foreach($cat['cat'] as $item){
            $tree[]=array('id'=>$item->id,'status'=>$item->status,'parent_id'=>$item->parent_id,'post_id'=>$item->post_id,'title'=>$item->title,'postcount'=>$item->postcount,'path'=>$item->path,'path_ids'=>$item->path_ids);
        }
        $list=Category::divListItem($tree,array(),0);
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
          foreach ($branch[$parentId] as $keyBranch => $itemBranch) { $actClass='';
            $edit='<span class="action">';
                $edit.='<a class="btn btn-primary btn-xsm" href="'.route('admin.post.edit',$itemBranch["post_id"]).'">Edit</a>';
                if(!empty($itemBranch["status"])){
                    $edit.=' <a target="_blank" class="btn btn-info btn-xsm" href="'.Lib::publicUrl($itemBranch["path"]).'">View</a>';
                }else{$actClass='disabled';}
            $edit.='</span>';
            $output.='<li class="'.$actClass.'">'.$itemBranch['title'].'  <span>('.$itemBranch['postcount'].')</span> '.$edit.' <span class="catidtext">[ID:'.$itemBranch['id'].']<span> </li>';
            $output.=Category::divListItem($elements, $branch, $itemBranch["id"]);
          }
          $output.= '</ul>';
          return $output;
        }
    }

    //$countarr=Category::getpostcount();
    public static function getpostcount(){
        /*  SELECT 
            pc.category_id, count(pc.category_id) as total_category
            FROM `post_category` as pc 
            left join post p on p.id=pc.post_id 
            where p.status=1 and p.deleted_at is null 
            GROUP BY pc.category_id
            HAVING pc.category_id >=0; */
        $category = DB::select('SELECT pc.category_id, count(pc.category_id) as total_category FROM `post_category` as pc  left join post p on p.id=pc.post_id  where p.status=1 and p.deleted_at is null GROUP BY pc.category_id HAVING pc.category_id >=0');
        $arr=array();
        Category::where('id','!=',0)->update(['postcount' => 0]);
        foreach($category as $key=>$item){
            if(!empty($item->category_id)){
                Category::where('id',$item->category_id)->update([
                    'postcount' => $item->total_category
                ]);
                $arr[$item->category_id]=$item->total_category;
            }
        }
        return $arr;
    }

    public static function categoryList($postid=0,$catid=0){        
        $cat = Category::where('status',1)->orderBy('sort','asc')->get();
        $tree=array();
        foreach($cat as $item){
            $tree[]=array('id'=>$item->id,'parent_id'=>$item->parent_id,'item'=>$item);
        }
        $parentId=empty($parentId)?$catid:$parentId;
        $list=Category::categoryListItem($tree,array(),$parentId,$postid,$catid);
        return $list;
    }
    public static function categoryListItem(array $elements, array $branch, $parentId=0,$postid,$catid){ 
        // group elements by parents if it does not comes on the parameters
        $ulClass='sub-cat';
        if (empty($branch)) {
          $ulClass='catlist';
          $branch = array();
          foreach ($elements as $element) {
            $branch[$element["parent_id"]][$element["id"]] = $element;
          }
        }
        // echo the childs referenced by the parentId parameter
        if (isset($branch[$parentId])) {
          $output= '<ul class="'.$ulClass.'">';
              if($ulClass!='menu'){$output.= '<div class="sub-cat-wrap">';}
              foreach ($branch[$parentId] as $keyBranch => $itemBranch) { $actClass=$url='';
                if(!empty($catid)){
                    if(strlen(strpos($itemBranch["item"]->path_ids,$catid."/")) || $itemBranch["item"]->path_ids==$catid){}else{continue;}
                }
                $actClass.='cat-item  cat-item-'.$itemBranch["item"]->post_id.'  menu-item-'.$itemBranch["item"]->id.' '; //'.$itemBranch["item"]->classname.' 
                $child=Category::categoryListItem($elements, $branch, $itemBranch["id"],$postid,$catid);
                if($postid==$itemBranch["item"]->post_id){
                    $actClass.=' active  ';
                }

                $url=!empty($itemBranch["item"]->path)?Lib::publicUrl($itemBranch["item"]->path):Lib::publicUrl();
                $output.='<li class="'.$actClass.'"><a href="'.$url.'" >'.$itemBranch["item"]->title.' <span>('.$itemBranch["item"]->postcount.')</span></a>';
                    $output.=$child;
                $output.='</li>';
              }
              if($ulClass!='menu'){$output.= '</div>';}
          $output.= '</ul>';
          return $output;
        }
    }


    /*
    Category::path($category_list,$item->id,$item->urlkey);
    */
    public static function path($list,$id,$urlkey)
    {
        $path_ids=$path=array();
        $path_ids[]=$id;
        $path[]=$urlkey;
        if($id){
            $cat = $list[$id];
                while(!empty($cat['parent_id'])){
                    $cat = $list[$cat['parent_id']];
                    $path_ids[]=$cat['id'];
                    $path[]=$cat['urlkey'];
                }
        }
        $path_ids=array_reverse($path_ids);
        $path=array_reverse($path);
        return array('path_ids'=>implode("/",$path_ids),'path'=>implode("/",$path));
    }
    /*
    Response
        array:3 [▼
          "cat" => Illuminate\Database\Eloquent\Collection {#1592 ▶}
          "tree" => array:2 [▶]
          "list" => array:5 [▶]
        ]
    */
    public static function item()
    {
        $cat = Category::orderBy('parent_id', 'ASC')->get();
        $tree=$list=array();
        foreach($cat as $item){
            $list[$item->id]=array('id'=>$item->id,'parent_id'=>$item->parent_id,'urlkey'=>$item->urlkey,'item'=>$item);
            $tree[]=array('id'=>$item->id,'parent_id'=>$item->parent_id);
        }
        $tree=Category::buildTree($tree,array());
        return array('cat'=>$cat,'tree'=>$tree,'list'=>$list);
    }
    /*
    Response
     array:1 [▼
      4 => array:2 [▼
        "path_ids" => "0/3/4"
        "path" => "explore/blog/test4"
      ]
    */
    public static function generate()
    {
        $cat = Category::item();
        $list=array();
        if(count($cat['cat'])):
            foreach($cat['cat'] as $item){
                $list[$item->id]=$update=Category::path($cat['list'],$item->id,$item->urlkey);
                if(!empty($item->id)){
                    $updatecat = Category::find($item->id);
                    $updatecat->path=$update['path'];
                    $updatecat->path_ids=$update['path_ids'];
                    $updatecat->save();
                }
            }
        endif;
        //dd($list);
        return $list;
    }

    /*
    $tasks[] = array("id" => 1, "parent_id" => 0, "title" => 'Cat 1');
    $tasks[] = array("id" => 2, "parent_id" => 1, "title" => 'Cat 2');
    $tasks[] = array("id" => 3, "parent_id" => 1, "title" => 'Cat 3');
    $branch = array();
    $x=buildTree($tasks, array());
    */
    public static function buildTree(array $elements, array $branch, $parentId=0) {
        // group elements by parents if it does not comes on the parameters
        if (empty($branch)) {
          $branch = array();
          foreach ($elements as $element) {
            $branch[$element["parent_id"]][$element["id"]] = $element;
          }
        }

        // echo the childs referenced by the parentId parameter
        if (isset($branch[$parentId])) {
          $output = array();
          foreach ($branch[$parentId] as $keyBranch => $itemBranch) {
            $output[$itemBranch["id"]]=Category::buildTree($elements, $branch, $itemBranch["id"]); // iterate with the actual Id to check if this record have childs
          }
          return $output;
        }
    }
}



/*


//Find parent ids
$arr = array(
    array('id' => 1, 'parent' => 0),
    array('id' => 2, 'parent' => 0),
    array('id' => 3, 'parent' => 0),
    array('id' => 4, 'parent' => 1),
    array('id' => 5, 'parent' => 1),
    array('id' => 6, 'parent' => 5));

$parents = [];
function find_parents($input, $id) {
    global $parents, $arr;
    if(is_array($input)) {
        foreach($input as $k => $val) {
           
            if($val['id'] == $id  && $val['parent'] != 0) {
                array_push($parents, $val['parent']);
                find_parents($arr, $val['parent']);
            }
        }
    }
}
find_parents($arr, 4);
print_r($parents);

















Tree working wil html UL
<?php
  $tasks[] = array("id" => 1, "parent_id" => 0, "title" => 'Cat 1');
  $tasks[] = array("id" => 2, "parent_id" => 7, "title" => 'Cat 2');
  $tasks[] = array("id" => 3, "parent_id" => 0, "title" => 'Cat 3');
  $tasks[] = array("id" => 4, "parent_id" => 1, "title" => 'Cat 4');
  $tasks[] = array("id" => 5, "parent_id" => 4, "title" => 'Cat 5');
  $tasks[] = array("id" => 6, "parent_id" => 1, "title" => 'Cat 6');
  $tasks[] = array("id" => 7, "parent_id" => 6, "title" => 'Cat 7');
  $tasks[] = array("id" => 8, "parent_id" => 3, "title" => 'Cat 8');
  $tasks[] = array("id" => 9, "parent_id" => 8, "title" => 'Cat 9');
  $tasks[] = array("id" => 10, "parent_id" => 3, "title" => 'Cat 10');
  $tasks[] = array("id" => 11, "parent_id" => 5, "title" => 'Cat 11');
  $tasks[] = array("id" => 12, "parent_id" => 11, "title" => 'Cat 12');
  $tasks[] = array("id" => 13, "parent_id" => 12, "title" => 'Cat 13');
  $branch = array();

  function buildTree(array $elements, array $branch, $parentId=0) {
    // group elements by parents if it does not comes on the parameters
    if (empty($branch)) {
      $branch = array();

      foreach ($elements as $element) {
        $branch[$element["parent_id"]][$element["id"]] = $element;
      }
    }

    // echo the childs referenced by the parentId parameter
    if (isset($branch[$parentId])) {
      echo'<ul>';

      foreach ($branch[$parentId] as $keyBranch => $itemBranch) {
        echo '<li>'.$itemBranch['title'];
        buildTree($elements, $branch, $itemBranch["id"]); // iterate with the actual Id to check if this record have childs
        echo '</li>';
      }

      echo '</ul>';
    }
  }

  buildTree($tasks, array());
?>










Tree working with Array
<?php
  $tasks[] = array("id" => 1, "parent_id" => 0, "title" => 'Cat 1');
  $tasks[] = array("id" => 2, "parent_id" => 7, "title" => 'Cat 2');
  $tasks[] = array("id" => 3, "parent_id" => 0, "title" => 'Cat 3');
  $tasks[] = array("id" => 4, "parent_id" => 1, "title" => 'Cat 4');
  $tasks[] = array("id" => 5, "parent_id" => 4, "title" => 'Cat 5');
  $tasks[] = array("id" => 6, "parent_id" => 1, "title" => 'Cat 6');
  $tasks[] = array("id" => 7, "parent_id" => 6, "title" => 'Cat 7');
  $tasks[] = array("id" => 8, "parent_id" => 3, "title" => 'Cat 8');
  $tasks[] = array("id" => 9, "parent_id" => 8, "title" => 'Cat 9');
  $tasks[] = array("id" => 10, "parent_id" => 3, "title" => 'Cat 10');
  $tasks[] = array("id" => 11, "parent_id" => 5, "title" => 'Cat 11');
  $tasks[] = array("id" => 12, "parent_id" => 11, "title" => 'Cat 12');
  $tasks[] = array("id" => 13, "parent_id" => 12, "title" => 'Cat 13');
  $branch = array();

  function buildTree(array $elements, array $branch, $parentId=0) {
    // group elements by parents if it does not comes on the parameters
    if (empty($branch)) {
      $branch = array();
      foreach ($elements as $element) {
        $branch[$element["parent_id"]][$element["id"]] = $element;
      }
    }

    // echo the childs referenced by the parentId parameter
    if (isset($branch[$parentId])) {
      $output = array();
      foreach ($branch[$parentId] as $keyBranch => $itemBranch) {
        //echo '<li>'.$itemBranch['title'];
        $output[$itemBranch["id"]]=buildTree($elements, $branch, $itemBranch["id"]); // iterate with the actual Id to check if this record have childs
      }
      return $output;
    }
  }

  $x=buildTree($tasks, array());
  echo '<pre>';print_r($x);
?>










//Tree 3 level only
<?php
$arr = array(1=>array('id' => 1, 'parent' => 0),
    2=>array('id' => 2, 'parent' => 0),
    3=>array('id' => 3, 'parent' => 0),
    4=>array('id' => 4, 'parent' => 1),
    5=>array('id' => 5, 'parent' => 4),
    6=>array('id' => 6, 'parent' => 1),
    7=>array('id' => 7, 'parent' => 6),
    8=>array('id' => 8, 'parent' => 3),
    9=>array('id' => 9, 'parent' => 8),
    10=>array('id' => 10, 'parent' => 3),
    11=>array('id' => 11, 'parent' => 4)
    );


function treeMain($childs)
 {
  foreach ($childs as $key => $child) {
     
   if (!empty($child['parent'])) {
    //$childrenArray = $ch;//traverseChild($ch);
    if(isset($categoryChild[$child['parent']])){
        $categoryChild[$child['parent']]['child'][$key] = $child;
    }else{
        $categoryChild=treeSub($categoryChild,$child);
        //$categoryChild[$child['parent']]['child'][$key] = $child;
    }
   }else{
     $ctemp = array();
     $ctemp['id'] = $child['id'];
     $ctemp['parent'] = $child['parent'];
     $categoryChild[$key] = array('cat'=>$ctemp);
   }
  }
  return $categoryChild;
 }
 
 function treeSub($all,$child){
    foreach($all as $oneid=>$one){
        if(count($one['child'])){
          foreach($one['child'] as $twoid=>$two){
            if($twoid==$child['parent']){
                $all[$oneid]['child'][$twoid]['child'][$child['id']]=$child;
            }
          }
        }
    }
    return $all;
 }
 
 echo '<pre>';print_r(treeMain($arr));
?>










Testing
    public static function selectList($selval,$id){
        // $Category = Category::where('status',1)->orderBy('sort', 'ASC')->get();
        // $list = array();
        // $list[0]="None";
        // foreach($Category as $item){
        //     if($id!=$item->id){
        //         $hiphen=explode("/",$item->path);$count=(count($hiphen)==1)?0:(count($hiphen)-1);
        //         $list[$item->id]=str_repeat("  ",$count).ucwords($item->title);
        //     }
        // }

       /* $cat = Category::item();
        $list = array();
        $list[0]="None";
        //dd($cat);
        if(count($cat['tree'])){
            $data=$cat['list'];
            //$list = Category::test($list,$data,$cat,'');
            foreach($cat['tree'] as $id=>$val){
                $list[$id] = Category::test($list,$data,$cat,'');
                if(!empty($val)){
                    foreach($val as $id=>$val){
                        $list[$id] = "  ".$data[$id]['item']->title;
                        if(!empty($val)){

                        }
                    }
                }
            }
        }
        dd($list);













        public static function path($id,$urlkey)
    {
        $path_ids=$path=array();
        $path_ids[]=$id;
        $path[]=$urlkey;
        if($id){
            $cat = Category::find($id);
            while(!empty($cat->parent_id)){
                $cat = Category::find($cat->parent_id);
                $path_ids[]=$cat->parent_id;
                $path[]=$cat->urlkey;
            }
        }
        $path_ids=array_reverse($path_ids);
        $path=array_reverse($path);
        return array('path_ids'=>implode("/",$path_ids),'path'=>implode("/",$path));
    }
*/


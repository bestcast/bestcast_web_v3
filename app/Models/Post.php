<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;

class Post extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='post';

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
        'store_id',
        'urlkey',
        'title',
        'excerpt',
        'type',
        'status',
        'template',
        'published_date',
        'password',
        'thumbnail_id',
        'image_id',
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
        'store_id'          => 'integer',
        'urlkey'            => 'string',
        'title'             => 'string',
        'excerpt'           => 'string',
        'type'              => 'string',
        'status'            => 'string',
        'template'          => 'string',
        'published_date'    => 'datetime',
        'password'          => 'string',
        'thumbnail_id'      => 'integer',
        'image_id'          => 'integer',
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
        'title' => 'required|max:1000',
        'urlkey' => 'required|max:1000|unique:post,urlkey'
    ];


    public static $messages = [
        'title.required' => 'Title is required.',
        'urlkey.required' => 'URL Key is required.',
        'urlkey.unique' => 'URL Key already exists.'
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

    public static function ispublic($model){
        if(empty($model))
            return false;
        if(!empty($model->status) && ($model->status=='1' || $model->status=='3')){
            if(strtotime(date("Y-m-d H:i:s"))>=strtotime($model->published_date)){
                return true;
            }
        }
        return false;
    }

    public function status($val)
    {
        $ar=['0' => 'Draft', '1' => 'Published', '2' => 'Private', '3' => 'Protected'];
        return empty($ar[$val])?$ar[0]:$ar[$val];;
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Media','image_id','id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category','id','post_id');
    }
    public function postcategory()
    {
        return $this->hasMany('App\Models\PostCategory','post_id','id');
    }
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }
    public function postcontent()
    {
        return $this->belongsTo('App\Models\PostContent','id','post_id');
    }
    public function meta()
    {
        return $this->hasMany('App\Models\Meta','meta_id','id')->where('type','1');
    }
    public static function postcategoryprimary($list)
    {
        foreach($list as $item){
            if(!empty($item->primary)){
                return $item;
            }
        }
        return false;
    }

    public static function primarypath($model)
    {
        $postcategoryprimary=empty($model->postcategory)?$model->urlkey:$model->postcategoryprimary($model->postcategory);
        $primarypath=empty($postcategoryprimary->category)?$model->urlkey:$postcategoryprimary->category->path."/".$model->urlkey;
        return $primarypath;
    }
    public static function primarypathRoot($model)
    {
        $postcategoryprimary=empty($model->postcategory)?'':$model->postcategoryprimary($model->postcategory);
        $primarypath=empty($postcategoryprimary->category)?'':Lib::publicUrl($postcategoryprimary->category->path."/".$model->urlkey);
        return $primarypath;
    }
    public static function latestpost($pid,$attr)
    {
        foreach($attr as $k=>$v){$$k=empty($v)?'':$v;}$html='';
        $id=empty($id)?0:$id;
        $postids=empty($postids)?'':$postids;
        $type=empty($type)?'':$type;

        $currentpost=Post::find($pid);        
        $post=Post::where('status',1)->latest();
        if(!empty($postids)){ //force to get post by ids
            $postids=explode(",",$postids);
            $post=$post->whereIn('id',$postids);
        }else{
            if(!empty($currentpost->type)){ //filter by type blog, post, category etc
                $post=$post->where('type',$currentpost->type);
            }
        }
        if(!empty($currentpost->id)){ //skip current post
            $post=$post->where('id','!=',$currentpost->id);
        }
        $post=$post->limit(5)->get();
        if(!empty($post)){
            $core=CoreConfig::list();
            foreach($post as $item){
                $url=$item->urlkey;
                if($item->type=='category'){
                   $url=empty($item->category->path)?$url:$item->category->path;
                }
                $html.='<div class="latest-post">
                    <div class="thumbnail">
                        <a href="'.Lib::publicUrl($url).'">';
                            $smallimg=empty($item->thumbnail->urlkey)?'':$item->thumbnail->urlkey;
                            if(empty($smallimg)){$smallimg=empty($item->image->urlkey)?'':$item->image->urlkey;}
                            if(!empty($smallimg)){
                                $smallSize=empty($core['media_size_small'])?200:$core['media_size_small'];
                                $html.='<img src="'.Lib::publicImgSrc(Lib::getImgResizeSrc($smallimg,$smallSize)).'" alt="'.$item->title.'">';
                            }else{
                                $html.='<img src="'.Lib::publicImgSrc('img/placeholder/default/80_80.png').'" alt="'.$item->title.'">';
                            }
                        $html.='</a>
                    </div>
                    <div class="post-content">
                        <h4 class="title"><a href="'.Lib::publicUrl($url).'">'.Lib::excerpt($item->title,60).'</a></h4>
                    </div>
                </div>';
            }
        }
        return $html;
    }
}

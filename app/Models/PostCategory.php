<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use DB;
use Lib;
class PostCategory extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='post_category';

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
        'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'category_id',
        'post_id',
        'primary',
        'created_by'
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
        'category_id'         => 'integer',
        'post_id'           => 'integer',
        'primary'           => 'integer',
        'postcount'         => 'integer',
        'created_by'        => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

   /*public static function listByCat($cat)
    {
        return PostCategory::join('post', function($join) {
                $join->on('post.id', '=', 'post_category.post_id');
            })
            //->join('media', function($join) {
            //    $join->on('post.thumbnail_id', '=', 'media.id');
            //})
            //->where('media.filename','!=','')
            ->where('post_category.category_id',$cat)
            ->where('post.status','1')
            ->orderBy('post_category.created_at','desc')->get();
    }
    */

    public static function listByCat($catid,$limit=0,$attr=array())
    {
        foreach($attr as $k=>$v){
            if($k=='catid' || $k=='limit'){continue;}
            $$k=empty($v)?'':$v;
        }
        $currentpostid=empty($currentpostid)?0:$currentpostid;
        $parentcatid=empty($parentcat)?$catid:$parentcat;

        $limit=empty($limit)?Lib::limit('featured'):$limit;
        //DB::enableQueryLog();
       //$list=PostCategory::where('category_id',$catid)->latest()->get();
        $page=$pageno=empty($_GET['page'])?1:$_GET['page'];
        $page=($page-1)*$limit;
        $post=$postcount=PostCategory::where('category_id',$catid)->latest()->whereRelation('post','id','!=',$currentpostid)->whereRelation('post','status','=',1);
        $paginate=$postcount->paginate($limit)->onEachSide(1);
        $items = $post->offset($page)->limit($limit)->get();

        if(!count($items)){
            $post=$postcount=PostCategory::where('category_id',$parentcatid)->latest()->whereRelation('post','id','!=',$currentpostid)->whereRelation('post','status','=',1);
            $paginate=$postcount->paginate($limit)->onEachSide(1);
            $items = $post->offset($page)->limit($limit)->get();
        }
        //dd($items);
        /*$list = PostCategory::where('category_id',$catid)->latest()->with(['post' => function ($query) {
            $query->where('status', '1');
        }])->get();*/
       //dd(DB::getQueryLog());
        return array('items'=>$items,'paginate'=>$paginate);
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post','post_id','id');
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
}


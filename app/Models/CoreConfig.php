<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\CoreConfigField;
use Lib;

class CoreConfig extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='core_config';

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
        'post_id',
        'path',
        'value'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'user_id'       => 'integer',
        'post_id'       => 'integer',
        'path'          => 'string',
        'value'         => 'string'
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
        'path'          => 'required|max:1000',
        'value'         => 'required|max:5000'
    ];


    public static $messages = [
        'value.required' => 'Please enter a valid Value'
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

    /**
     * CoreConfig::value('');
     */
    public static function value($path){
        if(!empty($path)){
            $core = CoreConfig::where('path', '=', $path)->first();
            if(!empty($core)){
               return $core->value;
            }
        }
        return null;
    }

    /**
     * CoreConfig::list('');
     */
    public static function list(){
        $core = CoreConfig::pluck('value', 'path');
        foreach($core as $key=>$val){
            if(!empty($val) && strlen(strpos($val,"config/image"))){
                $core[$key]=Lib::imgsrc($val);
            }
        }
        return $core;
    }


    /**
     * CoreConfig::all('');
     */
    public static function item(){
        return CoreConfig::with('field')->get()->sortBy('field.sort')->sortBy('field.id');//sortByDesc
    }


    public function field()
    {
        return $this->belongsTo(CoreConfigField::class, 'path', 'path');
    }

    /**
     * CoreConfig::getAllURL();
     */
    public static function getAllURL(){

        $firstpriority=array('home','about','contact');
        $noindex=array('page-not-found');

        $post=\App\Models\Post::where('status','1')->whereIn('type',['page','blog'])->get();
        $category=\App\Models\Category::where('status','1')->get();
        $postcat=\App\Models\PostCategory::latest()->where('primary',1)->whereRelation('post','status','=',1)->get();

        $urls=array();
        foreach($post as $list){
          if(in_array($list->urlkey,$noindex)) 
            continue;
          $no=(in_array($list->urlkey,$firstpriority))?1:2;
          $urls[$list->urlkey]=array('updated_at'=>$list->updated_at,'priority'=>$no);
        }

        $cat=array();
        foreach($category as $list){
          if(in_array($list->path,$noindex)) 
            continue;
          $cat[$list->id]=$list->path;
          $urls[$list->path]=array('updated_at'=>$list->updated_at,'priority'=>2);
        }

        /* for category post primay start */
        foreach($postcat as $list){
          if(!empty($list->post) && !empty($list->post->status) && !empty($cat[$list->category_id])){
            if(in_array($list->post->urlkey,$noindex)) 
              continue;
            //if(!empty($list->primary)){
                if(isset($urls[$list->post->urlkey])){
                    unset($urls[$list->post->urlkey]);
                    $setpath=$cat[$list->category_id]."/".$list->post->urlkey;
                    $urls[$setpath]=array('updated_at'=>$list->post->updated_at,'priority'=>2);
                }
            //}
          }
        }
        /* for category post primay end */

        return $urls;
    }

    
    /**
     * CoreConfig::getCachedPagesData();
     */
    public static function getCachedPagesData(){

        $urls=CoreConfig::getAllURL();$i=0;
        if(count($urls)){
            foreach($urls as $path=>$item){
                $cache_pagrurl=Lib::publicUrl($path);
                $cfolder='cache/'.strlen($cache_pagrurl).'/'.md5($cache_pagrurl).'/';if(!file_exists('cache')){mkdir('cache');}if(!file_exists('cache/'.strlen($cache_pagrurl))){mkdir('cache/'.strlen($cache_pagrurl));}if(file_exists($cfolder)){
                    $i++;
                }
            }
        }
        return array('total'=>count($urls),'cached'=>$i);
    }

    /**
     * CoreConfig::getCachedImagesData();
     */
    public static function getCachedImagesData(){
        $core=CoreConfig::list();$iconsize=empty($core['media_size_icon'])?100:$core['media_size_icon'];
        $media = Media::all();$i=$j=0;
        foreach($media as $list){
            if(!empty($list->urlkey) && ($list->type!='application/pdf')){ $i++;
                $cache="cache/".$iconsize."/";
                $diskPath=public_path()."/";
                $resizefile=str_replace("media/","media/".$cache,$list->urlkey);
                if(empty(file_exists($diskPath.$resizefile))){
                    $j++;
                    //echo $list->id."(".$diskPath.$resizefile.")<br>";
                }
            }
        }

        return array('total'=>$i,'cached'=>($i-$j));
    }


}

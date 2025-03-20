<?php
namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use Lib;
use Auth;

class Notification extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='notification';

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
        'type',
        'title',
        'content',
        'mark_read',
        'visibility',
        'model',
        'relation_id',
        'icon', //0 -admin, 1-user
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
        'type'              => 'string',
        'title'             => 'string',
        'content'           => 'string',
        'mark_read'         => 'integer',
        'visibility'        => 'integer',
        'model'             => 'string',
        'relation_id'       => 'integer',
        'icon'              => 'integer',
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
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function visibility()
    {
        return array(0=>"Hidden",1=>"Visible");
    }

    public static function adminCount()
    {
        return Notification::where('type','admin')->where('mark_read',0)->count();
    }


    public static function addAdmin($title='',$content='',$data=array())
    {
        if(empty($title) && empty($content))
            return null;

        $model = new Notification();
        $model->user_id=Auth::user()->id;
        $model->type='admin';
        $model->title=$title;
        $model->content=$content;
        $model->mark_read=empty($data['mark_read'])?0:1;
        $model->visibility=empty($data['visibility'])?0:1;
        $model->model=empty($data['model'])?null:$data['model'];
        $model->relation_id=empty($data['relation_id'])?0:$data['relation_id'];
        $model->icon=empty($data['icon'])?0:$data['icon'];
        $model->created_by = Auth::user()->id;
        $model->save();

        return $model;
    }

    public static function getList()
    {
        $data = Notification::where('type','admin')->latest();
        $data =$data->paginate(50);
        return $data;
    }
}

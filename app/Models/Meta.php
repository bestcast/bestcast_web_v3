<?php

/*--
1=page
2=section
3=user


---*/

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;

class Meta extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='meta';
    public $timestamps = false;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',//1 - post table,2 - Movies table,3 -  User, 4 - Mobile App, 5- paymentgateway , 6- refer
        'meta_id',
        'path',
        'value'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'type'              => 'integer',
        'meta_id'           => 'integer',
        'path'              => 'string',
        'value'             => 'string'
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

    public static function mobileapp($id,$pluck=false){
       if(empty($id)){ return null;}
       $meta = Meta::where('meta_id',$id)->where('type',4);
       if(!empty($pluck)){
            $meta=$meta->pluck('value','path');
       }
       return $meta;
    }

    public static function refer($id,$pluck=false){
       if(empty($id)){ return null;}
       $meta = Meta::where('meta_id',$id)->where('type',6);
       if(!empty($pluck)){
            $meta=$meta->pluck('value','path');
       }
       return $meta;
    }

    public static function paymentgateway($id,$pluck=false){
       if(empty($id)){ return null;}
       $meta = Meta::where('meta_id',$id)->where('type',5);
       if(!empty($pluck)){
            $meta=$meta->pluck('value','path');
       }
       return $meta;
    }

    public static function page($id,$pluck=false){
       if(empty($id)){ return null;}
       $meta = Meta::where('meta_id',$id)->where('type',1);
       if(!empty($pluck)){
            $meta=$meta->pluck('value','path');
       }
       return $meta;
    }


    public static function section($id,$pluck=false){
       if(empty($id)){ return null;}
       $meta = Meta::where('meta_id',$id)->where('type',2);
       if(!empty($pluck)){
            $meta=$meta->pluck('value','path');
       }
       return $meta;
    }


    public static function user($id,$pluck=false){
       if(empty($id)){ return null;}
       $meta = Meta::where('meta_id',$id)->where('type',3);
       if(!empty($pluck)){
            $meta=$meta->pluck('value','path');
       }
       return $meta;
    }


}

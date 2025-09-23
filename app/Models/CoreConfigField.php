<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;

class CoreConfigField extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='core_config_field';

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
        'path',
        'label',
        'type',
        'placeholder',
        'value',
        'comment',
        'classname',
        'errormessage',
        'option',
        'group',
        'subgroup',
        'sort'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'path'                  => 'string',
        'label'                 => 'string',
        'type'                  => 'string',
        'placeholder'           => 'string',
        'value'                 => 'string',
        'comment'               => 'string',
        'classname'             => 'string',
        'errormessage'          => 'string',
        'option'                => 'string',
        'group'                 => 'string',
        'subgroup'              => 'string',
        'sort'                  => 'integer'
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

    public static function list(){
        $core = CoreConfigField::all();
        return $core;
    }
}

<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Skill extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_skills';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Categories relation
     */
    public $belongsToMany = [
        'courses' => [
            'Genuineq\Tms\Models\Course',
            'table' => 'genuineq_tms_courses_categories',
            'order' => 'name',
        ],
    ];

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Function that executed before the creation of an event;
     */
    public function beforeCreate()
    {
        $this->slug = str_slug($this->name, '-');
    }
}

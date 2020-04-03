<?php namespace Genuineq\Courses\Models;

use Model;

/**
 * Model
 */
class Skill extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_courses_skills';

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
            'Genuineq\Courses\Models\Course',
            'table' => 'genuineq_courses_courses_categories',
            'order' => 'name',
        ],
    ];
}

<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class LearningPlan extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_learning_plans';

    /**
     * Courses relation
     */
    public $belongsToMany = [
        'courses' => [
            'Genuineq\Tms\Models\Course',
            'table' => 'genuineq_tms_learning_plans_courses',
            'pivot' => ['school_id', 'covered_costs'],
            'timestamps' => true,
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

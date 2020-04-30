<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_teachers';

    /**
     * Address relation
     */
    public $hasOne = [
        'address' => 'Genuineq\Tms\Models\Address',
        'seniority_level' => 'Genuineq\Tms\Models\Address',
        'school_level' => 'Genuineq\Tms\Models\Address',
    ];

    /**
     * "User" relation
     */
    public $belongsTo = [
        'user' => 'Genuineq\user\Models\User',
    ];

    /**
     * Learning plans relation
     */
    public $belongsToMany = [
        'learning_plans' => [
            'Genuineq\Tms\Models\LearningPlan',
            'table' => 'genuineq_tms_teachers_learning_plans',
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

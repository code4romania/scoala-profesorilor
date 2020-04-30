<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class School extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_schools';

    /**
     * Address relation
     */
    public $hasOne = [
        'address' => 'Genuineq\Tms\Models\Address',
        'inspectorate' => 'Genuineq\Tms\Models\Inspectorate',
    ];

    /**
     * Learning plans course" relation
     */
    public $hasMany = [
        'learning_plans_course' => 'Genuineq\Tms\Models\LearningPlansCourse',
    ];

    /**
     * "User" relation
     */
    public $belongsTo = [
        'user' => 'Genuineq\user\Models\User',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class TeacherLearningPlan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_teachers_learning_plans';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

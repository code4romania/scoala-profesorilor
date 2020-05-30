<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Semester extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_semesters';

    public $hasMany = [
        'learningPlans' => 'Genuineq\Tms\Models\LearningPlan'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /***********************************************
     ******************* Functions *****************
     ***********************************************/

    /**
     * Function that extracts the latest appraisal
     *  for an associated teacher.
     */
    public static function getLatest()
    {
        return Semester::orderBy('created_at','desc')->first();
    }


    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Configure correct date;
     */
    public function beforeCreate()
    {
        $this->year = date('Y');
    }
}

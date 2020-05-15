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
    public $hasMany = [
        'courses' => 'Genuineq\Tms\Models\LearningPlansCourse'
    ];

    /**
     * Teacher and real courses relation
     */
    public $belongsToMany = [
        'teachers' => [
            'Genuineq\Tms\Models\Teacher',
            'table' => 'genuineq_tms_teachers_learning_plans',
        ],
        'realCourses' => [
            'Genuineq\Tms\Models\Course',
            'table' => 'genuineq_tms_learning_plans_courses',
            'order' => 'start_date asc',
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function getTeacherAttribute(){
        return $this->teachers->first();
    }

    public static function createNewPlan(){
        $learningPlan = new LearningPlan();

        $learningPlan->year = date('Y');
        /**
         * Semester 1: August - January
         * Semester 2: February - June
         */
        $learningPlan->semester = ((1 == date('n')) || (8 <= date('n'))) ? (1) : (2);
        $learningPlan->save();

        return $learningPlan;
    }
}

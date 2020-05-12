<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class LearningPlansCourse extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $dates = [];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'learning_plan_id',
        'school_id',
        'course_id',
        'covered_costs',
        'mandatory',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_learning_plans_courses';

    /**
     * Learning plan relation
     */
    public $belongsTo = [
        'learning_plan' => 'Genuineq\Tms\Models\LearningPlan',
        'course' => 'Genuineq\Tms\Models\Course',
        'school' => 'Genuineq\Tms\Models\School',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Function that extracts the name of the course
     *  this learning-plans-coures belongs to.
     *
     * @return String
     */
    public function getCourseNameAttribute(){
        if (null !== $this->course) {
            return $this->course->name;
        } else {
            return '';
        }
    }

    /**
     * Function that extracts the name of the school
     *  this learning-plans-coures belongs to.
     *
     * @return String
     */
    public function getSchoolNameAttribute(){
        if (null !== $this->school) {
            return $this->school->name;
        } else {
            return '';
        }
    }
}

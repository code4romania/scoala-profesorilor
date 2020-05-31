<?php namespace Genuineq\Tms\Models;

use Log;
use Model;
use Genuineq\Tms\Models\Semester;

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
     * Semester relation
     */
    public $belongsTo = [
        'semester' => ['Genuineq\Tms\Models\Semester'],
        'teacher' => ['Genuineq\Tms\Models\Teacher'],
    ];

    /**
     * Real courses relation
     */
    public $belongsToMany = [
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

    /***********************************************
     ******************* Mutators ******************
     ***********************************************/

    public function getTeacherNameAttribute(){
        return $this->teacher->name;
    }

    public function getYearAttribute(){
        return $this->semester->year;
    }

    public function getRealSemesterAttribute(){
        return $this->semester->semester;
    }

    public function getNameAttribute(){
        return $this->teacher->name . ' ' . $this->semester->year . '-' . $this->semester->semester;
    }

    public function getProposedCoursesAttribute(){
        return $this->courses->where('status', 'proposed');
    }

    public function getAcceptedCoursesAttribute(){
        return $this->courses->where('status', 'accepted');
    }

    public function getDeclinedCoursesAttribute(){
        return $this->courses->where('status', 'declined');
    }

    public function getTotalCostAttribute(){
        $cost = 0;

        foreach ($this->accepted_courses as $learningPlanCorse) {
            $cost += $learningPlanCorse->course->price;
        }

        return $cost;
    }

    public function getSchoolCoveredCostAttribute(){
        $coveredCost = 0;

        foreach ($this->accepted_courses as $learningPlanCorse) {
            $coveredCost += $learningPlanCorse->school_covered_costs;
        }

        return $coveredCost;
    }

    public function getTotalCreditsAttribute(){
        $credits = 0;

        foreach ($this->accepted_courses as $learningPlanCorse) {
            $credits += $learningPlanCorse->course->credits;
        }

        return $credits;
    }

    /***********************************************
     ******************* Functions *****************
     ***********************************************/

    /**
     * Function that checks if the learning plan
     *  has a specific course.
     */
    public function hasCourse($courseId){
        return ($this->realCourses->where('id', $courseId)->count()) ? (true) : (false);
    }

    /**
     * Function that archives the learning plan.
     */
    public function archive(){
        $this->status = 0;
        $this->save();
    }

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Create all dependencies;
     */
    public function beforeCreate()
    {
        $this->semester_id = Semester::latest()->first()->id;
    }
}

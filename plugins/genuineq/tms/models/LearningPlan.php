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

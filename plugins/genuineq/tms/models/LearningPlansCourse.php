<?php namespace Genuineq\Tms\Models;

use Log;
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
        'school_budget_id',
        'school_covered_costs',
        'teacher_budget_id',
        'teacher_covered_costs',
        'mandatory',
        'status',
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
     * Requests relation.
     */
    public $morphTo = [
        'requestable' => []
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /***********************************************
     ******************* Mutators ******************
     ***********************************************/

    /**
     * Function that extracts the name of the learning plan
     *  this learning-plans-coures belongs to.
     *
     * @return String
     */
    public function getLearningPlanNameAttribute(){
        return $this->learning_plan->name;
    }

    /**
     * Function that extracts the name of the course
     *  this learning-plans-coures belongs to.
     *
     * @return String
     */
    public function getCourseNameAttribute(){
        return $this->course->name;
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

    /***********************************************
     **************** Search/Filter ****************
     ***********************************************/

    /**
     * Function used for searching, filtering, sorting and paginating budget courses.
     *
     * @param query The query to be used for extracting budget courses
     * @param options The option for searching, filtering, sorting and paginating
     *
     * @return mixed
     */
    public function scopeTeacherFilterBudgetCourses($query, $options = [])
    {
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'ids'=> [],
            'searchInput' => '',
            'sortBy' => null,
            'sort' => 'desc'
        ], $options));

        /** Apply the id filter */
        if (count($ids)) {
            $query->whereIn('id', $ids);
        } else {
            /** Force invalid query to return no value. */
            $query->whereIn('id', [-1]);
        }

        /** Apply the search input */
        if ($searchInput) {
            $query->whereHas('course', function ($query) use ($searchInput) {
                $query->where('name', 'like', "%${searchInput}%");
            });
        }

        $query->orderBy($sortBy, $sort);

        /** Prepare resulta array */
        $results = [
            'number-courses' => $query->get()->count(),
            'number-accredited' => 0,
            'costs-supported' => 0,
            'costs-discounted' => 0,
            'total-credits' => 0,
            'total-hours' => 0,
            'courses' => null
        ];

        /** Extract result statistics */
        foreach ($query->get() as $budgetCourse) {
            $results['costs-supported'] += $budgetCourse->teacher_covered_costs;
            $results['costs-discounted'] += $budgetCourse->school_covered_costs;
            $results['total-hours'] += $budgetCourse->course->duration;

            if ($budgetCourse->course->accredited) {
                $results['number-accredited']++;
                $results['total-credits'] += $budgetCourse->course->credits;
            }

        }

        $page = ($query->paginate($perPage, $page)->lastPage() < $page) ? (1) : ($page);

        $results['courses'] = $query->paginate($perPage, $page);

        return $results;
    }

    /**
     * Function used for searching, filtering, sorting and paginating
     *  courses from the active school budget.
     *
     * @param query The query to be used for extracting budget courses
     * @param options The option for searching, filtering, sorting and paginating
     *
     * @return mixed
     */
    public function scopeSchoolFilterSchoolCourses($query, $options = [])
    {
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 1,
            'ids'=> [],
            'searchInput' => '',
            'category' => -1,
            'accreditation' => -1,
            'sortBy' => null,
            'sort' => 'desc'
        ], $options));

        /** Apply the id filter */
        if (count($ids)) {
            $query->whereIn('id', $ids);
        } else {
            /** Force invalid query to return no value. */
            $query->whereIn('id', [-1]);
        }

        /** Apply the category filter */
        if (-1 != $category) {
            $query->whereHas('course.categories', function ($query) use ($category) {
                $query->where('id', $category);
            });
        }

        /** Apply the accreditation filter */
        if (-1 != $accreditation) {
            $query->whereHas('course', function ($query) use ($accreditation) {
                $query->where('accredited', $accreditation);
            });
        }

        /** Apply the search input search */
        if ('' != $searchInput) {
            $query->whereHas('course', function ($query) use ($searchInput) {
                $query->where('name', 'like', "%${searchInput}%");
            });
        }

        $query->orderBy($sortBy, $sort);

        /** Prepare resulta array */
        $results = [
            'number-courses' => $query->get()->count(),
            'number-accredited' => 0,
            'costs-supported' => 0,
            'total-credits' => 0,
            'total-hours' => 0,
            'courses' => null
        ];

        /** Extract result statistics */
        foreach ($query->get() as $course) {
            $results['costs-supported'] += $course->school_covered_costs;
            $results['total-hours'] += $course->course->duration;

            if ($course->course->accredited) {
                $results['number-accredited']++;
                $results['total-credits'] += $course->course->credits;
            }

        }

        $page = ($query->paginate($perPage, $page)->lastPage() < $page) ? (1) : ($page);

        $results['courses'] = $query->paginate($perPage, $page);

        return $results;
    }
}

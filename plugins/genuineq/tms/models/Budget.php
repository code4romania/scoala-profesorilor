<?php namespace Genuineq\Tms\Models;

use Log;
use Lang;
use Model;
use Illuminate\Support\Collection;
use Genuineq\Tms\Models\Semester;
use Genuineq\Tms\Models\LearningPlansCourse;

/**
 * Model
 */
class Budget extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_budgets';

    /**
     * School & Teacher relation.
     */
    public $morphTo = [
        'budgetable' => []
    ];
    /**
     * Belongs to relations
     */
    public $belongsTo = [
        'semester' => ['Genuineq\Tms\Models\Semester'],
    ];

    /**
     * Teacher/School course relation
     */
    public $hasMany = [
        'teacherCourses' => [
            'Genuineq\Tms\Models\LearningPlansCourse',
            'key' => 'teacher_budget_id',

        ],

        'schoolCourses' => [
            'Genuineq\Tms\Models\LearningPlansCourse',
            'key' => 'school_budget_id'
        ],
    ];

    /**
     * Teacher/School real course relation
     */
    public $hasManyThrough = [
        'realTeacherCourses' => [
            'Genuineq\Tms\Models\Course',
            'key' => 'teacher_budget_id',
            'through' => 'Genuineq\Tms\Models\LearningPlansCourse',
        ],

        'realSchoolCourses' => [
            'Genuineq\Tms\Models\Course',
            'key' => 'school_budget_id',
            'through' => 'Genuineq\Tms\Models\LearningPlansCourse',
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

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

    /***********************************************
     **************** Search/Filter ****************
     ***********************************************/

    /**
     * Function used for searching, filtering, sorting and paginating budget courses.
     *
     * @param options The option for searching, filtering, sorting and paginating
     *
     * @return Collection of budget courses
     */
    public static function filterTeacherActiveCourses($options = [])
    {
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'searchInput' => '',
            'id' => null,
            'type' => null,
            'year' => -1,
            'semester' => -1,
            'sortBy' => null,
            'sort' => 'desc'
        ], $options));

        /******************** Filter budgets *********************/
        $budgets = Budget::where('budgetable_id', $id)->where('budgetable_type', $type);

        /** Apply the budgets year filter */
        if ($year && (-1 != $year)) {
            $budgets = $budgets->whereHas('semester', function ($query) use ($year) { $query->where('year', $year); });
        }

        /** Apply the budgets semester filter */
        if ($semester && (-1 != $semester)) {
            $budgets = $budgets->whereHas('semester', function ($query) use ($semester) { $query->where('semester', $semester); });
        }

        /******************** Merge courses **********************/
        $budgetCourses = new Collection();
        foreach ($budgets->get() as $key => $budget) {
            /** One of 'teacherCourses' or 'schoolCourses' will be empty. */
            $budgetCourses = $budgetCourses->merge($budget->teacherCourses);
            $budgetCourses = $budgetCourses->merge($budget->schoolCourses);
        }

        /******************** Filter courses *********************/
        $budgetCoursesOptions = [
            'page' => $page,
            'perPage' => $perPage,
            'ids' => $budgetCourses->pluck('id'),
            'searchInput' => $searchInput,
            'sortBy' => $sortBy,
            'sort' => $sort
        ];

        return LearningPlansCourse::teacherFilterBudgetCourses($budgetCoursesOptions);
    }

    /**
     * Function used for searching, filtering, sorting and paginating school courses.
     *
     * @param options The option for searching, filtering, sorting and paginating
     *
     * @return Collection of school courses
     */
    public static function filterSchoolCourses($options = [])
    {
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'searchInput' => '',
            'id' => null,
            'type' => null,
            'category' => -1,
            'accreditation' => -1,
            'sortBy' => null,
            'sort' => 'desc'
        ], $options));

        /******************** Filter active budget *********************/
        $budget = Budget::where('budgetable_id', $id)->where('budgetable_type', $type)->where('status', 1)->first();

        /******************** Extract courses **********************/
        $budgetCourses = $budget->schoolCourses;

        /******************** Filter courses *********************/
        $budgetCoursesOptions = [
            'page' => $page,
            'perPage' => $perPage,
            'ids' => $budgetCourses->pluck('id'),
            'searchInput' => $searchInput,
            'category' => $category,
            'accreditation' => $accreditation,
            'sortBy' => $sortBy,
            'sort' => $sort
        ];

        return LearningPlansCourse::schoolFilterSchoolCourses($budgetCoursesOptions);
    }

    /***********************************************
     ***************** Static data *****************
    ***********************************************/

    /**
     * Function that returns values used for sorting.
     */
    public static function getSortingTypes()
    {
        return [
            Lang::get('genuineq.tms::lang.budgets.frontend.desc') => 'desc',
            Lang::get('genuineq.tms::lang.budgets.frontend.asc') => 'asc',
        ];
    }

    /**
     * Function that returns years used for filtering.
     */
    public static function getFilterYears($teacherId)
    {
        foreach (Teacher::find($teacherId)->budgets as $budget) {
            $years['' . $budget->semester->year] = '' . $budget->semester->year;
        }

        $years[Lang::get('genuineq.tms::lang.budgets.frontend.all_years')] = -1;

        return array_reverse($years, true);
    }

    /**
     * Function that returns years used for filtering.
     */
    public static function getFilterSemesters()
    {
        $semesters[Lang::get('genuineq.tms::lang.budgets.frontend.all_semesters')] = -1;
        $semesters['1'] = 1;
        $semesters['2'] = 2;

        return $semesters;
    }
}

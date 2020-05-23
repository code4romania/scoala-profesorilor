<?php namespace Genuineq\Tms\Models;


use Lang;
use Model;
use DateTime;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_courses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * "Supplier" relation
     */
    public $belongsTo = [
        'supplier' => 'Genuineq\Tms\Models\Supplier',
    ];

    /**
     * Learning plans course" relation
     */
    public $hasMany = [
        'learning_plans_course' => 'Genuineq\Tms\Models\LearningPlansCourse',
    ];

    /**
     * Categories and skills relation
     */
    public $belongsToMany = [
        'categories' => [
            'Genuineq\Tms\Models\Category',
            'table' => 'genuineq_tms_courses_categories',
            'order' => 'name',
        ],
        'skills' => [
            'Genuineq\Tms\Models\Skill',
            'table' => 'genuineq_tms_courses_skills',
            'order' => 'name',
        ],
    ];

    /**
     * Function that formats the start date of the course
     *  to d.m.Y format.
     *
     * @return string The formated course start date.
     */
    public function startDate(){
        $date = new DateTime($this->start_date);
        return $date->format('d.m.Y');
    }

    /**
     * Function that formats the end date of the course
     *  to d.m.Y format.
     *
     * @return string The formated course end date.
     */
    public function endDate(){
        $date = new DateTime($this->end_date);
        return $date->format('d.m.Y');
    }

    /**
     * Function that calculates the color of a course
     *  by extractig the average color of the categories.
     */
    public function getColorAttribute()
    {
        /** Set a default color. */
        $color = "#4C025E";

        /** Extract the colors of the course categories. */
        $colors = $this->categories->pluck('color');
        if ($colors->count()) {
            $sum = 0;
            foreach ($colors as $color) {
                $sum += hexdec($color);
            }

            $color = '#' . dechex($sum / count($colors));
        }

        return $color;
    }

    /**
     * Function used for searching, filtering, sorting and paginating courses.
     *
     * @param query The query to be used for extracting courses
     * @param options The option for searching, filtering, sorting and paginating
     * @param _courses Collection of courses to be removed
     *
     * @return Collection of courses
     */
    public function scopeFilterCourses($query, $options = [], $_courses = null){
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'category' => null,
            'searchInput' => '',
            'accreditation' => -1,
            'sort' => 'name asc'

        ], $options));

        if ($searchInput) {
            /** Search the requested input */
            $query->where('name', 'like', "%${searchInput}%");
        }

        if ($category && (-1 != $category)) {
            $categories = [$category];

            foreach ($categories as $category) {
                /** Apply the category filter */
                $query->whereHas('categories', function($q) use ($category){
                    $q->where('id', '=', $category);
                });
            }
        }

        if (-1 != $accreditation) {
            /** Apply the accreditation filter */
            $query->where('accredited', '=', $accreditation);
        }

        if ($sort) {
            $sortTypes = explode(' ', $sort);

            $query->orderBy(/*field*/$sortTypes[0], /*type*/$sortTypes[1]);
        }

        /** Check if some specific courses need to be skiped */
        if ($_courses) {
            $query->whereNotIn('id', $_courses);
        }

        $page = ($query->paginate($perPage, $page)->lastPage() < $page) ? (1) : ($page);

        return $query->paginate($perPage, $page);
    }

    /**
     * Function that returns category values used for filtering.
     */
    public static function getFilterCategories(){
        /** Extract the list of categories. */
        foreach (Category::all() as $category) {
            $categories[$category->name] = $category->id;
        }

        if ($categories) {
            $categories = array_reverse($categories);
            $categories[Lang::get('genuineq.tms::lang.course.frontend.all_courses')] = -1;

            return array_reverse($categories);
        } else {
            return [];
        }
    }

    /**
     * Function that returns accreditation values used for filtering.
     */
    public static function getFilterAccreditations(){
        /** Construct the list of accreditations. */
        return [
            Lang::get('genuineq.tms::lang.course.frontend.all_accreditations') => -1,
            Lang::get('genuineq.tms::lang.course.frontend.not_accredited') => 0,
            Lang::get('genuineq.tms::lang.course.frontend.accredited') => 1
        ];
    }

    /**
     * Function that returns values used for sorting.
     */
    public static function getSortingTypes(){
        return [
            Lang::get('genuineq.tms::lang.course.frontend.name_asc') => 'name asc',
            Lang::get('genuineq.tms::lang.course.frontend.name_desc') => 'name desc',
            Lang::get('genuineq.tms::lang.course.frontend.duration_asc') => 'duration asc',
            Lang::get('genuineq.tms::lang.course.frontend.duration_desc') => 'duration desc',
            Lang::get('genuineq.tms::lang.course.frontend.start_date_asc') => 'start_date asc',
            Lang::get('genuineq.tms::lang.course.frontend.start_date_desc') => 'start_date desc',
            Lang::get('genuineq.tms::lang.course.frontend.end_date_asc') => 'end_date asc',
            Lang::get('genuineq.tms::lang.course.frontend.end_date_desc') => 'end_date desc',
            Lang::get('genuineq.tms::lang.course.frontend.credits_asc') => 'credits asc',
            Lang::get('genuineq.tms::lang.course.frontend.credits_desc') => 'credits desc',
            Lang::get('genuineq.tms::lang.course.frontend.price_asc') => 'price asc',
            Lang::get('genuineq.tms::lang.course.frontend.price_desc') => 'price desc',
        ];
    }
}

<?php namespace Genuineq\Tms\Models;

use Model;
use Genuineq\Tms\Models\Course;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Categories relation
     */
    public $belongsToMany = [
        'courses' => [
            'Genuineq\Tms\Models\Course',
            'table' => 'genuineq_tms_courses_categories',
            'order' => 'name',
        ],
    ];

    /**
     * Function used for searching, filtering, sorting and paginating the school courses.
     *
     * @param options An array of options to use.
     */
    public function filterCourses($options = []){
        /** Add the category ID */
        $options['category'] = $this->id;

        return Course::filterCourses($options);
    }
}

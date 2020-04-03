<?php namespace Genuineq\Courses\Models;

use Model;
use DateTime;

/**
 * Model
 */
class Course extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_courses_courses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Supplier relation
     */
    public $belongsTo = [
        'supplier' => 'Genuineq\Courses\Models\Supplier',
    ];

    /**
     * Categories and skills relation
     */
    public $belongsToMany = [
        'categories' => [
            'Genuineq\Courses\Models\Category',
            'table' => 'genuineq_courses_courses_categories',
            'order' => 'name',
        ],
        'skills' => [
            'Genuineq\Courses\Models\Skill',
            'table' => 'genuineq_courses_courses_skills',
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
}

<?php namespace Genuineq\Tms\Models;

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
    public $table = 'genuineq_tms_courses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Supplier relation
     */
    public $belongsTo = [
        'supplier' => 'Genuineq\Tms\Models\Supplier',
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
    public function getColor()
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
}

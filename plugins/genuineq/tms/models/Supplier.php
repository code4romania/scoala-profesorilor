<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Supplier extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'description',
        'status'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_suppliers';

    /**
     * Courses relation
     */
    public $hasMany = [
        'courses' => 'Genuineq\Tms\Models\Course',
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
     * Function that executed before the creation of an event;
     */
    public function beforeCreate()
    {
        $this->slug = str_slug($this->name, '-');
    }

    /***********************************************
     ******************* Mutators ******************
     ***********************************************/

    /**
     * Function that returns the active courses.
     */
    public function getActiveCoursesAttribute()
    {
        return $this->courses()->whereDate('start_date', '>=', date('Y-m-d'))->get();
    }
}

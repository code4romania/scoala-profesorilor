<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Semester extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_semesters';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];


    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Configure correct date;
     */
    public function beforeCreate()
    {
        $this->year = date('Y');
    }
}

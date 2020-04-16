<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Grade extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_grades';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

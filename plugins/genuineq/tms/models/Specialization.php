<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Specialization extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_specializations';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

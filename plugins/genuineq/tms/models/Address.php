<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Address extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_addresses';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

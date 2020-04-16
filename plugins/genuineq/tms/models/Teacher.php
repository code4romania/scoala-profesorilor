<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Teacher extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_teachers';

    /**
     * Address relation
     */
    public $hasOne = [
        'address' => 'Genuineq\Tms\Models\Address',
    ];

    /**
     * Profile picture relation
     */
    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

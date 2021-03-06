<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class SchoolLevel extends Model
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
        'diacritic',
        'description',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_school_levels';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

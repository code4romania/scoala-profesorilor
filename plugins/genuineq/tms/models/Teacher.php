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
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'phone',
        'birth_date',
        'description',
        'user_id',
        'address_id',
        'seniority_level_id',
        'school_level_id',
        'contract_type_id',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_teachers';

    /**
     * Address relation
     */
    public $belongsTo = [
        'address' => 'Genuineq\Tms\Models\Address',
        'seniority_level' => 'Genuineq\Tms\Models\SeniorityLevel',
        'school_level' => 'Genuineq\Tms\Models\SchoolLevel',
        'contract_type' => 'Genuineq\Tms\Models\ContractType',
    ];

    /**
     * "User" relation
     */
    public $hasOne = [
        'user' => 'Genuineq\user\Models\User',
    ];

    /**
     * Learning plans relation
     */
    public $belongsToMany = [
        'learning_plans' => [
            'Genuineq\Tms\Models\LearningPlan',
            'table' => 'genuineq_tms_teachers_learning_plans',
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Function that
     */
    public function getFormatedBirthDateAttribute(){
        return date('d-m-Y', strtotime($this->birth_date));
    }
}

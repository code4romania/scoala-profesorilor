<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class ContractType extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

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
    public $table = 'genuineq_tms_contract_types';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

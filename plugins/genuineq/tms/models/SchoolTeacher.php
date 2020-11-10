<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class SchoolTeacher extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    use \Jacob\Logbook\Traits\LogChanges;

    protected $dates = ['deleted_at'];

    /**
     * Address relation
     */
    public $belongsTo = [
        'contract_type' => 'Genuineq\Tms\Models\ContractType',
        'school_level' => 'Genuineq\Tms\Models\SchoolLevel',
        'grade' => 'Genuineq\Tms\Models\Grade',
        'specialization_1' => 'Genuineq\Tms\Models\Specialization',
        'specialization_2' => 'Genuineq\Tms\Models\Specialization',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_schools_teachers';

    /**
     * Here you can override the model name that is displayed in the log files.
     * The name is going to be translated when possible.
     */
    public $logBookModelName = 'SchoolTeacher';

    /**
     * Delete log book items after model is deleted
     *
     * If true -&gt; log items are deleted when the model is deleted
     * If false -&gt; a new log item will be created with status deleted.
     *
     * @var bool
     */
    protected $deleteLogbookAfterDelete = true;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}

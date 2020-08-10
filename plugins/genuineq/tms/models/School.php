<?php namespace Genuineq\Tms\Models;

use Model;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Appraisal;
use Genuineq\Tms\Models\Budget;

/**
 * Model
 */
class School extends Model
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
        'principal',
        'inspectorate_id',
        'address_id',
        'detailed_address',
        'description',
        'user_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_role',
        'status',
        'type',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_schools';

    /**
     * Requests relation.
     */
    public $morphMany = [
        'requests' => [
            'Genuineq\Tms\Models\LearningPlansCourse',
            'name' => 'requestable'
        ],
        'budgets' => [
            'Genuineq\Tms\Models\Budget',
            'name' => 'budgetable'
        ]
    ];

    /**
     * Address relation
     */
    public $belongsTo = [
        'address' => 'Genuineq\Tms\Models\Address',
        'inspectorate' => 'Genuineq\Tms\Models\Inspectorate',
        'user' => 'Genuineq\user\Models\User',
    ];

    /**
     * Learning plans course" relation
     */
    public $hasMany = [
        'appraisals' => [
            'Genuineq\Tms\Models\Appraisal',
            'order' => 'created_at desc'
        ],
    ];

    /**
     * Teachers relation
     */
    public $belongsToMany = [
        'teachers' => [
            'Genuineq\Tms\Models\Teacher',
            'table' => 'genuineq_tms_schools_teachers',
            'order' => 'name',
            'pivot' => [
                'contract_type_id',
                'school_level_1_id',
                'school_level_2_id',
                'school_level_3_id',
                'grade_id',
                'specialization_1_id',
                'specialization_2_id'
            ],
        ],
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /***********************************************
     ******************* Mutators ******************
     ***********************************************/

    /**
     * Function that extracts the active budget.
     */
    public function getActiveBudgetAttribute()
    {
        return $this->budgets->where('status', 1)->first();
    }

    /**
     * Function that extracts the active budget ID.
     */
    public function getActiveBudgetIdAttribute()
    {
        return $this->budgets->where('status', 1)->first()->id;
    }

    /**
     * Function that extracts the address name of the school.
     */
    public function getAddressNameAttribute(){
        $generalAddress = ($this->address) ? ($this->address->name . ', ' . $this->address->county) : ('');
        return ($this->detailed_address) ? ($this->detailed_address . ', ' . $generalAddress) : ($generalAddress);
    }

    /**
     * Function that extracts the inspectorate name of the school.
     */
    public function getInspectorateNameAttribute(){
        return ($this->inspectorate) ? ($this->inspectorate->name) : ('');
    }

    /***********************************************
     ******************* Functions *****************
     ***********************************************/

    /**
     * Function that extracts the latest appraisal
     *  for an associated teacher.
     */
    public function getActiveAppraisal($teacherId)
    {
        return $this->appraisals
                    ->where('teacher_id', $teacherId)
                    ->where('status', '<>', 'closed')->first();
                    // ->orderBy('created_at', 'desc')->first();
    }

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Create all dependencies;
     */
    public function afterCreate()
    {
        $budget = new Budget();
        $budget->budgetable = $this;
        $budget->save();
    }

    /***********************************************
     **************** Search/Filter ****************
     ***********************************************/

    /**
     * Function used for search, filter, sort
     *  and pagination of the school teachers.
     *
     * @param options An array of options to use.
     */
    public function filterTeachers($options = [])
    {
        /** Add the school ID */
        $options['school'] = $this->id;

        return Teacher::schoolTeachers($options);
    }

    /**
     * Function used for search, filter, sort
     *  and pagination of the appraisals.
     *
     * @param options An array of options to use.
     */
    public function filterAppraisals($options = [])
    {
        /** Add the school ID */
        $options['school'] = $this->id;

        return Appraisal::filterAppraisals($options);
    }

    /***********************************************
     ***************** Static data *****************
     ***********************************************/

    /**
     * Function that extracts the proposed requests for a specific learning plan.
     */
    public function getProposedLearningPlanRequests($learningPlanId)
    {
        return $this->requests->where('learning_plan_id', $learningPlanId)->where('status', 'proposed');
    }

    /**
     * Function that extracts the accepted requests for a specific learning plan.
     */
    public function getAcceptedLearningPlanRequests($learningPlanId)
    {
        return $this->requests->where('learning_plan_id', $learningPlanId)->where('status', 'accepted');
    }

    /**
     * Function that extracts the declined requests for a specific learning plan.
     */
    public function getDeclinedLearningPlanRequests($learningPlanId)
    {
        return $this->requests->where('learning_plan_id', $learningPlanId)->where('status', 'declined');
    }
}

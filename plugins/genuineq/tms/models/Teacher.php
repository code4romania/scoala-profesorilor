<?php namespace Genuineq\Tms\Models;

use Lang;
use Model;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\Budget;

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
        'address_id',
        'description',
        'user_id',
        'seniority_level_id',
        'status',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_teachers';

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
     * One-to-one relations
     */
    public $belongsTo = [
        'address' => 'Genuineq\Tms\Models\Address',
        'seniority_level' => 'Genuineq\Tms\Models\SeniorityLevel',
        'user' => 'Genuineq\user\Models\User',
    ];

    /**
     * Learning plan relation
     */
    public $hasMany = [
        'learning_plans' => [
            'Genuineq\Tms\Models\LearningPlan',
            'order' => 'created_at desc',
        ],
        'schoolConnections' => 'Genuineq\Tms\Models\SchoolTeacher'
    ];

    /**
     * Schools relation
     */
    public $belongsToMany = [
        'schools' => [
            'Genuineq\Tms\Models\School',
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
     * Function that returns the birth date in a frontend format
     */
    public function getFormatedBirthDateAttribute()
    {
        return date('d-m-Y', strtotime($this->birth_date));
    }

    /**
     * Function that extracts the email from the user
     *  which has this profile
     */
    public function getEmailAttribute()
    {
        return ($this->user) ? ($this->user->email) : ('');
    }

    /**
     * Function that extracts the address name of the teacher.
     */
    public function getAddressNameAttribute(){
        return ($this->address) ? ($this->address->name . ', ' . $this->address->county) : ('');
    }

    /**
     * Function that extracts the seniority of the teacher.
     */
    public function getSeniorityAttribute(){
        return ($this->seniority_level) ? ($this->seniority_level->name) : ('');
    }

    /**
     * Function that extracts the active learning plan.
     */
    public function getActiveLearningPlanAttribute()
    {
        return $this->learning_plans->where('status', 1)->first();
    }

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
     * Function that extracts the proposed requests.
     */
    public function getProposedRequestsAttribute()
    {
        return $this->requests
                    ->where('learning_plan_id', $this->active_learning_plan->id)
                    ->where('status', 'proposed');
    }

    /**
     * Function that extracts the accepted requests.
     */
    public function getAcceptedRequestsAttribute()
    {
        return $this->requests
                    ->where('learning_plan_id', $this->active_learning_plan->id)
                    ->where('status', 'accepted');
    }

    /**
     * Function that extracts the declined requests.
     */
    public function getDeclinedRequestsAttribute()
    {
        return $this->requests
                    ->where('learning_plan_id', $this->active_learning_plan->id)
                    ->where('status', 'declined');
    }

    /**
     * Function that merges the declined requests from all
     *  the schools in one collection.
     */
    public function getSchoolDeclinedRequestsAttribute()
    {
        $declinedRequests = new Collection();

        foreach ($this->schools as $school) {
            $declinedRequests = $declinedRequests->merge($school->getDeclinedLearningPlanRequests($this->active_learning_plan->id));
        }

        return $declinedRequests;
    }

    /**
     * Function that checks is a teacher has schools.
     */
    public function getHasSchoolsAttribute()
    {
        return (0 < $this->schools->count());
    }

    /**
     * Function that extracts the list of schools of the teacher.
     */
    protected function getSchoolsListAttribute()
    {
        /** Extact the teacher schools. */
        $value = 0;
        $schools = [];
        foreach ($this->schools as $school) {
            $schools[$school->name] = $value++;
        }

        return (count($schools)) ? (json_encode($schools)) : (null);
    }

    /***********************************************
     ********************* Scope *******************
     ***********************************************/

    /**
     * Function that extracts the ID's of all teachers
     *  that a have a specific seniority.
     */
    public function scopeOfSeniority($query, $seniorityLevelId)
    {
        return $query->where('seniority_level_id', $seniorityLevelId);
    }

    /***********************************************
     ******************* Functions *****************
     ***********************************************/

    /**
     * Function that creates a new learning plan
     *  for the teacher;
     */
    public function newLearningPlan()
    {
        $learningPlan = new LearningPlan();
        $learningPlan->teacher_id = $this->id;
        $learningPlan->save();
    }

    /**
     * Funtion that extracts the epcializations from a school connection.
     */
    public function schoolSpecializations($schoolId)
    {
        $retVal = '';
        $schoolConnection = $this->schoolConnections->where('school_id', $schoolId)->first();

        if ($schoolConnection->specialization_1) {
            $retVal .= $schoolConnection->specialization_1->name;
        }

        if ($schoolConnection->specialization_2) {
            $retVal .= ' / ' . $schoolConnection->specialization_2->name;
        }

        return $retVal;
    }

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Function that executed before the creation of an event;
     */
    public function beforeCreate()
    {
        $this->slug = str_slug($this->name, '-') . '-' . Carbon::now()->timestamp;
    }

    /**
     * Create all dependencies;
     */
    public function afterCreate()
    {
        $learningPlan = new LearningPlan();
        $learningPlan->teacher_id = $this->id;
        $learningPlan->save();

        $budget = new Budget();
        $budget->budgetable = $this;
        $budget->save();
    }

    /***********************************************
     **************** Search/Filter ****************
     ***********************************************/

    /**
     * Function used for searching, filtering, sorting and paginating teachers.
     *
     * @param options An array of options to use.
     */
    public function scopeSchoolTeachers($query, $options = [])
    {
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'searchInput' => '',
            'school' => -1,
            'seniorityLevel' => -1,
            'schoolLevel' => -1,
            'contractType' => -1,
            'sort' => 'name asc'
        ], $options));

        /** Apply the school filter */
        if ($school && (-1 != $school)) {
            $schools = [$school];

            foreach ($schools as $school) {
                $query->whereHas('schools', function($q) use ($school){
                    $q->where('id', '=', $school);
                });
            }
        }

        if ($searchInput) {
            /** Search the requested input */
            $query->where('name', 'like', "%${searchInput}%");
        }

        /** Apply the seniority level filter */
        if ($seniorityLevel && (-1 != $seniorityLevel)) {
            $seniorityLevels = [$seniorityLevel];

            foreach ($seniorityLevels as $seniorityLevel) {
                $query->whereHas('seniority_level', function($q) use ($seniorityLevel){
                    $q->where('id', '=', $seniorityLevel);
                });
            }
        }

        /** Apply the school level filter */
        if ($schoolLevel && (-1 != $schoolLevel)) {
            $schoolLevels = [$schoolLevel];

            foreach ($schoolLevels as $schoolLevel) {
                $query->whereHas('school_level', function($q) use ($schoolLevel){
                    $q->where('id', '=', $schoolLevel);
                });
            }
        }

        /** Apply the contract type filter */
        if ($contractType && (-1 != $contractType)) {
            $contractTypes = [$contractType];

            foreach ($contractTypes as $contractType) {
                $query->whereHas('contract_type', function($q) use ($contractType){
                    $q->where('id', '=', $contractType);
                });
            }
        }

        if ($sort) {
            $sortTypes = explode(' ', $sort);

            $query->orderBy(/*field*/$sortTypes[0], /*type*/$sortTypes[1]);
        }

        $page = ($query->paginate($perPage, $page)->lastPage() < $page) ? (1) : ($page);

        return $query->paginate($perPage, $page);
    }

    /***********************************************
     ***************** Static data *****************
     ***********************************************/

    /**
     * Function that returns seniority levels used for filtering.
     */
    public static function getFilterSeniorityLevel()
    {
        /** Extract the list of seniority levels. */
        foreach (SeniorityLevel::all() as $seniorityLevel) {
            $seniorityLevels[$seniorityLevel->name] = $seniorityLevel->id;
        }

        $seniorityLevels = array_reverse($seniorityLevels);
        $seniorityLevels[Lang::get('genuineq.tms::lang.teacher.frontend.all_seniority_levels')] = -1;

        return array_reverse($seniorityLevels);
    }

    /**
     * Function that returns school levels used for filtering.
     */
    public static function getFilterSchoolLevel()
    {
        /** Extract the list of school levels. */
        foreach (SchoolLevel::all() as $schoolLevel) {
            $schoolLevels[$schoolLevel->name] = $schoolLevel->id;
        }

        $schoolLevels = array_reverse($schoolLevels);
        $schoolLevels[Lang::get('genuineq.tms::lang.teacher.frontend.all_school_levels')] = -1;

        return array_reverse($schoolLevels);
    }

    /**
     * Function that returns contract types used for filtering.
     */
    public static function getFilterContractType()
    {
        /** Extract the list of contract types. */
        foreach (ContractType::all() as $contractType) {
            $contractTypes[$contractType->name] = $contractType->id;
        }

        $contractTypes = array_reverse($contractTypes);
        $contractTypes[Lang::get('genuineq.tms::lang.teacher.frontend.all_contract_types')] = -1;

        return array_reverse($contractTypes);
    }

    /**
     * Function that returns values used for sorting.
     */
    public static function getSortingTypes()
    {
        return [
            Lang::get('genuineq.tms::lang.teacher.frontend.name_asc') => 'name asc',
            Lang::get('genuineq.tms::lang.teacher.frontend.name_desc') => 'name desc',
            Lang::get('genuineq.tms::lang.teacher.frontend.birth_date_asc') => 'birth_date asc',
            Lang::get('genuineq.tms::lang.teacher.frontend.birth_date_desc') => 'birth_date desc',
        ];
    }
}

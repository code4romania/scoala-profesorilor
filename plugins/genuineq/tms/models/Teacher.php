<?php namespace Genuineq\Tms\Models;

use Lang;
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
        'schools' => [
            'Genuineq\Tms\Models\School',
            'table' => 'genuineq_tms_schools_teachers',
            'order' => 'name',
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


    /**
     * Function used for searching, filtering, sorting and paginating teachers.
     *
     * @param options An array of options to use.
     */
    public function scopeSchoolTeachers($query, $options = []){
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'searchInput' => '',
            'school' => null,
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

    /**
     * Function that returns seniority levels used for filtering.
     */
    public static function getFilterSeniorityLevel(){
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
    public static function getFilterSchoolLevel(){
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
    public static function getFilterContractType(){
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
    public static function getSortingTypes(){
        return [
            Lang::get('genuineq.tms::lang.teacher.frontend.name_asc') => 'name asc',
            Lang::get('genuineq.tms::lang.teacher.frontend.name_desc') => 'name desc',
            Lang::get('genuineq.tms::lang.teacher.frontend.birth_date_asc') => 'birth_date asc',
            Lang::get('genuineq.tms::lang.teacher.frontend.birth_date_desc') => 'birth_date desc',
        ];
    }
}

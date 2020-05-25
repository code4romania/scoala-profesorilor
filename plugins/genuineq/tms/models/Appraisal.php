<?php namespace Genuineq\Tms\Models;

use Lang;
use Model;
use Genuineq\Tms\Models\School\Teacher;
use Genuineq\Tms\Models\Semester;

/**
 * Model
 */
class Appraisal extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_appraisals';

    /**
     * Belongs to relations
     */
    public $belongsTo = [
        'school' => ['Genuineq\Tms\Models\School'],
        'semester' => ['Genuineq\Tms\Models\Semester'],
    ];

    /**
     * Skills relation
     */
    public $hasOne = [
        'firstSkill' => ['Genuineq\Tms\Models\Skill', 'key' => 'skill_1_id'],
        'secondSkill' => ['Genuineq\Tms\Models\Skill', 'key' => 'skill_2_id'],
        'thirdSkill' => ['Genuineq\Tms\Models\Skill', 'key' => 'skill_3_id']
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
     * Function that extracts the name
     *  of the school that owns the appraisal.
     */
    public function getSchoolNameAttribute()
    {
        return $this->school->name;
    }

    /**
     * Function that extracts the name
     *  of the teacher for whch the appraisal is.
     */
    public function getTeacherNameAttribute()
    {
        return Teacher::find($this->teacher_id)->name;
    }

    /**
     * Function that extracts the name
     *  of the teacher for whch the appraisal is.
     */
    public function getSemesterNameAttribute()
    {
        return $this->semester->year . '-' . $this->semester->semester;
    }

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Create all dependencies;
     */
    public function beforeCreate()
    {
        $this->semester_id = Semester::latest()->first()->id;
    }

    /***********************************************
     **************** Search/Filter ****************
     ***********************************************/

    /**
     * Function used for searching, filtering, sorting and paginating courses.
     *
     * @param query The query to be used for extracting courses
     * @param options The option for searching, filtering, sorting and paginating
     *
     * @return Collection of appraisals
     */
    public function scopeFilterAppraisals($query, $options = [])
    {
        /** Define the default options. */
        extract(array_merge([
            'page' => 1,
            'perPage' => 12,
            'searchInput' => '',
            'status' => -1,
            'year' => -1,
            'semester' => -1,
            'sort' => 'year desc'
        ], $options));

        /** Apply the status filter */
        if ($status && (-1 != $status)) {
            $query->where('status', $status);
        }

        if ($searchInput) {
            /** Search the requested input */
            $query->where('name', 'like', "%${searchInput}%");
        }

        $query->orderBy('created_at', 'desc');

        $page = ($query->paginate($perPage, $page)->lastPage() < $page) ? (1) : ($page);

        return $query->paginate($perPage, $page);
    }

    /***********************************************
     ***************** Static data *****************
     ***********************************************/

    /**
     * Function that returns statuses used for filtering.
     */
    public static function getFilterStatuses()
    {
        return [
            Lang::get('genuineq.tms::lang.appraisal.frontend.all_statuses') => -1,
            Lang::get('genuineq.tms::lang.appraisal.frontend.new') => 'new',
            Lang::get('genuineq.tms::lang.appraisal.frontend.objectives_set') => 'objectives-set',
            Lang::get('genuineq.tms::lang.appraisal.frontend.skills_set') => 'skills-set',
            Lang::get('genuineq.tms::lang.appraisal.frontend.closed') => 'closed',
        ];
    }

    /**
     * Function that returns years used for filtering.
     */
    public static function getFilterYears($teacherId)
    {
        /** Extract the list of contract types. */
        $years = [];
        foreach (Appraisal::where('teacher_id', $teacherId)->get() as $appraisal) {
            $years['' . $appraisal->year] = $appraisal->year;
        }

        $years = array_reverse($years);
        $years[Lang::get('genuineq.tms::lang.appraisal.frontend.all_years')] = -1;

        return array_reverse($years);
    }

    /**
     * Function that returns years used for filtering.
     */
    public static function getFilterSemesters($teacherId)
    {
        $appraisals = Appraisal::where('teacher_id', $teacherId)->get();
        if (1 < $appraisals->count()) {
            $semesters[Lang::get('genuineq.tms::lang.appraisal.frontend.all_years')] = -1;
            $semesters['1'] = 1;
            $semesters['2'] = 2;
        } else {
            $semesters[Lang::get('genuineq.tms::lang.appraisal.frontend.all_years')] = -1;
            $semesters['' . $appraisals[0]->semester] = $appraisals[0]->semester;
        }

        return $semesters;
    }

    /**
     * Function that returns values used for sorting.
     */
    public static function getSortingTypes()
    {
        return [
            Lang::get('genuineq.tms::lang.apprasal.frontend.status_asc') => 'status asc',
            Lang::get('genuineq.tms::lang.apprasal.frontend.status_desc') => 'status desc',
            Lang::get('genuineq.tms::lang.apprasal.frontend.year_asc') => 'year asc',
            Lang::get('genuineq.tms::lang.apprasal.frontend.year_desc') => 'year desc',
            Lang::get('genuineq.tms::lang.apprasal.frontend.semester_asc') => 'semester asc',
            Lang::get('genuineq.tms::lang.apprasal.frontend.semester_desc') => 'semester desc',
        ];
    }
}

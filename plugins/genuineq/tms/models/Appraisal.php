<?php namespace Genuineq\Tms\Models;

use Model;
use Genuineq\Tms\Models\School\Teacher;

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
    public $belongsToMany = [
        'skills' => [
            'Genuineq\Tms\Models\Skill',
            'table' => 'genuineq_tms_appraisals_skills',
            'order' => 'name',
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
     * Function that extracts the name
     *  of the school that owns the appraisal.
     */
    public function getSchoolNameAttribute(){
        return $this->school->name;
    }

    /**
     * Function that extracts the name
     *  of the teacher for whch the appraisal is.
     */
    public function getTeacherNameAttribute(){
        return Teacher::find($this->teacher_id)->name;
    }

    /**
     * Function that extracts the name
     *  of the teacher for whch the appraisal is.
     */
    public function getSemesterNameAttribute(){
        return $this->semester->year . '-' . $this->semester->semester;
    }
}

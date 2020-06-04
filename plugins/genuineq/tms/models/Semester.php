<?php namespace Genuineq\Tms\Models;

use Model;

/**
 * Model
 */
class Semester extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'semester',
        'year',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_semesters';

    public $hasMany = [
        'learningPlans' => 'Genuineq\Tms\Models\LearningPlan',
        'appraisals' => 'Genuineq\Tms\Models\Appraisal'
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
     * Function that extracts the appraisals that have
     *  the new status.
     */
    public function getNewAppraisalsAttribute()
    {
        return $this->appraisals->where('status', 'new');
    }

    /**
     * Function that extracts the appraisals that have
     *  the objectives-set status.
     */
    public function getObjectivesSetAppraisalsAttribute()
    {
        return $this->appraisals->where('status', 'objectives-set');
    }

    /**
     * Function that extracts the appraisals that have
     *  the objectives-set status.
     */
    public function getObjectivesApprovedAppraisalsAttribute()
    {
        return $this->appraisals->where('status', 'objectives-set');
    }

    /**
     * Function that extracts the appraisals that have
     *  the skills-set status.
     */
    public function getSkillsSetAppraisalsAttribute()
    {
        return $this->appraisals->where('status', 'skills-set');
    }

    /**
     * Function that extracts the active learning plans.
     */
    public function getActiveLearningPlansAttribute()
    {
        return $this->learningPlans->where('status', 1);
    }

    /***********************************************
     ******************* Functions *****************
     ***********************************************/

    /**
     * Function that extracts the latest appraisal
     *  for an associated teacher.
     */
    public static function getLatest()
    {
        return Semester::orderBy('created_at','desc')->first();
    }


    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Configure correct date;
     */
    public function beforeCreate()
    {
        $this->year = date('Y');
    }
}

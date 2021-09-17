<?php namespace Genuineq\Tms\Components;

use Log;
use Auth;
use Lang;
use Flash;
use DateTime;
use Redirect;
use Genuineq\Tms\Models\Skill;
use Genuineq\Tms\Models\Budget;
use Genuineq\Tms\Models\Semester;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\User\Helpers\AuthRedirect;
use Cms\Classes\ComponentBase;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\ContractType;
use Genuineq\Tms\Models\Grade;
use Genuineq\Tms\Models\Specialization;

/**
 * School dashboard component
 *
 * Displays the school dashboard
 */
class SchoolDashboard extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'genuineq.tms::lang.component.school-dashboard.name',
            'description' => 'genuineq.tms::lang.component.school-dashboard.description'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * Executed when this component is initialized
     */
    public function prepareVars()
    {
        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->prepareVars();
    }

    /***********************************************
     **************** AJAX handlers ****************
     ***********************************************/

    /**
     * Loads all the needed school dashboard data.
     */
    public function onSchoolDashboardView()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);

        /** Get active semester ID. */
        $activeSemesterId = Auth::getUser()->profile->active_budget->semester_id;

        $this->prepareBudgetAllocationData($activeSemesterId);
        $this->prepareFinancedTeachersData($activeSemesterId);
        $this->prepareAccreditedCoursesData($activeSemesterId);
        $this->prepareSpentMoneyData($activeSemesterId);
        $this->prepareBudgetTotalData($activeSemesterId);
        $this->prepareDistributedCostsData($activeSemesterId);
        $this->prepareTeacherSenioritiesData();
        $this->preparePaidCoursesData($activeSemesterId);
        $this->prepareSkillMatrixData();
        $this->prepareDatatableData();

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /**
     * Loads all the needed school report data.
     */
    public function onSchoolViewReports()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->prepareDatatableData();

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /**
     * Update the school active budget
     */
    public function onSchoolBudgetUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        /** Sanitize budget before update. */
        $updated_budget = ltrim(post('budget'), '=-@+');

        if (0 > $updated_budget) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-dashboard.validation.invalid_budget'));
        }

        /** Extract the teacher profile budget and update it. */
        $budget = Auth::getUser()->profile->active_budget;
        $budget->budget = $updated_budget;
        $budget->save();

        Flash::success(Lang::get('genuineq.tms::lang.component.school-dashboard.message.budget_update_successful'));
        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);

        /** Get active semester ID. */
        $activeSemesterId = Auth::getUser()->profile->active_budget->semester_id;

        $this->prepareBudgetAllocationData($activeSemesterId);
        $this->prepareFinancedTeachersData($activeSemesterId);
        $this->prepareAccreditedCoursesData($activeSemesterId);
        $this->prepareSpentMoneyData($activeSemesterId);
        $this->prepareBudgetTotalData($activeSemesterId);
        $this->prepareDistributedCostsData($activeSemesterId);
        $this->prepareTeacherSenioritiesData();
        $this->preparePaidCoursesData($activeSemesterId);
        $this->prepareSkillMatrixData();
        $this->prepareDatatableData();

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /**
     * Loads all the needed school dashboard to make a cost distribution comparison.
     */
    public function onSchoolCostDistributionCompare()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);

        $this->prepareDistributedCostsCompareData(Auth::getUser()->profile->active_budget->semester_id, Semester::where('year', post('year'))->where('semester', post('semester'))->first()->id);

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /**
     * Loads all the needed cost distribution school data.
     */
    public function onSchoolCostDistributionReset()
    {
        /** Force authentication in case user is not authenticated. */
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        $this->page['school'] = (Auth::check()) ? (Auth::getUser()->profile) : (null);
        $this->page['schoolYears'] = Budget::getSchoolFilterYears(Auth::getUser()->profile->id);
        $this->page['schoolSemesters'] = Budget::getSchoolFilterSemesters(Auth::getUser()->profile->id);

        $this->prepareDistributedCostsData(Auth::getUser()->profile->active_budget->semester_id);

        /** Send the "nonce" attribute for ajax loaded scripts. */
        $this->page['csp_nonce'] = post('nonce');
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Extract the budget allocation for a specified semester.
     */
    protected function prepareBudgetAllocationData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        /** Get the total budget. */
        $this->page['budgetTotal'] = ($budget->budget) ? ($budget->budget) : (0);
        $this->page['budgetSpent'] = 0;
        /** Get the total spent budget. */
        foreach ($budget->schoolCourses as $key => $course) {
            $this->page['budgetSpent'] += $course->school_covered_costs;
        }

        $this->page['budgetTotalLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.budget_total');
        $this->page['budgetSpentLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.budget_spent');
    }

    /**
     * Extract the finaced teachers for a specified semester.
     */
    protected function prepareFinancedTeachersData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        /** Get the total financed teachers. */
        $this->page['teachersFianced'] = $budget->schoolCourses->groupBy('teacher_id')->count();
        /** Get the total not financed teachers. */
        $this->page['teachersNotFianced'] = Auth::getUser()->profile->teachers->count() - $this->page['teachersFianced'];

        $this->page['teachersFiancedLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_financed');
        $this->page['teachersNotFiancedLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_not_financed');
    }

    /**
     * Extract the accredited courses for a specified semester.
     */
    protected function prepareAccreditedCoursesData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        $this->page['accreditedCourses'] = 0;
        $this->page['noncreditedCourses'] = 0;

        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            if ($learningPlanCourse->course->accredited) {
                $this->page['accreditedCourses'] += 1;
            } else {
                $this->page['noncreditedCourses'] += 1;
            }
        }

        $this->page['accreditedCoursesLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.accredited_courses');
        $this->page['noncreditedCoursesLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.noncredited_courses');
    }

    /**
     * Extract the spent money for a specified semester.
     */
    protected function prepareSpentMoneyData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        $this->page['schoolSpentMoney'] = 0;
        $this->page['teachersSpentMoney'] = 0;

        /** Calculate the school total costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $this->page['schoolSpentMoney'] += $learningPlanCourse->school_covered_costs;
        }

        /** Calculate the teachers total costs. */
        foreach (Auth::getUser()->profile->teachers as $key => $teacher) {
            foreach ($teacher->active_budget->teacherCourses as $key => $learningPlanCourse) {
                $this->page['teachersSpentMoney'] += $learningPlanCourse->teacher_covered_costs;
            }
        }

        $this->page['schoolSpentMoneyLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.school_spent_money');
        $this->page['teachersSpentMoneyLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_spent_money');
    }

    /**
     * Extract the budget totals for a specified semester.
     */
    protected function prepareBudgetTotalData($semesterId)
    {
        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        $this->page['schoolBudget'] = ($budget->budget) ? ($budget->budget) : (0);
        $this->page['teachersBudget'] = 0;

        /** Calculate the teachers total costs. */
        foreach (Auth::getUser()->profile->teachers as $key => $teacher) {
            $this->page['teachersBudget'] += $teacher->active_budget->budget;
        }

        $this->page['schoolBudgetLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.school_budget');
        $this->page['teachersBudgetLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_budget');
    }

    /**
     * Extract the teacher seniorities a specified semester.
     */
    protected function prepareTeacherSenioritiesData()
    {
        /** Extract the school. */
        $school = Auth::getUser()->profile;
        /** Create the seniorities array. */
        $seniorities = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];

        $senioritiesLabels = [
            1 => SeniorityLevel::find(1)->diacritic,
            2 => SeniorityLevel::find(2)->diacritic,
            3 => SeniorityLevel::find(3)->diacritic,
            4 => SeniorityLevel::find(4)->diacritic,
            5 => SeniorityLevel::find(5)->diacritic,
        ];

        /** Parse all teachers and count seniorities. */
        foreach ($school->teachers as $key => $teacher) {
            if ($teacher->seniority_level_id) {
                $seniorities[$teacher->seniority_level_id]++;
            }
        }

        $this->page['seniorities'] = $seniorities;
        $this->page['senioritiesLabels'] = $senioritiesLabels;
    }

    /**
     * Extract the total number of courses paid by teachers and the total
     *  number of courses payed by the school for a specified semester.
     */
    protected function preparePaidCoursesData($semesterId)
    {
        /** Get the school. */
        $school = Auth::getUser()->profile;
        /** Extract the school budget. */
        $schoolBudget = $school->budgets->where('semester_id', $semesterId)->first();

        /** Get the total school paid courses. */
        $this->page['schoolPaidCourses'] = $schoolBudget->schoolCourses->count();
        /** Get the total teachers paid courses. */
        $this->page['teachersPaidCourses'] = 0;

        foreach ($school->teachers as $key => $teacher) {
            /** Get the teacher budget. */
            $teacherBudget = $teacher->budgets->where('semester_id', $semesterId)->first();

            $this->page['teachersPaidCourses'] += $teacherBudget->teacherCourses->where('school_budget_id', $schoolBudget->id)->count();
            $this->page['teachersPaidCourses'] += $teacherBudget->teacherCourses()->doesntHave('school_budget')->count();
        }

        $this->page['schoolPaidCoursesLabel'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.school_paid_courses');
        $this->page['teachersPaidCoursesLael'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.teachers_paid_courses');
    }

    /**
     * Extract the budget totals for a specified semester.
     */
    protected function prepareDistributedCostsData($semesterId)
    {
        $semester = Semester::find($semesterId);
        /** Check what semester it is. */
        if (1 == $semester->semester) {
            $this->page['distributedLabels'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester');
            $distributedCosts = [ '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '1' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester_distributed_costs_label') . $semester->year];
        } else {
            $distributedCosts = [ '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0 ];
            $this->page['distributedLabels'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester');
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester_distributed_costs_label') . $semester->year];
        }

        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $semesterId)->first();

        /** Calculate the distributed costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $month = date("n", strtotime($learningPlanCourse->course->end_date));
            if (array_key_exists($month, $distributedCosts)) {
                $distributedCosts[$month] += $learningPlanCourse->school_covered_costs;
            } else {
                $distributedCosts[$month] = $learningPlanCourse->school_covered_costs;
            }
        }

        $this->page['distributedCosts'] = [0 => array_values($distributedCosts)];
        $this->page['distributedCostsColor'] = [0 => 'rgba(230, 153, 131, 0.2)'];
        $this->page['distributedCostsBorderColor'] = [0 => 'rgba(230, 153, 131, 1)'];
    }

    /**
     * Extract the budget totals for a specified semester.
     */
    protected function prepareDistributedCostsCompareData($activeSemesterId, $secondSemesterId)
    {
        /** Save the labels */
        $this->page['distributedLabels'] = Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.compare_semester');

        /****************** Extarct the first semester ****************************/
        $semester = Semester::find($activeSemesterId);
        /** Check what semester it is. */
        if (1 == $semester->semester) {
            $distributedCosts = [ '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '1' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester_distributed_costs_label') . $semester->year];
        } else {
            $distributedCosts = [ '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester_distributed_costs_label') . $semester->year];
        }

        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $activeSemesterId)->first();

        /** Calculate the distributed costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $month = date("n", strtotime($learningPlanCourse->course->end_date));
            if (array_key_exists($month, $distributedCosts)) {
                $distributedCosts[$month] += $learningPlanCourse->school_covered_costs;
            } else {
                $distributedCosts[$month] = $learningPlanCourse->school_covered_costs;
            }
        }
        $this->page['distributedCosts'] = [0 => array_values($distributedCosts)];

        /****************** Extarct the second semester ****************************/
        $semester = Semester::find($secondSemesterId);
        /** Check what semester it is. */
        if (1 == $semester->semester) {
            $distributedCosts = [ '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '1' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => $this->page['distributedCostsLabels'][0], 1 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.first_semester_distributed_costs_label') . $semester->year];
        } else {
            $distributedCosts = [ '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0 ];
            $this->page['distributedCostsLabels'] = [0 => $this->page['distributedCostsLabels'][0], 1 => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.second_semester_distributed_costs_label') . $semester->year];
        }

        /** Extract the school budget. */
        $budget = Auth::getUser()->profile->budgets->where('semester_id', $secondSemesterId)->first();

        /** Calculate the distributed costs. */
        foreach ($budget->schoolCourses as $key => $learningPlanCourse) {
            $month = date("n", strtotime($learningPlanCourse->course->end_date));
            if (array_key_exists($month, $distributedCosts)) {
                $distributedCosts[$month] += $learningPlanCourse->school_covered_costs;
            } else {
                $distributedCosts[$month] = $learningPlanCourse->school_covered_costs;
            }
        }
        $this->page['distributedCosts'] = [0 => $this->page['distributedCosts'][0], 1 => array_values($distributedCosts)];
        $this->page['distributedCostsColor'] = [0 => 'rgba(230, 153, 131, 0.2)', 1 => 'rgba(134, 100, 91, 0.2)'];
        $this->page['distributedCostsBorderColor'] = [0 => 'rgba(230, 153, 131, 1)', 1 => 'rgba(134, 100, 91, 1)'];
    }

    /**
     * Extract the data fot the skill matrix.
     */
    protected function prepareSkillMatrixData()
    {
        /** Extract the school. */
        $school = Auth::getUser()->profile;

        /** Will contain all the skills of the school from all the semesters. */
        $allSkills = [];
        /** Will contain all the skills from all the semesters grouped by semester. */
        $semesterSkills = [];
        /** Will contain all the skills from all the semesters grouped by skill. */
        $skillSemesters = [];

        /** Parse all semesters */
        foreach (Semester::all() as $key => $semester) {
            /** Populate the semesters with empty arrays */
            $semesterSkills[$semester->id] = [];

            /** Extract school appraisals. */
            $appraisals = $school->appraisals->where('semester_id', $semester->id);
            foreach ($appraisals as $key => $appraisal) {
                if ($appraisal->firstSkill) {
                    /** Check if the skill is new */
                    if (!in_array($appraisal->firstSkill->id, $allSkills)) {
                        $allSkills[] = $appraisal->firstSkill->id;
                    }
                    /** Check if the skill has beed added to the semester */
                    if (array_key_exists($appraisal->firstSkill->id, $semesterSkills[$semester->id])) {
                        $semesterSkills[$semester->id][$appraisal->firstSkill->id]['sum'] += $appraisal->grade_1;
                        $semesterSkills[$semester->id][$appraisal->firstSkill->id]['count']++;
                    } else {
                        $semesterSkills[$semester->id][$appraisal->firstSkill->id]['sum'] = $appraisal->grade_1;
                        $semesterSkills[$semester->id][$appraisal->firstSkill->id]['count'] = 1;
                    }
                }

                if ($appraisal->secondSkill) {
                    /** Check if the skill is new */
                    if (!in_array($appraisal->secondSkill->id, $allSkills)) {
                        $allSkills[] = $appraisal->secondSkill->id;
                    }
                    /** Check if the skill has beed added to the semester */
                    if (array_key_exists($appraisal->secondSkill->id, $semesterSkills[$semester->id])) {
                        $semesterSkills[$semester->id][$appraisal->secondSkill->id]['sum'] += $appraisal->grade_2;
                        $semesterSkills[$semester->id][$appraisal->secondSkill->id]['count']++;
                    } else {
                        $semesterSkills[$semester->id][$appraisal->secondSkill->id]['sum'] = $appraisal->grade_2;
                        $semesterSkills[$semester->id][$appraisal->secondSkill->id]['count'] = 1;
                    }
                }

                if ($appraisal->thirdSkill) {
                    /** Check if the skill is new */
                    if (!in_array($appraisal->thirdSkill->id, $allSkills)) {
                        $allSkills[] = $appraisal->thirdSkill->id;
                    }
                    /** Check if the skill has beed added to the semester */
                    if (array_key_exists($appraisal->thirdSkill->id, $semesterSkills[$semester->id])) {
                        $semesterSkills[$semester->id][$appraisal->thirdSkill->id]['sum'] += $appraisal->grade_3;
                        $semesterSkills[$semester->id][$appraisal->thirdSkill->id]['count']++;
                    } else {
                        $semesterSkills[$semester->id][$appraisal->thirdSkill->id]['sum'] = $appraisal->grade_3;
                        $semesterSkills[$semester->id][$appraisal->thirdSkill->id]['count'] = 1;
                    }
                }
            }
        }

        /** Calculate semester average for each identified skill. */
        foreach ($allSkills as $key => $skill) {
            foreach (Semester::all() as $key => $semester) {
                if (array_key_exists($skill, $semesterSkills[$semester->id])) {
                    $skillSemesters[$skill]['values'][$semester->id] = $semesterSkills[$semester->id][$skill]['sum'] / $semesterSkills[$semester->id][$skill]['count'];
                } else {
                    $skillSemesters[$skill]['values'][$semester->id] = 0;
                }
            }
            $skillSemesters[$skill]['label'] = Skill::find($skill)->name;
            if (5 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . (100 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . (100 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 1)';
            } elseif (10 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . (113 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . (113 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 1)';
            } elseif (20 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . (124 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . (124 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 1)';
            } elseif (50 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . (135 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . (135 + $skill) . ', ' . (100 + $skill) . ', ' . $skill . ', 1)';
            } else {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(146, 100, 91, 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(146, 100, 91, 1)';
            }
        }

        /** Extract the semester labels. */
        foreach (Semester::all() as $key => $semester) {
            $skillSemestersLabels[] = $semester->year . '-' . $semester->semester;
        }

        $this->page['skillSemesters'] = $skillSemesters;
        $this->page['skillSemestersLabels'] = $skillSemestersLabels;
    }

    /**
     * Extract the teachers data for the school datatable.
     */
    protected function prepareDatatableData()
    {
        $this->page['schoolDatatableHeaders'] = [
            /* 0  */ 'name' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_name'),
            /* 1  */ 'identifier' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_identifier'),
            /* 2  */ 'email' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_email'),
            /* 3  */ 'phone' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_phone'),
            /* 4  */ 'birth_date' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_birth_date'),
            /* 5  */ 'description' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_description'),
            /* 6  */ 'address' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_address'),
            /* 7  */ 'seniority' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_seniority'),
            /* 8  */ 'school_level_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_school_level_1'),
            /* 9  */ 'school_level_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_school_level_2'),
            /* 10 */ 'school_level_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_school_level_3'),
            /* 11 */ 'contract_type' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_contract_type'),
            /* 12 */ 'grade' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_grade'),
            /* 13 */ 'specialization_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_specialization_1'),
            /* 14 */ 'specialization_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_specialization_2'),
            /* 15 */ 'skill_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_1'),
            /* 16 */ 'evaluation_type_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_evaluation_type_1'),
            /* 17 */ 'skill_percentage_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_percentage_1'),
            /* 28 */ 'skill_grade_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grade_1'),
            /* 19 */ 'skill_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_2'),
            /* 20 */ 'evaluation_type_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_evaluation_type_2'),
            /* 21 */ 'skill_percentage_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_percentage_2'),
            /* 22 */ 'skill_grade_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grade_2'),
            /* 23 */ 'skill_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_3'),
            /* 24 */ 'evaluation_type_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_evaluation_type_3'),
            /* 25 */ 'skill_percentage_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_percentage_3'),
            /* 26 */ 'skill_grade_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grade_3'),
            /* 27 */ 'skill_grades_average' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grades_average'),
        ];

        /** Extarct the school */
        $school = Auth::getUser()->profile;

        /** Extract the data for all the school teachers */
        $schoolDatatableLines = [];
        foreach ($school->teachers as $key => $teacher) {
            /** Get the teacher appraisal. */
            $appraisal = $school->getActiveAppraisal($teacher->id);

            $schoolDatatableLines[] = [
                0  => $teacher->name,
                1  => ($teacher->user) ? ($teacher->user->identifier) : (''),
                2  => ($teacher->user) ? ($teacher->user->email) : (''),
                3  => $teacher->phone,
                4  => date("d.m.Y", strtotime($teacher->birth_date)),
                5  => $teacher->description,
                6  => ($teacher->address) ? ($teacher->address->name.", ".$teacher->address->county) : (''),
                7  => $teacher->seniority,
                8  => ($teacher->pivot->school_level_1_id) ? (SchoolLevel::find($teacher->pivot->school_level_1_id)->name) : (null),
                9  => ($teacher->pivot->school_level_2_id) ? (SchoolLevel::find($teacher->pivot->school_level_2_id)->name) : (null),
                10 => ($teacher->pivot->school_level_3_id) ? (SchoolLevel::find($teacher->pivot->school_level_3_id)->name) : (null),
                11 => ($teacher->pivot->contract_type_id) ? (ContractType::find($teacher->pivot->contract_type_id)->name) : (null),
                12 => ($teacher->pivot->grade_id) ? (Grade::find($teacher->pivot->grade_id)->name) : (null),
                13 => ($teacher->pivot->specialization_1_id) ? (Specialization::find($teacher->pivot->specialization_1_id)->name) : (null),
                14 => ($teacher->pivot->specialization_2_id) ? (Specialization::find($teacher->pivot->specialization_2_id)->name) : (null),
                15 => ($appraisal) ? (($appraisal->firstSkill) ? ($appraisal->firstSkill->name) : ('')) : (''),
                16 => ($appraisal) ? (($appraisal->firstSkill) ? ($appraisal->evaluation_types[$appraisal->evaluation_type_1]) : ('')) : (''),
                17 => ($appraisal) ? (($appraisal->firstSkill) ? ($appraisal->percentage_1."%") : ('')) : (''),
                18 => ($appraisal) ? (($appraisal->firstSkill) ? ($appraisal->grade_1) : ('')) : (''),
                19 => ($appraisal) ? (($appraisal->secondSkill) ? ($appraisal->secondSkill->name) : ('')) : (''),
                20 => ($appraisal) ? (($appraisal->secondSkill) ? ($appraisal->evaluation_types[$appraisal->evaluation_type_2]) : ('')) : (''),
                21 => ($appraisal) ? (($appraisal->secondSkill) ? ($appraisal->percentage_2."%") : ('')) : (''),
                22 => ($appraisal) ? (($appraisal->secondSkill) ? ($appraisal->grade_2) : ('')) : (''),
                23 => ($appraisal) ? (($appraisal->thirdSkill) ? ($appraisal->thirdSkill->name) : ('')) : (''),
                24 => ($appraisal) ? (($appraisal->thirdSkill) ? ($appraisal->evaluation_types[$appraisal->evaluation_type_3]) : ('')) : (''),
                25 => ($appraisal) ? (($appraisal->thirdSkill) ? ($appraisal->percentage_3."%") : ('')) : (''),
                26 => ($appraisal) ? (($appraisal->thirdSkill) ? ($appraisal->grade_3) : ('')) : (''),
                27 => ($appraisal) ? ($appraisal->average) : ('')
            ];
        }
        $this->page['schoolDatatableLines'] = $schoolDatatableLines;
    }

    /**
     * Function that returns values used for the datatable sorting.
     */
    public static function getDatatableSortingTypes()
    {
        return [
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.name_asc') => 'name asc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.name_desc') => 'name desc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.identifier_asc') => 'identifier asc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.identifier_desc') => 'identifier desc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.birthdate_asc') => 'birth_date asc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.birthdate_desc') => 'birth_date desc',

            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.email_asc') => 'email asc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.email_desc') => 'email desc',

            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.credits_asc') => 'credits asc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.credits_desc') => 'credits desc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.price_asc') => 'price asc',
            Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.price_desc') => 'price desc',
        ];
    }
}

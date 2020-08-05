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
    }

    /**
     * Update the school active budget
     */
    public function onSchoolBudgetUpdate()
    {
        if (!Auth::check()) {
            return Redirect::guest($this->pageUrl(AuthRedirect::loginRequired()));
        }

        if (0 > post('budget')) {
            throw new ApplicationException(Lang::get('genuineq.tms::lang.component.school-dashboard.validation.invalid_budget'));
        }

        /** Extract the teacher profile budget and update it. */
        $budget = Auth::getUser()->profile->active_budget;
        $budget->budget = post('budget');
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
            1 => SeniorityLevel::find(1)->name,
            2 => SeniorityLevel::find(2)->name,
            3 => SeniorityLevel::find(3)->name,
            4 => SeniorityLevel::find(4)->name,
            5 => SeniorityLevel::find(5)->name,
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
        $this->page['distributedCostsColor'] = [0 => 'rgba(75, 192, 192, 0.2)'];
        $this->page['distributedCostsBorderColor'] = [0 => 'rgba(75, 192, 192, 1)'];
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
        $this->page['distributedCostsColor'] = [0 => 'rgba(75, 192, 192, 0.2)', 1 => 'rgba(153, 102, 255, 0.2)'];
        $this->page['distributedCostsBorderColor'] = [0 => 'rgba(75, 192, 192, 1)', 1 => 'rgba(153, 102, 255, 1)'];
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
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . $skill . ', ' . (215 + $skill) . ', ' . (75 + $skill) . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . $skill . ', ' . (215 + $skill) . ', ' . (75 + $skill) . ', 1)';
            } elseif (10 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . $skill . ', ' . (175 + $skill) . ', ' . (25 + $skill) . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . $skill . ', ' . (175 + $skill) . ', ' . (25 + $skill) . ', 1)';
            } elseif (20 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . $skill . ', ' . (115 + $skill) . ', ' . (55 + $skill) . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . $skill . ', ' . (115 + $skill) . ', ' . (55 + $skill) . ', 1)';
            } elseif (50 > $skill) {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(' . $skill . ', ' . (35 + $skill) . ', ' . (85 + $skill) . ', 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(' . $skill . ', ' . (35 + $skill) . ', ' . (85 + $skill) . ', 1)';
            } else {
                $skillSemesters[$skill]['backgroundColor'] = 'rgba(153, 102, 255, 0.2)';
                $skillSemesters[$skill]['borderColor'] = 'rgba(153, 102, 255, 1)';
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
            'name' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_name'),
            'identifier' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_identifier'),
            'email' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_email'),
            'phone' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_phone'),
            'seniority' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_seniority'),
            'skill_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_1'),
            'skill_grade_1' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grade_1'),
            'skill_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_2'),
            'skill_grade_2' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grade_2'),
            'skill_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_3'),
            'skill_grade_3' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grade_3'),
            'skill_grades_average' => Lang::get('genuineq.tms::lang.component.school-dashboard.frontend.datatable_skill_grades_average'),
        ];

        /** Extarct the school */
        $school = Auth::getUser()->profile;

        /** Extract the data for all the school teachers */
        $schoolDatatableLines = [];
        foreach ($school->teachers as $key => $teacher) {
            /** Get the teacher appraisal. */
            $appraisal = $school->getActiveAppraisal($teacher->id);

            $schoolDatatableLines[] = [
                0 => $teacher->name,
                1 => ($teacher->user) ? ($teacher->user->identifier) : (''),
                2 => ($teacher->user) ? ($teacher->user->email) : (''),
                3 => $teacher->phone,
                4 => $teacher->seniority,
                5 => ($appraisal->firstSkill) ? ($appraisal->firstSkill->name) : (''),
                6 => $appraisal->grade_1,
                7 => ($appraisal->secondSkill) ? ($appraisal->secondSkill->name) : (''),
                8 => $appraisal->grade_2,
                9 => ($appraisal->thirdSkill) ? ($appraisal->thirdSkill->name) : (''),
                10 => $appraisal->grade_3,
                11 => $appraisal->average
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

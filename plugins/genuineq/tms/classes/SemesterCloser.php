<?php namespace Genuineq\Tms\Classes;

use Genuineq\Tms\Models\Semester;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\Appraisal;
use Genuineq\Tms\Models\Budget;

class SemesterCloser
{
    /**
     * Function performs all the operation for closing the first semester.
     */
    public static function closeFirstSemester()
    {
        /** Extract closing semester. */
        $closingSemester = Semester::getLatest();

        /** Create a new second semester */
        $newSemester = new Semester();
        $newSemester->semester = 2;
        $newSemester->save();

        self::archiveLearningPlans($closingSemester->id, $newSemester->id);
        self::archiveAppraisals($closingSemester->id, $newSemester->id);
        self::archiveBudgets($closingSemester->id, $newSemester->id);
    }

    /**
     * Function performs all the operation for closing the second semester.
     */
    public static function closeSecondSemester()
    {
        /** Extract closing semester. */
        $closingSemester = Semester::getLatest();

        /** Create a new first semester */
        $newSemester = new Semester();
        $newSemester->semester = 1;
        $newSemester->save();

        self::archiveLearningPlans($closingSemester->id, $newSemester->id);
        self::archiveAppraisals($closingSemester->id, $newSemester->id);
        self::archiveBudgets($closingSemester->id, $newSemester->id);
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    private static function archiveLearningPlans($closingSemesterId, $newSemesterId)
    {
        $closingLearningPlans = LearningPlan::where('semester_id', $closingSemesterId)->where('status', 1)->get();

        foreach ($closingLearningPlans as $key => $closingLearningPlan) {
            /** Close old learning plan. */
            $closingLearningPlan->status = 0;
            $closingLearningPlan->save();

            /** Create new learning plan */
            $newLearningPlan = new LearningPlan();
            $newLearningPlan->teacher_id = $closingLearningPlan->teacher_id;
            $newLearningPlan->semester_id = $newSemesterId;
            $newLearningPlan->save();
        }
    }

    private static function archiveAppraisals($closingSemesterId, $newSemesterId)
    {
        $closingAppraisals = Appraisal::where('semester_id', $closingSemesterId)->where('status', 1)->get();

        foreach ($closingAppraisals as $key => $closingAppraisal) {
            /** Close old appraisal. */
            $closingAppraisal->status = 'closed';
            $closingAppraisal->save();

            /** Create new appraisal */
            $newAppraisal = new Appraisal();
            $newAppraisal->school_id = $closingAppraisal->school_id;
            $newAppraisal->teacher_id = $closingAppraisal->teacher_id;
            $newAppraisal->semester_id = $newSemesterId;
            $newAppraisal->save();
        }
    }

    private static function archiveBudgets($closingSemesterId, $newSemesterId)
    {
        $closingBudgets = Budget::where('semester_id', $closingSemesterId)->where('status', 1)->get();

        foreach ($closingBudgets as $key => $closingBudget) {
            /** Close old budget. */
            $closingBudget->status = 0;
            $closingBudget->save();

            /** Create new budget */
            $newBudget = new Budget();
            $newBudget->semester_id = $newSemesterId;
            $newBudget->budgetable_id = $closingBudget->budgetable_id;
            $newBudget->budgetable_type = $closingBudget->budgetable_type;
            $newBudget->save();
        }
    }
}

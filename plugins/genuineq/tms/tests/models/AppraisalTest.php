<?php namespace Genuineq\Tms\Tests\Models;

use Log;
use PluginTestCase;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Skill;
use Genuineq\Tms\Models\Appraisal;
use System\Classes\PluginManager;

class AppraisalTest extends PluginTestCase
{
    public function test__createAppraisal()
    {
        /** Create a school */
        $school_1 = $this->helper__createSchool(1);
        /** Create a teacher */
        $teacher_2 = $this->helper__createTeacher(2);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_1->id, $teacher_2->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_1->id, $appraisal->school_id);
        $this->assertEquals($teacher_2->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);
    }

    public function test__updateAppraisal()
    {
        /** Create a school */
        $school_3 = $this->helper__createSchool(3);
        /** Create a teacher */
        $teacher_4 = $this->helper__createTeacher(4);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_3->id, $teacher_4->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_3->id, $appraisal->school_id);
        $this->assertEquals($teacher_4->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);

        /** Create a school */
        $school_5 = $this->helper__createSchool(5);
        /** Create a teacher */
        $teacher_6 = $this->helper__createTeacher(6);
        /** Create a skill 1 */
        $skill_4 = $this->helper__createSkill(4);
        /** Create a skill 2 */
        $skill_5 = $this->helper__createSkill(5);
        /** Create a skill 3 */
        $skill_6 = $this->helper__createSkill(6);

        /** Update appraisal */
        $appraisal->school_id = $school_5->id;
        $appraisal->teacher_id = $teacher_6->id;
        $appraisal->objectives = 'new-test-appraisal-objectives';
        $appraisal->skill_1_id = $skill_4->id;
        $appraisal->grade_1 = 4;
        $appraisal->skill_2_id = $skill_5->id;
        $appraisal->grade_2 = 5;
        $appraisal->skill_3_id = $skill_6->id;
        $appraisal->grade_3 = 6;
        $appraisal->notes_objectives_set = 'new-test-appraisal-notes-objectives-set';
        $appraisal->notes_objectives_approved = 'new-test-appraisal-notes-objectives-approved';
        $appraisal->notes_skills_set = 'new-test-appraisal-notes-skills-set';
        $appraisal->notes_evaluation_opened = 'new-test-appraisal-notes-evaluation-opened';
        $appraisal->status = 'objectives-set';
        $appraisal->save();

        /* Validate created appraisal */
        $this->assertEquals($school_5->id, $appraisal->school_id);
        $this->assertEquals($teacher_6->id, $appraisal->teacher_id);
        $this->assertEquals('new-test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_4->id, $appraisal->skill_1_id);
        $this->assertEquals(4, $appraisal->grade_1);
        $this->assertEquals($skill_5->id, $appraisal->skill_2_id);
        $this->assertEquals(5, $appraisal->grade_2);
        $this->assertEquals($skill_6->id, $appraisal->skill_3_id);
        $this->assertEquals(6, $appraisal->grade_3);
        $this->assertEquals('new-test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('new-test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('new-test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('new-test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('objectives-set', $appraisal->status);
    }

    public function test__deleteAppraisal()
    {
        /** Create a school */
        $school_7 = $this->helper__createSchool(7);
        /** Create a teacher */
        $teacher_8 = $this->helper__createTeacher(8);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_7->id, $teacher_8->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_7->id, $appraisal->school_id);
        $this->assertEquals($teacher_8->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);

        /** Save the ID */
        $appraisalId = $appraisal->id;

        /** Delete appraisal. */
        $appraisal->delete();

        /** Search for deleted appraisal */
        $_appraisal = Appraisal::where('id', $appraisalId)->first();

        $this->assertEquals(null, $_appraisal);
    }


    public function test__schoolName()
    {
        /** Create a school */
        $school_9 = $this->helper__createSchool(9);
        /** Create a teacher */
        $teacher_10 = $this->helper__createTeacher(10);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_9->id, $teacher_10->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_9->id, $appraisal->school_id);
        $this->assertEquals($teacher_10->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);

        /** Validate school name */
        $this->assertEquals($school_1->name, $appraisal->school_name);
    }


    public function test__teacherName()
    {
        /** Create a school */
        $school_11 = $this->helper__createSchool(11);
        /** Create a teacher */
        $teacher_12 = $this->helper__createTeacher(12);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_11->id, $teacher_12->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_11->id, $appraisal->school_id);
        $this->assertEquals($teacher_12->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);

        /** Validate teacher name */
        $this->assertEquals($teacher_2->name, $appraisal->teacher_name);
    }


    public function test__skillName()
    {
        /** Create a school */
        $school_13 = $this->helper__createSchool(13);
        /** Create a teacher */
        $teacher_14 = $this->helper__createTeacher(14);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_13->id, $teacher_14->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_13->id, $appraisal->school_id);
        $this->assertEquals($teacher_14->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);

        /** Validate skill name */
        $this->assertEquals($skill_1->name, $appraisal->first_skill_name);
    }


    public function test__skillDescription()
    {
        /** Create a school */
        $school_15 = $this->helper__createSchool(15);
        /** Create a teacher */
        $teacher_16 = $this->helper__createTeacher(16);
        /** Create a skill 1 */
        $skill_1 = $this->helper__createSkill(1);
        /** Create a skill 2 */
        $skill_2 = $this->helper__createSkill(2);
        /** Create a skill 3 */
        $skill_3 = $this->helper__createSkill(3);

        /** Create new appraisal */
        $appraisal = $this->helper__createAppraisal($school_15->id, $teacher_16->id, $skill_1->id, $skill_2->id, $skill_3->id);

        /* Validate created appraisal */
        $this->assertEquals($school_15->id, $appraisal->school_id);
        $this->assertEquals($teacher_16->id, $appraisal->teacher_id);
        $this->assertEquals('test-appraisal-objectives', $appraisal->objectives);
        $this->assertEquals($skill_1->id, $appraisal->skill_1_id);
        $this->assertEquals(1, $appraisal->grade_1);
        $this->assertEquals($skill_2->id, $appraisal->skill_2_id);
        $this->assertEquals(2, $appraisal->grade_2);
        $this->assertEquals($skill_3->id, $appraisal->skill_3_id);
        $this->assertEquals(3, $appraisal->grade_3);
        $this->assertEquals('test-appraisal-notes-objectives-set', $appraisal->notes_objectives_set);
        $this->assertEquals('test-appraisal-notes-objectives-approved', $appraisal->notes_objectives_approved);
        $this->assertEquals('test-appraisal-notes-skills-set', $appraisal->notes_skills_set);
        $this->assertEquals('test-appraisal-notes-evaluation-opened', $appraisal->notes_evaluation_opened);
        $this->assertEquals('new', $appraisal->status);

        /** Validate skill name */
        $this->assertEquals($skill_1->description, $appraisal->first_skill_description);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an appraisal object and return's it
     */
    protected function helper__createAppraisal($index, $schoolId, $teacherId, $firstSkillId, $secondSkillId, $thirdSkillId)
    {
        /** Create new learningPlan */
        $appraisal = Appraisal::create([
            'school_id' => $schoolId,
            'teacher_id' => $teacherId,
            'objectives' => 'test-appraisal-objectives-' . $index,
            'skill_1_id' => $firstSkillId,
            'grade_1' => 1,
            'skill_2_id' => $secondSkillId,
            'grade_2' => 2,
            'skill_3_id' => $thirdSkillId,
            'grade_3' => 3,
            'notes_objectives_set' => 'test-appraisal-notes-objectives-set-' . $index,
            'notes_objectives_approved' => 'test-appraisal-notes-objectives-approved-' . $index,
            'notes_skills_set' => 'test-appraisal-notes-skills-set-' . $index,
            'notes_evaluation_opened' => 'test-appraisal-notes-evaluation-opened-' . $index,
            'status' => 'new',
        ]);

        return $appraisal;
    }


    /**
     * Created an school object and return's it
     */
    protected function helper__createSchool($indexId)
    {
        /** Create new school */
        $school = School::create([
            'name' => 'test-school-name-' . $indexId,
            'slug' => 'test-school-slug-' . $indexId,
            'phone' => '072222222' . $indexId,
            'principal' => 'test-school-principal-' . $indexId,
            'inspectorate_id' => $indexId,
            'address_id' => $indexId,
            'description' => 'test-school-description-' . $indexId,
            'user_id' => $indexId,
            'contact_name' => 'test-school-contact-name-' . $indexId,
            'contact_email' => 'contact@email.com-' . $indexId,
            'contact_phone' => '073333333' . $indexId,
            'contact_role' => 'test-school-role-' . $indexId,
            'status'=> 1,
        ]);

        return $school;
    }


    /**
     * Created an teacher object and return's it
     */
    protected function helper__createTeacher($indexId)
    {
        /** Create new teacher */
        $teacher = Teacher::create([
            'name' => 'test-teacher-name-' . $indexId,
            'slug' => 'test-teacher-slug-' . $indexId,
            'phone' => '072222222' . $indexId,
            'birth_date' => '1987-10-2' . $indexId,
            'address_id' => $indexId,
            'description' => 'test-teacher-description-' . $indexId,
            'user_id' => $indexId,
            'seniority_level_id' => $indexId,
            'status' => 1,
        ]);

        return $teacher;
    }


    /**
     * Created an skill object and return's it
     */
    protected function helper__createSkill($indexId)
    {
        /** Create new skill */
        $skill = Skill::create([
            'name' => 'test-skill-name-' . $indexId,
            'slug' => 'test-skill-slug-' . $indexId,
            'description' => 'test-skill-description-' . $indexId,
        ]);

        return $skill;
    }
}

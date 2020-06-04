<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Genuineq\Tms\Models\Skill;
use System\Classes\PluginManager;

class SkillTest extends PluginTestCase
{
    public function test__createSkill()
    {
        /** Create new skill */
        $skill = $this->helper__createSkill();

        /* Validate created skill */
        $this->assertEquals('test-skill-name', $skill->name);
        $this->assertEquals('test-skill-slug', $skill->slug);
        $this->assertEquals('test-skill-description', $skill->description);
    }

    public function test__updateSkill()
    {
        /** Create new skill */
        $skill = $this->helper__createSkill();

        /* Validate created skill */
        $this->assertEquals('test-skill-name', $skill->name);
        $this->assertEquals('test-skill-slug', $skill->slug);
        $this->assertEquals('test-skill-description', $skill->description);

        /** Update skill */
        $skill->name = 'new-test-skill-name';
        $skill->slug = 'new-test-skill-slug';
        $skill->description = 'new-test-skill-description';
        $skill->save();

        /** Check skill new values */
        $this->assertEquals('new-test-skill-name', $skill->name);
        $this->assertEquals('new-test-skill-slug', $skill->slug);
        $this->assertEquals('new-test-skill-description', $skill->description);
    }

    public function test__deleteSkill()
    {
        /** Create new skill */
        $skill = $this->helper__createSkill();

        /* Validate created skill */
        $this->assertEquals('test-skill-name', $skill->name);
        $this->assertEquals('test-skill-slug', $skill->slug);
        $this->assertEquals('test-skill-description', $skill->description);

        /** Save the ID */
        $skillId = $skill->id;

        /** Delete skill. */
        $skill->delete();

        /** Search for deleted skill */
        $_skill = Skill::where('id', $skillId)->first();

        $this->assertEquals(null, $_skill);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an skill object and return's it
     */
    protected function helper__createSkill()
    {
        /** Create new skill */
        $skill = Skill::create([
            'name' => 'test-skill-name',
            'slug' => 'test-skill-slug',
            'description' => 'test-skill-description',
        ]);

        return $skill;
    }
}

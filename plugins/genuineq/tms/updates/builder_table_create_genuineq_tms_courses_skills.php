<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqCoursesTmsSkills extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_courses_skills', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('course_id');
            $table->integer('skill_id');
            $table->primary(['course_id','skill_id'], 'genuineq_tms_courses_skills_course_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_courses_skills');
    }
}

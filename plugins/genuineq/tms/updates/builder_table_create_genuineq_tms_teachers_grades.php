<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsCoursesGrades extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_teachers_grades', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('teacher_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->primary(['teacher_id','grade_id'], 'genuineq_tms_courses_grades_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_teachers_grades');
    }
}

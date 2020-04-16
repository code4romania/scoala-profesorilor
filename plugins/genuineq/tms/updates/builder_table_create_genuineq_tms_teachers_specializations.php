<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsCoursesSpecializations extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_teachers_specializations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('teacher_id')->unsigned();
            $table->integer('specialization_id')->unsigned();
            $table->primary(['teacher_id','specialization_id'], 'genuineq_tms_courses_specializations_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_teachers_specializations');
    }
}

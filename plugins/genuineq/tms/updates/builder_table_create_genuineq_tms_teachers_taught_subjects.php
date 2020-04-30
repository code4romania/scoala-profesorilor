<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsTeachersTaughtSubjects extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_teachers_taught_subjects', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('teacher_id');
            $table->integer('taught_subject_id');
            $table->primary(['teacher_id','taught_subject_id'], 'genuineq_tms_teachers_taught_subjects_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_teachers_taught_subjects');
    }
}

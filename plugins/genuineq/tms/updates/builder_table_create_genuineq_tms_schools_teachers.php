<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsSchoolsTeachers extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_schools_teachers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('school_id');
            $table->integer('teacher_id');
            $table->primary(['school_id','teacher_id'], 'genuineq_tms_schools_teachers_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_schools_teachers');
    }
}

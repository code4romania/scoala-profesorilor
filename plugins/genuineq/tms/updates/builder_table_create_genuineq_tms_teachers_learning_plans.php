<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsTeachersLearningPlans extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_teachers_learning_plans', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('teacher_id');
            $table->integer('learning_plan_id');
            $table->primary(['teacher_id','learning_plan_id'], 'genuineq_tms_teachers_learning_plans_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_teachers_learning_plans');
    }
}

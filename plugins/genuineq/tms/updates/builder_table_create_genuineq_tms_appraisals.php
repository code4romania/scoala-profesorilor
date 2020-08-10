<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsAppraisals extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_appraisals', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('school_id')->unsigned();
            $table->integer('teacher_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->text('objectives')->nullable();
            $table->integer('skill_1_id')->unsigned()->nullable();
            $table->integer('grade_1')->unsigned()->nullable();
            $table->integer('percentage_1')->unsigned()->default(34);
            $table->enum('evaluation_type_1', ['grade', 'percentage'])->default('grade');
            $table->integer('skill_2_id')->unsigned()->nullable();
            $table->integer('grade_2')->unsigned()->nullable();
            $table->integer('percentage_2')->unsigned()->default(33);
            $table->enum('evaluation_type_2', ['grade', 'percentage'])->default('grade');
            $table->integer('skill_3_id')->unsigned()->nullable();
            $table->integer('grade_3')->unsigned()->nullable();
            $table->integer('percentage_3')->unsigned()->default(33);
            $table->enum('evaluation_type_3', ['grade', 'percentage'])->default('grade');
            $table->text('notes_objectives_set')->nullable();
            $table->text('notes_objectives_approved')->nullable();
            $table->text('notes_skills_set')->nullable();
            $table->text('notes_evaluation_opened')->nullable();
            $table->enum('status', ['new', 'objectives-set', 'objectives-approved', 'skills-set', 'evaluation-opened', 'closed',])->default('new');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_appraisals');
    }
}

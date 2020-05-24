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
            $table->enum('status', ['new', 'objectives-set', 'skills-set', 'closed',])->default('new');
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
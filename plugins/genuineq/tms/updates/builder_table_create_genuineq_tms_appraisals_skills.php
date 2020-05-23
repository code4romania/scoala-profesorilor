<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsAppraisalsSkills extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_appraisals_skills', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('appraisal_id')->unsigned();
            $table->integer('skill_id')->unsigned();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_appraisals_skills');
    }
}
<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsSkills extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_skills', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->comment = "The name of the skill.";
            $table->string('slug')->comment = "The slug of the skill.";
            $table->text('description')->nullable()->comment = "The description of the skill.";
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_skills');
    }
}

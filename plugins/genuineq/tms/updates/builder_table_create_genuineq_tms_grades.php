<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsGrades extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_grades', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50)->comment = "The name of the grade.";
            $table->string('diacritic', 50)->comment = "The name of the grade with discritics.";
            $table->text('description')->comment = "The description of the grade.";
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_grades');
    }
}

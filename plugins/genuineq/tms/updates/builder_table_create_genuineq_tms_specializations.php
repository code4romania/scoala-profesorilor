<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsSpecializations extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_specializations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50)->comment = "The name of the specialization.";
            $table->string('diacritic', 50)->comment = "The name of the specialization with discritics.";
            $table->text('description')->comment = "The description of the specialization.";
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_specializations');
    }
}

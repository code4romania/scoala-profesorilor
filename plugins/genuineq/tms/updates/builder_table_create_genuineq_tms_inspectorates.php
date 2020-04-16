<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsInspectorates extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_inspectorates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 100)->comment = "The name of the inspectorate.";
            $table->string('slug')->comment = "The slug of the inspectorate.";
            $table->text('description')->comment = "The description of the inspectorate.";
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_inspectorates');
    }
}

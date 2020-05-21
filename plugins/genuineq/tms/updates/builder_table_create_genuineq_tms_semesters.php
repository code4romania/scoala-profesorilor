<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsSemesters extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_semesters', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->smallInteger('semester')->unsigned();
            $table->integer('year')->unsigned();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('genuineq_tms_semesters');
    }
}

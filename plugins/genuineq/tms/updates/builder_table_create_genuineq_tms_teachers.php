<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsTeachers extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_teachers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50)->comment = "The name of the teacher.";
            $table->string('slug')->comment = "The slug of the teacher.";
            $table->string('avatar')->nullable()->comment = "The avatar of the teacher.";
            $table->string('phone', 15)->comment = "The phone number of the teacher.";
            $table->dateTime('birth_date')->comment = "The birth date of the teacher.";
            $table->boolean('status')->default(1)->comment = "The status of the teacher.";
            $table->integer('address_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_teachers');
    }
}

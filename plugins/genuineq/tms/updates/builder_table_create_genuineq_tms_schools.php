<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsSchools extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_schools', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50)->nullable()->comment = "The name of the school.";
            $table->string('slug')->unique()->nullable()->comment = "The slug of the school.";
            $table->string('phone', 15)->nullable()->comment = "The phone number of the school.";
            $table->string('principal', 50)->nullable()->comment = "The name of the principal of the school.";
            $table->integer('inspectorate_id')->nullable()->unsigned();
            $table->integer('address_id')->nullable()->unsigned();
            $table->text('detailed_address')->nullable()->comment = "The detailed address of the school.";
            $table->text('description')->nullable()->comment = "The description of the school.";
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('contact_name', 50)->nullable()->comment = "The name of the contact person of the school.";
            $table->string('contact_email', 50)->nullable()->comment = "The email of the contact person of the school.";
            $table->string('contact_phone', 15)->nullable()->comment = "The phone number of the contact person of the school.";
            $table->string('contact_role', 50)->nullable()->comment = "The role of the contact person of the school.";
            $table->boolean('status')->default(1)->comment = "The status of the school.";
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_schools');
    }
}

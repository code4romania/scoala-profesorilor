<?php namespace Genuineq\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateMailBlockersTable extends Migration
{

    public function up()
    {
        Schema::create('genuineq_user_mail_blockers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email')->index()->nullable();
            $table->string('template')->index()->nullable();
            $table->integer('user_id')->unsigned()->nullable()->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_user_mail_blockers');
    }

}

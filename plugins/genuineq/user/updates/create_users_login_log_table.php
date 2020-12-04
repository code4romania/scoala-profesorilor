<?php namespace Genuineq\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateUsersLoginLogTable extends Migration
{

    public function up()
    {
        Schema::create('users_login_log', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->enum('type', ["Successful login", "Successful logout", "Unsuccessful login"]);
            $table->string('name')->nullable();
            $table->string('email');
            $table->string('ip_address');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users_login_log');
    }

}

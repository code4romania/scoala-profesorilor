<?php namespace Genuineq\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateUserGroupsTable extends Migration
{

    public function up()
    {
        Schema::create('user_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('users_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('user_group_id')->unsigned();
            $table->primary(['user_id', 'user_group_id'], 'user_group');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_groups');
        Schema::dropIfExists('users_groups');
    }

}

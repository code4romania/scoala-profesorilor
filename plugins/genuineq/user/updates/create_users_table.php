<?php namespace Genuineq\User\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Genuineq\User\Helpers\PluginConfig;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->unique();
            $table->string('username')->nullable()->unique();
            $table->string('identifier')->unique();
            $table->enum('type', PluginConfig::getUserTypes());
            $table->string('password');
            $table->string('activation_code')->nullable()->index();
            $table->string('persist_code')->nullable();
            $table->string('reset_password_code')->nullable()->index();
            $table->text('permissions')->nullable();
            $table->boolean('is_activated')->default(0);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->boolean('is_guest')->default(false);
            $table->boolean('is_superuser')->default(false);
            $table->string('created_ip_address')->nullable();
            $table->string('last_ip_address')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }

}

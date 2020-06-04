<?php

namespace Genuineq\UserAccessLog\Updates;

use Schema;
use Illuminate\Support\Facades\DB;
use October\Rain\Database\Updates\Migration;

class CreateAccessLogTable extends Migration
{

	public function up()
	{
		Schema::create('user_access_log', function($table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');

			$table->integer('user_id')->unsigned()->nullable();
			$table->string('ip_address')->nullable();

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('user_access_log');
	}

}

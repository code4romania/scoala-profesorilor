<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsAddresses extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_addresses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50)->nullable();
            $table->string('diacritic', 50)->nullable();
            $table->string('county', 50)->nullable();
            $table->string('auto', 2)->nullable();
            $table->string('zip', 10)->nullable();
            $table->integer('population')->nullable()->unsigned();
            $table->double('latitude', 11, 7)->nullable();
            $table->double('longitude', 11, 7)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('genuineq_tms_addresses');
    }
}
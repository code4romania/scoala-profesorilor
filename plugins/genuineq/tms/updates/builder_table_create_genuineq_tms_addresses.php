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
            $table->string('name', 50)->nullable()->comment = "The name of the address.";
            $table->string('diacritic', 50)->nullable()->comment = "The name woth diacritics of the address.";;
            $table->string('county', 50)->nullable()->comment = "The county of the address.";;
            $table->string('auto', 2)->nullable()->comment = "The auro code of the address.";;
            $table->string('zip', 10)->nullable()->comment = "The zip code of the address.";;
            $table->integer('population')->nullable()->unsigned()->comment = "The population of the address.";;
            $table->double('latitude', 11, 7)->nullable()->comment = "The latitude of the address.";;
            $table->double('longitude', 11, 7)->nullable()->comment = "The longitude of the address.";;
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_addresses');
    }
}

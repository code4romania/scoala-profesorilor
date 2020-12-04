<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsCategories extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 75)->comment = "The name of the category.";
            $table->string('slug', 75)->unique()->comment = "The slug of the category.";
            $table->string('color', 10)->comment = "The color of the category.";
            $table->string('icon')->nullable()->comment = "The icon of the category.";
            $table->text('description')->nullable()->comment = "The description of the category.";
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_categories');
    }
}

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
            $table->string('name')->comment = "The name of the category.";
            $table->string('slug')->comment = "The slug of the category.";
            $table->string('color')->comment = "The color of the category.";
            $table->string('icon')->nullable()->comment = "The icon of the category.";
            $table->text('description')->nullable()->comment = "The description of the category.";
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_categories');
    }
}

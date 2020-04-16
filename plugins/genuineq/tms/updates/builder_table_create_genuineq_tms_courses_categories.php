<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqTmsCoursesCategories extends Migration
{
    public function up()
    {
        Schema::create('genuineq_tms_courses_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('course_id');
            $table->integer('category_id');
            $table->primary(['course_id','category_id'], 'genuineq_tms_courses_categories_course_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_tms_courses_categories');
    }
}

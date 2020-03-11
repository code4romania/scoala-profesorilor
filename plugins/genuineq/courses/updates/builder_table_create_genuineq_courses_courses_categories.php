<?php namespace Genuineq\Courses\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateGenuineqCoursesCoursesCategories extends Migration
{
    public function up()
    {
        Schema::create('genuineq_courses_courses_categories', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('course_id');
            $table->integer('category_id');
            $table->primary(['course_id','category_id'], 'genuineq_courses_courses_categories_course_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('genuineq_courses_courses_categories');
    }
}

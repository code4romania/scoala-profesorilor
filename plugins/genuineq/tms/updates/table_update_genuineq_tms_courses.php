<?php namespace Genuineq\Tms\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableUpdateGenuineqTmsCourses extends Migration
{
    public function up()
    {
        Schema::table('genuineq_tms_courses', function($table)
        {
            $table->string('card_color')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('genuineq_tms_courses', function($table)
        {
            $table->dropColumn('card_color');
        });
    }
}

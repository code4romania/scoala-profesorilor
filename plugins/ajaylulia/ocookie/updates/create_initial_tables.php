<?php

namespace AjayLulia\OSecure\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use DB;
class CreateInitialTables extends Migration {
    public function up() {
        if (!Schema::hasTable('ajaylulia_ocookie_configuration')) {
                Schema::create('ajaylulia_ocookie_configuration', function (Blueprint $table) {
                    $table->engine = 'InnoDB';
                    $table->increments('id');
                    $table->string('display_position', 100);
                    $table->string('button_text', 1000);
                    $table->string('background_color', 100);
                    $table->string('text_color', 100);
                    $table->string('link_color', 100);
                    $table->string('button_background_color', 100);
                    $table->string('button_text_color', 100);
                    $table->string('cookie_content', 1000);
                });
                DB::table('ajaylulia_ocookie_configuration')->insert([
                    'display_position' => "top",
                    'button_text' => "I Accept",
                    'background_color' => "#222",
                    'text_color' => "#fff",
                    'link_color' => "#6bceff",
                    'button_background_color' => "#de2424",
                    'button_text_color' => "#fff",
                    'cookie_content' => "This website uses cookies to ensure you get the best experience on our website <a href='#'>Privacy Policy</a>."
                ]);
            }
        }
    }

?>
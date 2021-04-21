<?php namespace Genuineq\Tms\Updates;

use Db;
use Schema;
use October\Rain\Database\Updates\Migration;

class InitGenuineqCookiePopup extends Migration
{
    /**
     * Populates system tables and dependency plugins tables.
     */
    public function up()
    {
        /** Populate genuineq_cookie_configuration table. */
        Db::table('genuineq_cookie_configuration')->truncate();
        Db::table('genuineq_cookie_configuration')->insert(['id' => 1, 'display_position' => 'right', 'button_text' => 'Sunt de acord', 'background_color' => '#4c3949', 'text_color' => '#fff', 'link_color' => '#9a4877', 'button_background_color' => '#9a4877', 'button_text_color' => '#fff', 'cookie_content' => 'Pentru a-ti oferi o experienta buna de navigare, utilizam fisiere de tip cookie. Daca nu esti de acord cu utilizarea cookie-urilor, poti sa iti retragi consimtamantul prin modificarea setarilor din browser-ul tau. <a href="http:\/\/scoalaprofesorilor.ro\/politica-de-cookie">Politica de cookie</a>']);
    }

    public function down()
    {
        /** Clear genuineq_cookie_configuration table. */
        Db::table('genuineq_cookie_configuration')->truncate();
    }
}

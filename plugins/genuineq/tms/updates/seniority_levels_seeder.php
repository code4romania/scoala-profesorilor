<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\SeniorityLevel;
use October\Rain\Database\Updates\Seeder;

class SeniorityLevelsSeeder extends Seeder
{
    public function run()
    {
        SeniorityLevel::create(["name" => "Bacalaureat",   "diacritic" => "Bacalaureat",   "description" => ""]);
        SeniorityLevel::create(["name" => "Licenta",       "diacritic" => "Licență",       "description" => ""]);
        SeniorityLevel::create(["name" => "Master",        "diacritic" => "Master",        "description" => ""]);
        SeniorityLevel::create(["name" => "Doctorat",      "diacritic" => "Doctorat",      "description" => ""]);
        SeniorityLevel::create(["name" => "Post doctorat", "diacritic" => "Post doctorat", "description" => ""]);
    }
}

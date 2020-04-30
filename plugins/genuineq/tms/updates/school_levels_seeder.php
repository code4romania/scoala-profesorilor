<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\SchoolLevel;
use October\Rain\Database\Updates\Seeder;

class SchoolLevelsSeeder extends Seeder
{
    public function run()
    {
        SchoolLevel::create(["name" => "Prescolar", "diacritic" => "Preșcolar", "description" => ""]);
        SchoolLevel::create(["name" => "Primar",    "diacritic" => "Primar",    "description" => ""]);
        SchoolLevel::create(["name" => "Gimnazial", "diacritic" => "Gimnazial", "description" => ""]);
        SchoolLevel::create(["name" => "Liceal",    "diacritic" => "Liceal",    "description" => ""]);
        SchoolLevel::create(["name" => "Licenta",   "diacritic" => "Licență",   "description" => ""]);
        SchoolLevel::create(["name" => "Masterat",  "diacritic" => "Masterat",  "description" => ""]);
        SchoolLevel::create(["name" => "Doctorat",  "diacritic" => "Doctorat",  "description" => ""]);
    }
}

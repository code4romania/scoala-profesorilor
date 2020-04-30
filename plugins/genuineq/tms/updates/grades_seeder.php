<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Grade;
use October\Rain\Database\Updates\Seeder;

class GradesSeeder extends Seeder
{
    public function run()
    {
        Grade::create(["name" => "Definitivat", "diacritic" => "Definitivat", "description" => ""]);
        Grade::create(["name" => "Gradul II",   "diacritic" => "Gradul II",   "description" => ""]);
        Grade::create(["name" => "Gradul I",    "diacritic" => "Gradul I",    "description" => ""]);
    }
}

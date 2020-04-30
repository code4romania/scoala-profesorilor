<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\ContractType;
use October\Rain\Database\Updates\Seeder;

class ContractTypesSeeder extends Seeder
{
    public function run()
    {
        ContractType::create(["name" => "Titular",      "diacritic" => "Titular",      "description" => ""]);
        ContractType::create(["name" => "Suplinitor",   "diacritic" => "Suplinitor",   "description" => ""]);
        ContractType::create(["name" => "Plata cu ora", "diacritic" => "Plată cu ora", "description" => ""]);
    }
}

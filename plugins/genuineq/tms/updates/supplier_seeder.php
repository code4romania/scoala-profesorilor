<?php

namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Supplier;
use October\Rain\Database\Updates\Seeder;
use Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('ro_RO');

        for ($i=0; $i < 10; $i++) {

            $name = $faker->sentence($nbWords = 6, $variableNbWords = true);

            Supplier::create([
                'name' => $name,
                'slug' => str_slug($name, '-'),
                'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                'phone' => $faker->tollFreePhoneNumber(),
                'email' => $faker->companyEmail(),
            ]);
        }
    }
}

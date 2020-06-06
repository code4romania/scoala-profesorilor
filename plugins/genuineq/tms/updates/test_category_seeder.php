<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Category;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
use Faker;

class TestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_CATEGORIES', false)) {
            $faker = Faker\Factory::create();

            for ($i=0; $i < 35; $i++) {

                $name = $faker->sentence($nbWords = 3, $variableNbWords = true);

                Category::create([
                    'name' => $name,
                    'slug' => str_slug($name, '-'),
                    'color' => $faker->hexcolor(),
                    'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
                ]);
            }
        }
    }
}

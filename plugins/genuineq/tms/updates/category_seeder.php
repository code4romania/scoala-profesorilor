<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Category;
use October\Rain\Database\Updates\Seeder;
use Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Check if the environment is either local OR development. */
        if (App::environment(['local', 'development'])) {
            $faker = Faker\Factory::create();

            for ($i=0; $i < 12; $i++) {

                $name = $faker->sentence($nbWords = 6, $variableNbWords = true);

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

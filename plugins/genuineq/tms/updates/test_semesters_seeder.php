<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Semester;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Faker;

class TestSemesterSeeder extends Seeder
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
            $years = 20;

            for ($i=0; $i < $years; $i++) {
                Semester::create([
                    'year' => intval(date('Y')) - ($years - $i),
                    'semester' => 1,
                ]);

                Semester::create([
                    'year' => intval(date('Y')) - ($years - $i),
                    'semester' => 2,
                ]);
            }
        }
    }
}

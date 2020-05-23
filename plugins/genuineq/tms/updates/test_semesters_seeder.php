<?php namespace Genuineq\Tms\Updates;

use Genuineq\Tms\Models\Semester;
use October\Rain\Database\Updates\Seeder;
use Illuminate\Support\Facades\App;
use Config;
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
        /* Check if the FAKE data should be added in DB. */
        if (env('TMS_ADD_FAKE_SEMESTERS', false)) {
            $years = 5;

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
        } else {
            /**
             * Semester 1: August - January
             * Semester 2: February - June
             */

            /** Create the current semester. */
            Semester::create([
                'semester' => ((1 == date('n')) || (8 <= date('n'))) ? (1) : (2)
            ]);
        }
    }
}

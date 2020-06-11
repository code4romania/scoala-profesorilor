<?php namespace Genuineq\Tms\Models;

use Lang;
use Genuineq\Tms\Models\Skill;
use Genuineq\Tms\Models\Course;
use Genuineq\Tms\Models\Category;
use Genuineq\Tms\Models\Supplier;

class CourseImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [
        'name' => 'required',
        'supplier' => 'required',
        'duration' => 'required',
        'address' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'accredited' => 'required',
        'credits' => 'required',
        'price' => 'required',
        'description' => 'required',
    ];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                $course = new Course;

                /** Extract the supplier */
                $supplier = Supplier::whereName($data['supplier_name'])->first();

                if ($supplier) {
                    $data['supplier_id'] = $supplier->id;
                } else {
                    $this->logWarning($row, Lang::get('genuineq.tms::lang.course.import-export.supplier_not_found') . $data['supplier_name']);
                }

                $course->name = $data['name'];
                $course->slug = str_replace(' ', '-', strtolower($data['name']));
                $course->duration = $data['duration'];
                $course->address = $data['address'];
                $course->start_date = date('Y-m-d H:i:s', strtotime($data['start_date']));
                $course->end_date = date('Y-m-d H:i:s', strtotime($data['end_date']));
                $course->accredited = $data['accredited'];
                $course->credits = $data['credits'];
                $course->price = $data['price'];
                $course->description = $data['description'];
                $course->supplier_id = (array_key_exists('supplier_id', $data)) ? ($data['supplier_id']) : (null);
                $course->save();

                /** Extract the categories */
                $categoriesNames = explode(', ', $data['categories_names']);
                foreach ($categoriesNames as $key => $categoryName) {
                    $category = Category::whereName($categoryName)->first();

                    if (!$category) {
                        $this->logWarning($row, Lang::get('genuineq.tms::lang.course.import-export.category_not_found') . $categoryName);
                    } else {
                        $course->categories()->attach($category);
                    }
                }

                /** Extract the skills */
                $skillsNames = explode(', ', $data['skills_names']);
                foreach ($skillsNames as $key => $skillName) {
                    $skill = Skill::whereName($skillName)->first();

                    if (!$skill) {
                        $this->logWarning($row, Lang::get('genuineq.tms::lang.course.import-export.skill_not_found') . $skillName);
                    } else {
                        $course->skills()->attach($skill);
                    }
                }

                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}

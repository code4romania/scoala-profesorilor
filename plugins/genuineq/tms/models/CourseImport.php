<?php namespace Genuineq\Tms\Models;

use Lang;
use Genuineq\Tms\Models\Course;
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

                /** Extract the supplier. */
                $supplier = Supplier::whereName($data['supplier'])->first();

                if ($supplier) {
                    $data['supplier_id'] = $supplier->id;
                } else {
                    $this->logWarning($row, Lang::get('genuineq.tms::lang.course.import-export.supplier_not_found') . $data['supplier']);
                }

                $data['slug'] = str_replace(' ', '-', strtolower($data['name']));

                $course->fill($data);
                $course->save();

                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}

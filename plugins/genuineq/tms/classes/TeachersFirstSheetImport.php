<?php namespace Genuineq\Tms\Classes;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Genuineq\Tms\Classes\SchoolTeacher;

class TeachersFirstSheetImport implements OnEachRow, WithHeadingRow
{
    private $rowsSuccessfull = 0;
    private $rowsFailed = 0;

    /**
     * Handles each row from the import file.
     */
    public function onRow(Row $row)
    {
        if (SchoolTeacher::createSchoolTeacher($row->toArray())) {
            ++$this->rowsSuccessfull;
        } else {
            ++$this->rowsFailed;
        }
    }

    public function headingRow(): int
    {
        return 2;
    }

    public function getSuccessfullRowCount(): int
    {
        return $this->rowsSuccessfull;
    }

    public function getFailedRowCount(): int
    {
        return $this->rowsFailed;
    }
}

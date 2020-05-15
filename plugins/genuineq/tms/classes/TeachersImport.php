<?php namespace Genuineq\Tms\Classes;

use Genuineq\Tms\Classes\TeachersFirstSheetImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TeachersImport implements WithMultipleSheets
{
    /**
     * Specify what sheets to be parsed.
     */
    public function sheets(): array
    {
        return [
            'data' => new TeachersFirstSheetImport(),
        ];
    }

}

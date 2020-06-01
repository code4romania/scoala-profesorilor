<?php namespace Genuineq\Tms\Classes;

use Genuineq\Tms\Classes\TeachersFirstSheetImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TeachersImport implements WithMultipleSheets
{
    private $firstSheetReader;
    /**
     * Specify what sheets to be parsed.
     */
    public function sheets(): array
    {
        $this->firstSheetReader = new TeachersFirstSheetImport();
        return [
            'data' => $this->firstSheetReader
        ];
    }

    public function getSuccessfullRowCount(): int
    {
        return $this->firstSheetReader->getSuccessfullRowCount();
    }

    public function getFailedRowCount(): int
    {
        return $this->firstSheetReader->getFailedRowCount();
    }
}

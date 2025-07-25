<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CrudTableExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;
    protected $headings;

    public function __construct($data, $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return $this->headings;
    }
}

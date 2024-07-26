<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Item::select($this->fields)->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->fields;
    }
}

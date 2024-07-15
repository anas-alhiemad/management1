<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Item::all();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'id',
            'name',
            'description',
            'quantity',
            'minimum_quantity', // Add this field
            'status',
            'available',
            'expired_date',
            'type_id',
            'category_id',
            'created_at',
            'updated_at',
        ];
    }
}

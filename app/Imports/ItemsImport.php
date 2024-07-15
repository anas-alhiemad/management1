<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Item([
            'name'            => $row['name'],
            'description'     => $row['description'],
            'quantity'        => $row['quantity'],
            'minimum_quantity'=> $row['minimum_quantity'], // Add this field
            'status'          => $row['status'],
            'available'       => $row['available'],
            'expired_date'    => $row['expired_date'],
            'type_id'         => $row['type_id'],
            'category_id'     => $row['category_id'],
        ]);
    }
}

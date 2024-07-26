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


// app/Imports/ItemsImport.php

// namespace App\Imports;

// use App\Models\Item;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithValidation;
// use Illuminate\Validation\Rule;

// class ItemsImport implements ToModel, WithHeadingRow, WithValidation
// {
//     public function model(array $row)
//     {
//         return new Item([
//             'name' => $row['name'],
//             'description' => $row['description'],
//             'quantity' => $row['quantity'],
//             'minimum_quantity' => $row['minimum_quantity'],
//             'status' => $row['status'],
//             'available' => $row['available'],
//             'expired_date' => $row['expired_date'],
//             'type_id' => $row['type_id'],
//             'category_id' => $row['category_id'],
//         ]);
//     }

//     public function rules(): array
//     {
//         return [
//             '*.name' => 'required|string',
//             '*.description' => 'nullable|string',
//             '*.quantity' => 'required|integer',
//             '*.minimum_quantity' => 'nullable|integer',
//             '*.status' => 'required|string',
//             '*.available' => 'required|boolean',
//             '*.expired_date' => 'nullable|date',
//             '*.type_id' => 'required|integer|exists:types,id',
//             '*.category_id' => 'required|integer|exists:categories,id',
//         ];
//     }
// }

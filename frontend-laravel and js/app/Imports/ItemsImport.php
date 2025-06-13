<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Item([
            'itemDescription' => $row['itemdescription'],  // header names are case-insensitive
            'image_url'       => $row['image_url'],
            'image_path'      => $row['image_path'],
        ]);
    }
}

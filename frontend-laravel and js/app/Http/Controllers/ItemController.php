<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function import()
    {
        $filePath = database_path('data/unique_items_with_images.csv');  // your csv location
        
        Excel::import(new ItemsImport, $filePath);

        return "Items imported successfully!";
    }
}

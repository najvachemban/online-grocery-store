<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;


class HomeController extends Controller
{
    public function index()
{
    $currentMonth = date('n');

    $response = Http::get('http://127.0.0.1:8001/recommended-items/', [
        'month' => $currentMonth
    ]);

    $items = [];

    if ($response->successful()) {
        $recommendations = $response->json()['recommendations'] ?? [];

        foreach ($recommendations as $item) {
            // Create the image filename
            $filename = strtolower($item['itemDescription']) . '.jpg';
            $filepath = public_path('images/' . $filename);
            $publicPath = public_path($filename); // fallback check in public/
            $fallbackUrl = "https://your-public-storage.example.com/item_images";

            // Check if the image file exists
            if (file_exists($filepath)) {
                $imageUrl = asset('images/' . $filename);
            }else if (file_exists(public_path($filename))) {
                $imageUrl = asset($filename);
            } else {
                $imageUrl = $fallbackUrl . '/' . $filename;
            }


            $items[] = [
                'itemDescription' => str_replace('/', ' or ', $item['itemDescription']),
                'item_id' => $item['item_id'],

                'total_bought' => $item['total_bought'],
                'image' => $imageUrl,
            ];
        }
    }

    return view('home', [
        'items' => $items,
        'month' => $currentMonth,
    ]);
}
}


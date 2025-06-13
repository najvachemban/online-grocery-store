<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    // View cart
    public function index()
{
    $cartItems = Cart::all();
    $productData = [];

    foreach ($cartItems as $item) {
        $response = Http::timeout(3)->get("http://127.0.0.1:8001/item/{$item->item_id}");

        if ($response->successful()) {
            $productData[$item->item_id] = $response->json();
        } else {
            Log::warning("FastAPI failed for item ID {$item->item_id}");
            $productData[$item->item_id] = [
                'itemDescription' => 'Unavailable',
                'price' => null,
                'image' => null,
            ];
        }
    }

    return view('user.cart', compact('cartItems', 'productData'));
}


    // Add to cart
    public function store(Request $request)
{
    $request->validate([
        'item_id' => 'required|integer',
        'quantity' => 'required|integer|min:1',
    ]);

    Cart::create([
        'item_id' => $request->input('item_id'),
        'quantity' => $request->input('quantity'),
    ]);

    return redirect()->route('user.cart')->with('success', 'Product added to cart.');
}

    // Remove item
    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return redirect()->route('user.cart')->with('success', 'Item removed.');
    }
}


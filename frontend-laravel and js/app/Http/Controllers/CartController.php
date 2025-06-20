<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // View cart
public function index()
{
    $cartItems = [];

    if (Auth::check()) {
        // ✅ Logged-in user: get from database
        $cartItems = \App\Models\Cart::where('user_id', Auth::id())->get();
    } else {
        // ✅ Guest user: get from session
        $sessionCart = session()->get('cart', []);
        foreach ($sessionCart as $item) {
            $cartItems[] = (object) [
                'item_id' => $item['item_id'],
                'quantity' => $item['quantity'],
                'id' => 'guest-' . $item['item_id'], // to mimic cart item ID for blade
            ];
        }
    }

    // ✅ Fetch product data using FastAPI
    $productData = [];

    foreach ($cartItems as $item) {
        $response = Http::timeout(3)->get("http://127.0.0.1:8001/item/{$item->item_id}");

        if ($response->successful()) {
            $productData[$item->item_id] = $response->json();
        } else {
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

    if (Auth::check()) {
        // Authenticated user
        $userId = Auth::id();

        $existing = Cart::where('user_id', $userId)
            ->where('item_id', $request->item_id)
            ->first();

        if ($existing) {
            // Increase quantity
            $existing->quantity += $request->quantity;
            $existing->save();
        } else {
            // New cart item
            Cart::create([
                'user_id' => $userId,
                'item_id' => (int)$request->item_id,
                'quantity' => (int)$request->quantity,
            ]);
        }

    } else {
        // Guest user: use session cart (array of items)
        $cart = session()->get('cart', []);

        $itemId = $request->item_id;
        $quantity = $request->quantity;

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] += $quantity;
        } else {
            $cart[$itemId] = [
                'item_id' => $itemId,
                'quantity' => $quantity,
            ];
        }

        session(['cart' => $cart]);
    }

    return redirect()->route('user.cart')->with('success', 'Product added to cart.');
}



    // Remove item
    public function destroy($id)
    {
        Cart::findOrFail($id)->delete();
        return redirect()->route('user.cart')->with('success', 'Item removed.');
    }
}


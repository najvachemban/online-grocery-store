@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Shopping Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

   <div class="row cart-items">
    @forelse($cartItems as $item)
        @php
            $product = $productData[$item->item_id] ?? null;
            $itemName = $product['itemDescription'] ?? 'Product Not Available';
            $imageFileName = strtolower(str_replace(' ', '_', $itemName)) . '.jpg';
            $imagePath = 'images/' . $imageFileName;
        @endphp


        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="{{ asset($imagePath) }}" 
                class="card-img-top" 
                alt="{{ $itemName }}"
                onerror="this.onerror=null; this.src='{{ asset('images/default-product.jpg') }}';">


                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $itemName }}</h5>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Qty: {{ $item->quantity }}</span>
                            {{-- Only show price if you have it --}}
                            @if(isset($product['price']))
                                <span class="fw-bold">${{ number_format($product['price'], 2) }}</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('cart.destroy', $item->id) }}" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                Your cart is currently empty.
            </div>
        </div>
    @endforelse
</div>

    <hr>

    <h4>Add Product (by FastAPI ID)</h4>
    <form method="POST" action="{{ route('cart.store') }}">
        @csrf
        <div class="mb-3">
            <input type="number" name="item_id" placeholder="Product ID" required class="form-control">
        </div>
        <div class="mb-3">
            <input type="number" name="quantity" placeholder="Quantity" value="1" required class="form-control">
        </div>
        <button class="btn btn-primary">Add to Cart</button>
    </form>
</div>
@endsection

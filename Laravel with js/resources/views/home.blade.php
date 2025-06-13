@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Top Recommended Products for Month {{ $month }}</h2>

    @if (count($items) > 0)
        <div class="row">
            @foreach ($items as $item)
    @php
    // Clean itemDescription for display (replace slashes, capitalize)
    $displayName = ucwords(str_replace(['/', '\\'], ' or ', $item['itemDescription']));

    // Clean itemDescription for file path (lowercase, replace slashes, trim)
    $fileSafeName = strtolower(str_replace(['/', '\\'], ' ', $item['itemDescription']));
    $fileSafeName = preg_replace('/\s+/', ' ', $fileSafeName); // collapse multiple spaces
    $fileSafeName = trim($fileSafeName);

    // Final image path
    $imagePath = asset("images/{$fileSafeName}.jpg");
    @endphp

    <div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm border-0 product-card" style="transition: transform 0.3s ease;">
        <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $displayName }}" style="height: 200px; object-fit: cover;">
        <div class="card-body text-center">
            <h5 class="card-title">{{ $displayName }}</h5>
            <p class="card-text text-muted">Bought: {{ $item['total_bought'] }} times</p>

            <div class="d-grid gap-2">
                <form method="POST" action="/cart-store">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item['item_id'] }}">
                    <input type="number" name="quantity" value="1" min="1" class="form-control" style="width:80px;" />
                    <button type="submit" class="btn btn-primary btn-sm mt-2">Add to Cart</button>
                </form>
                <button class="btn btn-primary btn-sm" >
                    ⚡ Buy Now
                </button>
            </div>
        </div>
    </div>
</div>


    @endforeach

            </div>
        @else
            <p>No recommended items found.</p>
        @endif
    </div>
@endsection

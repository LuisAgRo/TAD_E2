@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-brand">{{ __('app.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-brand">{{ __('app.products') }}</a></li>
        <li class="breadcrumb-item active">{{ $product->name }}</li>
    </ol>
</nav>

<div class="row g-5">
    {{-- Imagen --}}
    <div class="col-md-6">
        @if($product->image)
           <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow product-show-image" style="width: 100%; height: 500px; object-fit: contain; background-color: #f8f9fa;">
        @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow product-show-placeholder">
                <span class="text-muted product-show-icon">
                    <i class="bi bi-palette"></i>
                </span>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="col-md-6">
        <span class="badge mb-3 bg-brand product-badge">
            {{ app()->getLocale() == 'en' ? ($product->category->name_en ?? $product->category->name) : ($product->category->name ?? __('app.no_category')) }}
        </span>

        <div class="d-flex align-items-center mb-2">
            @auth
                <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn p-0 border-0 bg-transparent favorite-toggle">
                        @if(auth()->user()->favoriteLists()->where('product_id', $product->id)->exists())
                            <span title="{{ __('app.remove_favorite') }}">❤️</span>
                        @else
                            <span title="{{ __('app.add_favorite') }}">🤍</span>
                        @endif
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-decoration-none me-2 favorite-toggle">
                    🤍
                </a>
            @endauth

            <h1 class="fw-bold m-0">{{ $product->name }}</h1>
        </div>

        <p class="text-muted mb-4">{{ $product->description }}</p>

        <div class="mb-4">
            <span class="fs-2 fw-bold text-brand">
                {{ number_format($product->price, 2) }} €
            </span>
        </div>

        <div class="mb-4">
            @if($product->stock > 0)
                <span class="text-success fw-semibold">✔ {{ __('app.in_stock') }} ({{ $product->stock }} {{ __('app.available') }})</span>
            @else
                <span class="text-danger fw-semibold">✘ {{ __('app.out_of_stock') }}</span>
            @endif
        </div>

        @if($product->stock > 0)
            @auth
                <form action="{{ route('cart.items.store', $product->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="mb-3">
                        <label for="quantity" class="form-label">{{ __('app.quantity') }}</label>
                        <input id="quantity" type="number" name="quantity" class="form-control"
                               min="1" max="{{ $product->stock }}" value="{{ old('quantity', 1) }}">
                    </div>
                    <button class="btn btn-lg w-100 text-white bg-brand" type="submit">
                        {{ __('app.add_to_cart') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-lg w-100 text-white mb-3 bg-brand">
                    {{ __('app.login_to_buy') }}
                </a>
            @endauth
        @else
            <button class="btn btn-lg w-100 btn-secondary mb-3" disabled>{{ __('app.no_stock') }}</button>
        @endif

        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
            {{ __('app.back_to_products') }}
        </a>

        @if(auth()->check() && auth()->user()->role_id === 1)
            <hr>
            <div class="d-flex gap-2 mt-2">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning flex-fill">{{ __('app.edit') }}</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-fill">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger w-100" onclick="return confirm('¿{{ __('app.delete') }} producto?')">{{ __('app.delete') }}</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

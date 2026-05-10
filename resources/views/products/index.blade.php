@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('app.products_title') }}</h2>
    @if(auth()->check() && auth()->user()->role_id === 1)
        <a href="{{ route('products.create') }}" class="btn btn-danger">{{ __('app.new_product') }}</a>
    @endif
</div>

<form method="GET" action="{{ route('products.index') }}" class="mb-4">
    <div class="d-flex gap-2 align-items-center">
        <select name="category_id" class="form-select w-auto">
            <option value="">{{ __('app.all_categories') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ app()->getLocale() == 'en' ? ($category->name_en ?? $category->name) : $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-danger">{{ __('app.filter') }}</button>
        @if(request('category_id'))
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">{{ __('app.clear') }}</a>
        @endif
    </div>
</form>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row row-cols-1 row-cols-md-3 g-4">
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 shadow-sm">
            @if($product->image)
               <img src="{{ asset($product->image) }}" class="img-fluid rounded shadow product-show-image" style="height: 400px; object-fit: cover; width: 100%;"
               alt="{{ $product->name }}">
            @else
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center product-image-placeholder">
                    <span class="text-white">{{ __('app.no_image') }}</span>
                </div>
            @endif

            <div class="card-body">
                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                    <h5 class="card-title">{{ $product->name }}</h5>
                </a>
                <p class="card-text text-muted small">{{ Str::limit($product->description, 80) }}</p>
                <span class="badge mb-2 bg-brand">
                    {{ app()->getLocale() == 'en' ? ($product->category->name_en ?? $product->category->name) : $product->category->name }}
                </span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h5 class="mb-0 fw-bold">{{ number_format($product->price, 2) }} €</h5>
                    <span class="text-muted small">{{ __('app.stock') }}: {{ $product->stock }}</span>
                </div>
            </div>

            <div class="card-footer bg-white">
                @if(auth()->check() && auth()->user()->role_id === 1)
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm flex-fill">{{ __('app.edit') }}</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-fill">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger btn-sm w-100" type="submit"
                                onclick="return confirm('¿{{ __('app.delete') }} producto?')">{{ __('app.delete') }}</button>
                        </form>
                    </div>
                @else
                    @if($product->stock > 0)
                        @auth
                            <form action="{{ route('cart.items.store', $product->id) }}" method="POST">
                                @csrf
                            <button class="btn w-100 text-white bg-brand" type="submit">
                                    {{ __('app.add_to_cart') }}
                                </button>
                            </form>
                        @else
                        <a href="{{ route('login') }}" class="btn w-100 text-white bg-brand">
                                {{ __('app.login_to_buy') }}
                            </a>
                        @endauth
                    @else
                        <button class="btn btn-secondary w-100" disabled>{{ __('app.no_stock') }}</button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="col-12">
            <p class="text-muted">{{ __('app.no_products_category') }}</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection

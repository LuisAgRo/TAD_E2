@extends('layouts.app')

@section('content')

{{-- HERO --}}
<div class="py-5 mb-5 rounded-3 text-white text-center hero-banner">
    <div class="py-4">
        <h1 class="display-4 fw-bold mb-3">{{ __('app.welcome_title') }}</h1>
        <p class="lead mb-4">{{ __('app.welcome_subtitle') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg fw-semibold px-5 text-brand">
            {{ __('app.see_products') }}
        </a>
    </div>
</div>

{{-- CATEGORÍAS --}}
<h2 class="mb-4">{{ __('app.explore_category') }}</h2>
<div class="row row-cols-2 row-cols-md-4 g-3 mb-5">
    @foreach($categories as $cat)
    <div class="col">
        <a href="{{ route('products.index', ['category_id' => $cat->id]) }}" class="text-decoration-none">
            <div class="card text-center h-100 border-0 shadow-sm hover-lift">
                <div class="card-body py-4">
                        <div class="mb-2 fs-1">
                            @switch($cat->slug)
                                @case('pintura') <i class="bi bi-brush text-brand"></i> @break
                                @case('ceramica') <i class="bi bi-cup-hot text-brand"></i> @break
                                @case('ilustracion') <i class="bi bi-pen text-brand"></i> @break
                                @case('escultura') <i class="bi bi-trophy text-brand"></i> @break
                                @default <i class="bi bi-bag text-brand"></i>
                            @endswitch
                        </div>
                   <h5 class="card-title mb-1 text-brand">
                        {{ app()->getLocale() == 'en' ? ($cat->name_en ?? $cat->name) : $cat->name }}
                    </h5>
                    <p class="text-muted small mb-0">
                        {{ app()->getLocale() == 'en' ? ($cat->description_en ?? $cat->description) : $cat->description }}
                    </p>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

{{-- PRODUCTOS DESTACADOS --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ __('app.latest_products') }}</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-danger btn-sm">{{ __('app.see_all') }}</a>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    @forelse($products as $product)
    <div class="col">
        <div class="card h-100 shadow-sm border-0">
            @if($product->image)
                <img src="{{ asset($product->image) }}" class="card-img-top product-image" style="height: 250px; object-fit: cover;" alt="{{ $product->name }}">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center product-image-placeholder">
                    <i class="bi bi-image text-muted image-icon"></i>
                </div>
            @endif
            <div class="card-body">
                <span class="badge mb-2 bg-brand">
                    {{ app()->getLocale() == 'en' ? ($product->category->name_en ?? $product->category->name) : ($product->category->name ?? __('app.no_category')) }}
                </span>
                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                    <h5 class="card-title">{{ $product->name }}</h5>
                </a>
                <p class="card-text text-muted small">{{ Str::limit($product->description, 70) }}</p>
                <div class="fw-bold fs-5 text-brand">{{ number_format($product->price, 2) }} €</div>
            </div>
            <div class="card-footer bg-white border-0">
                @if($product->stock > 0)
                    @auth
                        <form action="{{ route('cart.items.store', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn w-100 text-white bg-brand" type="submit">{{ __('app.add_to_cart') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn w-100 text-white bg-brand">{{ __('app.login_to_buy') }}</a>
                    @endauth
                @else
                    <button class="btn btn-secondary w-100" disabled>{{ __('app.no_stock') }}</button>
                @endif
            </div>
        </div>
    </div>
    @empty
        <div class="col-12">
            <p class="text-muted">{{ __('app.no_products') }}</p>
        </div>
    @endforelse
</div>

@endsection

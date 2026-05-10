@extends('layouts.app')

@section('content')

{{-- HERO --}}
<div class="py-5 mb-5 rounded-3 text-white text-center" style="background: linear-gradient(135deg, #C0392B, #922B21);">
    <div class="py-4">
        <h1 class="display-4 fw-bold mb-3">{{ __('app.welcome_title') }}</h1>
        <p class="lead mb-4">{{ __('app.welcome_subtitle') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg fw-semibold px-5" style="color: #C0392B;">
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
            <div class="card text-center h-100 border-0 shadow-sm" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='none'">
                <div class="card-body py-4">
                        <div class="mb-2 fs-1">
                            @switch($cat->slug)
                                @case('pintura') <i class="bi bi-brush" style="color:#C0392B;"></i> @break
                                @case('ceramica') <i class="bi bi-cup-hot" style="color:#C0392B;"></i> @break
                                @case('ilustracion') <i class="bi bi-pen" style="color:#C0392B;"></i> @break
                                @case('escultura') <i class="bi bi-trophy" style="color:#C0392B;"></i> @break
                                @default <i class="bi bi-bag" style="color:#C0392B;"></i>
                            @endswitch
                        </div>
                   <h5 class="card-title mb-1" style="color: #C0392B;">
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
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
            @else
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                </div>
            @endif
            <div class="card-body">
                <span class="badge mb-2" style="background-color: #C0392B;">
                    {{ app()->getLocale() == 'en' ? ($product->category->name_en ?? $product->category->name) : ($product->category->name ?? __('app.no_category')) }}
                </span>
                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                    <h5 class="card-title">{{ $product->name }}</h5>
                </a>
                <p class="card-text text-muted small">{{ Str::limit($product->description, 70) }}</p>
                <div class="fw-bold fs-5" style="color: #C0392B;">{{ number_format($product->price, 2) }} €</div>
            </div>
            <div class="card-footer bg-white border-0">
                @if($product->stock > 0)
                    @auth
                        <form action="{{ route('cart.items.store', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn w-100 text-white" style="background-color: #C0392B;" type="submit">{{ __('app.add_to_cart') }}</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn w-100 text-white" style="background-color: #C0392B;">{{ __('app.login_to_buy') }}</a>
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
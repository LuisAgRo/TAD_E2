@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 fw-bold">{{ __('app.my_favorites') }} <span class="text-brand">❤️</span></h2>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($favoriteItems as $item)
            @php $product = $item->product; @endphp

            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" class="card-img-top product-image">
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
                        <form action="{{ route('cart.items.store', $product->id) }}" method="POST">
                            @csrf
                            <button class="btn w-100 text-white bg-brand" type="submit">
                                {{ __('app.add_to_cart') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">{{ __('app.no_favorites') }}</p>
                <a href="{{ route('products.index') }}" class="btn text-white bg-brand">{{ __('app.see_products') }}</a>
            </div>
        @endforelse
    </div>
</div>
@endsection

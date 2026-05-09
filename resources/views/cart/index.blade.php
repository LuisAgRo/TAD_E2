@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Mi carrito</h2>
    @if($items->isNotEmpty())
    <form action="{{ route('cart.clear') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Vaciar todo el carrito?')">
            Vaciar carrito
        </button>
    </form>
    @endif
</div>

@if($items->isEmpty())
<div class="alert alert-info">Tu carrito esta vacio.</div>
<a href="{{ route('products.index') }}" class="btn btn-danger">Explorar productos</a>
@else
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-end">Precio</th>
                <th style="width: 180px;">Cantidad</th>
                <th class="text-end">Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>
                    <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none text-dark fw-semibold">
                        {{ $item->product->name }}
                    </a>
                </td>
                <td class="text-end">{{ number_format($item->product->price, 2) }} €</td>
                <td>
                    <form action="{{ route('cart.items.update', $item->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('PUT')
                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            min="1"
                            max="{{ $item->product->stock }}"
                            value="{{ $item->quantity }}">
                        <button class="btn btn-outline-secondary" type="submit">OK</button>
                    </form>
                </td>
                <td class="text-end fw-semibold">{{ number_format($item->product->price * $item->quantity, 2) }} €</td>
                <td class="text-end">
                    <form action="{{ route('cart.items.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th class="text-end">{{ number_format($total, 2) }} €</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="d-flex justify-content-end mt-3">
    <form action="{{route('orders.checkout')}}" method="POST">
        @csrf
        <button type="submit" class="btn btn-lg text-white fw-bold px-5"
            style="background-color:#C0392B;">
            Proceder al pago →
        </button>
    </form>
</div>
@endif
@endsection
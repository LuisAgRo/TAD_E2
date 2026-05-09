@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pedido {{ $order->order_number }}</h2>
<a href="{{ auth()->user()->role_id === 1 ? route('admin.orders') : route('orders.index') }}" class="btn btn-outline-secondary">
    ← {{ auth()->user()->role_id === 1 ? 'Gestión de pedidos' : 'Mis pedidos' }}
</a></div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Productos</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-end">Precio unit.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->unit_price, 2) }} €</td>
                            <td class="text-end">{{ number_format($item->subtotal, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total</th>
                            <th class="text-end" style="color:#C0392B;">{{ number_format($order->total_amount, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Estado del pedido</h5>
                @php
                    $badges = [
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                    ];
                    $labels = [
                        'pending'    => 'Pendiente',
                        'processing' => 'En proceso',
                        'shipped'    => 'Enviado',
                        'delivered'  => 'Entregado',
                        'cancelled'  => 'Cancelado',
                    ];
                @endphp
                <span class="badge fs-6 bg-{{ $badges[$order->status] }}">
                    {{ $labels[$order->status] }}
                </span>
                <hr>
                <p class="mb-1"><strong>Fecha:</strong> {{ $order->ordered_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Dirección de envío</h5>
                <p class="mb-0">{{ $order->delivery_address }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Dirección de facturación</h5>
                <p class="mb-0">{{ $order->invoice_address }}</p>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role_id === 1)
<div class="card shadow-sm border-0 mt-3">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">Actualizar estado</h5>
        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex gap-2">
                <select name="status" class="form-select">
                    <option value="pending"    {{ $order->status == 'pending'    ? 'selected' : '' }}>Pendiente</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En proceso</option>
                    <option value="shipped"    {{ $order->status == 'shipped'    ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered"  {{ $order->status == 'delivered'  ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelled"  {{ $order->status == 'cancelled'  ? 'selected' : '' }}>Cancelado</option>
                </select>
                <button class="btn text-white" style="background-color:#C0392B;">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
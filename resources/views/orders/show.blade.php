@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>{{ __('app.order') }} {{ $order->order_number }}</h2>
    <a href="{{ auth()->user()->role_id === 1 ? route('admin.orders') : route('orders.index') }}" class="btn btn-outline-secondary">
        ← {{ auth()->user()->role_id === 1 ? __('app.manage_orders') : __('app.my_orders_title') }}
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ __('app.products') }}</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('app.product') }}</th>
                            <th class="text-center">{{ __('app.quantity') }}</th>
                            <th class="text-end">{{ __('app.unit_price') }}</th>
                            <th class="text-end">{{ __('app.subtotal') }}</th>
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
                            <th colspan="3" class="text-end">{{ __('app.total') }}</th>
                            <th class="text-end text-brand">{{ number_format($order->total_amount, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ __('app.order_status') }}</h5>
                @php
                    $badges = [
                        'pending'    => 'warning',
                        'processing' => 'info',
                        'shipped'    => 'primary',
                        'delivered'  => 'success',
                        'cancelled'  => 'danger',
                    ];
                    $labels = [
                        'pending'    => __('app.pending'),
                        'processing' => __('app.processing'),
                        'shipped'    => __('app.shipped'),
                        'delivered'  => __('app.delivered'),
                        'cancelled'  => __('app.cancelled'),
                    ];
                @endphp
                <span class="badge fs-6 bg-{{ $badges[$order->status] }}">
                    {{ $labels[$order->status] }}
                </span>
                <hr>
                <p class="mb-1"><strong>{{ __('app.date') }}:</strong> {{ $order->ordered_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ __('app.shipping_address') }}</h5>
                <p class="mb-0">{{ $order->delivery_address }}</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ __('app.invoice_address') }}</h5>
                <p class="mb-0">{{ $order->invoice_address }}</p>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role_id === 1)
<div class="card shadow-sm border-0 mt-3">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3">{{ __('app.update_status') }}</h5>
        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex gap-2">
                <select name="status" class="form-select">
                    <option value="pending"    {{ $order->status == 'pending'    ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>{{ __('app.processing') }}</option>
                    <option value="shipped"    {{ $order->status == 'shipped'    ? 'selected' : '' }}>{{ __('app.shipped') }}</option>
                    <option value="delivered"  {{ $order->status == 'delivered'  ? 'selected' : '' }}>{{ __('app.delivered') }}</option>
                    <option value="cancelled"  {{ $order->status == 'cancelled'  ? 'selected' : '' }}>{{ __('app.cancelled') }}</option>
                </select>
                <button class="btn text-white bg-brand">{{ __('app.update') }}</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

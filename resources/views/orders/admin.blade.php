@extends('layouts.app')

@section('content')
<h2 class="mb-4">{{ __('app.manage_orders') }}</h2>

@if($orders->isEmpty())
    <div class="alert alert-info">{{ __('app.no_orders') }}</div>
@else
    <div class="table-responsive">
        <table class="table shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>{{ __('app.order_number') }}</th>
                    <th>{{ __('app.client') }}</th>
                    <th>{{ __('app.date') }}</th>
                    <th>{{ __('app.status') }}</th>
                    <th class="text-end">{{ __('app.total') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
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
                <tr>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->ordered_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <span class="badge bg-{{ $badges[$order->status] }}">
                            {{ $labels[$order->status] }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">{{ number_format($order->total_amount, 2) }} €</td>
                    <td class="text-end">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger">{{ __('app.view') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
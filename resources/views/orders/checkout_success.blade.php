@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <div class="checkout-success-icon">✅</div>
    <h1 class="fw-bold mt-3 text-brand">¡Pago completado!</h1>
    <p class="lead text-muted">Tu pedido <strong>{{ $order->order_number }}</strong> ha sido confirmado.</p>
    <p class="text-muted">Recibirás un email de confirmación en breve.</p>
    <a href="{{ route('orders.index') }}" class="btn btn-lg text-white mt-3 bg-brand">
        Ver mis pedidos
    </a>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <div class="checkout-success-icon">❌</div>
    <h1 class="fw-bold mt-3 text-brand">¡Pago cancelado!</h1>
    <p class="lead text-muted">Tu pedido <strong>{{ $order->order_number }}</strong> ha sido cancelado.</p>
    <p class="text-muted">Si ha sido un error, puedes intentarlo de nuevo desde tus pedidos.</p>
    <a href="{{ route('orders.index') }}" class="btn btn-lg text-white mt-3 bg-brand">
        Ver mis pedidos
    </a>
</div>
@endsection

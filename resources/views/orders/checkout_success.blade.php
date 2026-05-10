@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <div style="font-size: 5rem;">✅</div>
    <h1 class="fw-bold mt-3" style="color:#C0392B;">¡Pago completado!</h1>
    <p class="lead text-muted">Tu pedido <strong>{{ $order->order_number }}</strong> ha sido confirmado.</p>
    <p class="text-muted">Recibirás un email de confirmación en breve.</p>
    <a href="{{ route('orders.index') }}" class="btn btn-lg text-white mt-3" style="background-color:#C0392B;">
        Ver mis pedidos
    </a>
</div>
@endsection
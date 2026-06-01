@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5 text-center">
                <h1 class="h3 fw-bold mb-3">Pago completado</h1>
                <p class="text-muted mb-4">
                    Gracias por tu compra, <strong>{{ $order->user->name }}</strong>
                    Tu pedido se ha procesado correctamente.
                </p>

                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-danger">
                        Ver mis pedidos
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        Seguir comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
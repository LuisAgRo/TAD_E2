<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { background-color: #C0392B; color: white; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th { background-color: #f5f5f5; padding: 10px; text-align: left; }
        .table td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { font-size: 1.2em; font-weight: bold; color: #C0392B; }
        .footer { background-color: #f5f5f5; padding: 20px; text-align: center; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BermellónShop</h1>
        <p>Confirmación de pedido</p>
    </div>

    <div class="content">
        <p>Hola <strong>{{ $order->user->name }}</strong>,</p>
        <p>Tu pedido ha sido confirmado correctamente. Aquí tienes el resumen:</p>

        <p><strong>Nº Pedido:</strong> {{ $order->order_number }}</p>
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->ordered_at)->format('d/m/Y H:i') }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }} €</td>
                    <td>{{ number_format($item->subtotal, 2) }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: {{ number_format($order->total_amount, 2) }} €</p>

        <p><strong>Dirección de envío:</strong><br>
        {{ $order->delivery_address }}</p>

        <p>Puedes ver el estado de tu pedido en tu perfil.</p>
    </div>

    <div class="footer">
        <p>© 2026 BermellónShop — Arte hecho a mano</p>
    </div>
</body>
</html>
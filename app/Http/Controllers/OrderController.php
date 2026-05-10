<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    // GET /checkout — mostrar selección de dirección
    public function checkout()
    {
        $user = Auth::user();
        $items = $user->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $addresses = Address::where('user_id', $user->id)->get();
        $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

        return view('orders.checkout', compact('items', 'addresses', 'total'));
    }

    // POST /orders — crear pedido y redirigir a Stripe
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = Auth::user();
        $items = $user->cartItems()->with('product')->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Tu carrito está vacío.');
        }

        $order = null;

        try {
            DB::beginTransaction();

            $address = Address::findOrFail($request->address_id);
            $deliveryText = $address->street . ', ' . $address->postal_code . ' ' . $address->city . ', ' . $address->country;

            if ($request->has('same_address') || !$request->invoice_address_id) {
                $invoiceText = $deliveryText;
            } else {
                $invoiceAddress = Address::findOrFail($request->invoice_address_id);
                $invoiceText = $invoiceAddress->street . ', ' . $invoiceAddress->postal_code . ' ' . $invoiceAddress->city . ', ' . $invoiceAddress->country;
            }

            $total = $items->sum(fn($item) => $item->product->price * $item->quantity);

            $order = Order::create([
                'user_id'          => $user->id,
                'address_id'       => $request->address_id,
                'order_number'     => 'ORD-' . strtoupper(uniqid()),
                'total_amount'     => $total,
                'status'           => 'pending',
                'ordered_at'       => now(),
                'delivery_address' => $deliveryText,
                'invoice_address'  => $invoiceText,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'unit_price' => $item->product->price,
                    'subtotal'   => $item->product->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $user->cartItems()->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Error al procesar el pedido. Inténtalo de nuevo.');
        }

        // Redirigir a Stripe
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

            $lineItems = [];
            foreach ($order->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency'     => 'eur',
                        'product_data' => ['name' => $item->product->name],
                        'unit_amount'  => (int)($item->unit_price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            $checkout_session = $stripe->checkout->sessions->create([
                'line_items'  => $lineItems,
                'mode'        => 'payment',
                'success_url' => route('orders.checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}&order_id=' . $order->id,
                'cancel_url'  => route('orders.checkout.cancel', [], true) . '?order_id=' . $order->id,
            ]);

            return redirect($checkout_session->url);

        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('mensaje', '¡Pedido realizado! No se pudo procesar el pago, inténtalo desde tus pedidos.');
        }
    }

    // GET /checkout/success — pago completado, enviar email
    public function showCheckoutSuccess(Request $request)
    {
        try {
            $sessionId = $request->query('session_id');
            $orderId   = $request->query('order_id');

            if (!$sessionId || !$orderId) {
                throw new NotFoundHttpException();
            }

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
            $payment_session = $stripe->checkout->sessions->retrieve($sessionId);

            if (!$payment_session) {
                throw new NotFoundHttpException();
            }

            // Actualizar estado del pedido
            $order = Order::with('items.product', 'user')->findOrFail($orderId);
            $order->update(['status' => 'processing']);

            // Enviar email de confirmación
            Mail::to($order->user->email)->send(new OrderConfirmation($order));

        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'Error al confirmar el pedido.');
        }

        return view('orders.checkout_success', compact('order'));
    }

    // GET /checkout/cancel — pago cancelado
    public function showCheckoutCancel(Request $request)
    {
        $orderId = $request->query('order_id');
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['status' => 'cancelled']);
            }
        }
        return view('orders.checkout_cancel');
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $query = Order::with('items.product');

        if (auth()->user()->role_id === 1) {
            $order = $query->findOrFail($id);
        } else {
            $order = $query->where('user_id', Auth::id())->findOrFail($id);
        }

        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status'        => $request->status,
            'delivery_date' => $request->status === 'delivered' ? now() : $order->delivery_date,
        ]);

        return back()->with('mensaje', 'Estado del pedido actualizado');
    }

    public function adminIndex()
    {
        $orders = Order::with('user', 'items.product')
            ->latest()
            ->get();

        return view('orders.admin', compact('orders'));
    }
}
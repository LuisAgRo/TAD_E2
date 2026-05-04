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

class OrderController extends Controller
{
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

        $orderForEmail = Order::with('items.product', 'user')->find($order->id);
        Mail::to($user->email)->send(new OrderConfirmation($orderForEmail));

        return redirect()->route('orders.index')
            ->with('mensaje', '¡Pedido realizado correctamente! Recibirás un email de confirmación.');
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
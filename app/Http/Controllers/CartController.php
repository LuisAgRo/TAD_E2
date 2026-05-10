<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()
            ->cartItems()
            ->with('product')
            ->latest()
            ->get();

        $total = $items->sum(function (CartItem $item) {
            return ($item->product?->price ?? 0) * $item->quantity;
        });

        return view('cart.index', compact('items', 'total'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantityToAdd = $data['quantity'] ?? 1;

        if ($product->stock <= 0) {
            return back()->with('error', 'Este producto no tiene stock disponible.');
        }

        $item = $request->user()
            ->cartItems()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            if (($item->quantity + $quantityToAdd) > $product->stock) {
                return back()->with('error', 'No puedes anadir mas unidades que el stock disponible.');
            }

            $item->increment('quantity', $quantityToAdd);
        } else {
            if ($quantityToAdd > $product->stock) {
                return back()->with('error', 'No puedes anadir mas unidades que el stock disponible.');
            }

            $request->user()->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $quantityToAdd,
            ]);
        }

        return back()->with('mensaje', __('app.product_added_cart'));
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($data['quantity'] > $cartItem->product->stock) {
            return back()->with('error', 'La cantidad solicitada supera el stock disponible.');
        }

        $cartItem->update(['quantity' => $data['quantity']]);

        return back()->with('mensaje', 'Cantidad actualizada correctamente.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        if ($cartItem->user_id !== $request->user()->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('mensaje', 'Producto eliminado del carrito.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->user()->cartItems()->delete();

        return back()->with('mensaje', 'Carrito vaciado correctamente.');
    }
}
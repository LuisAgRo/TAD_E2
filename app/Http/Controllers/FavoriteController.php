<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $favoriteItems = $request->user()
            ->favoriteLists()
            ->with('product.category')
            ->get();

        return view('favorites.index', compact('favoriteItems'));
    }

    public function toggle(Request $request, $productId)
    {
        $user = $request->user();
        
        $favorite = $user->favoriteLists()->where('product_id', $productId)->first();

        if ($favorite) {
            $favorite->delete();
            $mensaje = __('app.removed_favorite');
        } else {
            $user->favoriteLists()->create([
                'product_id' => $productId
            ]);
            $mensaje = __('app.added_favorite');
        }

        return back()->with('mensaje', $mensaje);
    }
}
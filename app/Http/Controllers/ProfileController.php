<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;
use App\Models\UserProfile;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        $addresses = Address::where('user_id', $user->id)->get();
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->get();
        return view('profile.index', compact('user', 'profile', 'addresses', 'orders'));
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('mensaje', __('app.data_updated'));
    }

    public function updatePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed|different:current_password',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'password');
        }

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()
                ->withErrors(['current_password' => __('app.wrong_password')])
                ->with('active_tab', 'password');
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()
            ->with('mensaje', __('app.password_updated'))
            ->with('active_tab', 'password');
    }

    public function storeAddress(Request $request)
    {
        $validator = validator($request->all(), [
            'street'      => 'required|string',
            'city'        => 'required|string',
            'postal_code' => 'required|digits_between:4,5',
            'country'     => 'required|string',
            'state'       => 'nullable|string',
        ], [
            'street.required'            => __('app.street_required'),
            'city.required'              => __('app.city_required'),
            'postal_code.required'       => __('app.postal_code_required'),
            'postal_code.digits_between' => __('app.postal_code_invalid'),
            'country.required'           => __('app.country_required'),
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'direcciones');
        }

        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id'     => Auth::id(),
            'street'      => $request->street,
            'city'        => $request->city,
            'state'       => $request->state,
            'postal_code' => $request->postal_code,
            'country'     => $request->country,
            'is_default'  => $request->boolean('is_default'),
        ]);

        $referer = $request->headers->get('referer', route('profile.index'));

        return redirect($referer)
            ->with('mensaje', __('app.address_added'));
    }

    public function destroyAddress($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $address->delete();
        return back()
            ->with('mensaje', __('app.address_deleted'))
            ->with('active_tab', 'direcciones');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validator = validator($request->all(), [
            'street'      => 'required|string',
            'city'        => 'required|string',
            'postal_code' => 'required|digits_between:4,5',
            'country'     => 'required|string',
            'state'       => 'nullable|string',
        ], [
            'street.required'            => __('app.street_required'),
            'city.required'              => __('app.city_required'),
            'postal_code.required'       => __('app.postal_code_required'),
            'postal_code.digits_between' => __('app.postal_code_invalid'),
            'country.required'           => __('app.country_required'),
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('active_tab', 'direcciones');
        }

        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address->update([
            'street'      => $request->street,
            'city'        => $request->city,
            'state'       => $request->state,
            'postal_code' => $request->postal_code,
            'country'     => $request->country,
            'is_default'  => $request->boolean('is_default'),
        ]);

        return back()
            ->with('mensaje', __('app.address_updated'))
            ->with('active_tab', 'direcciones');
    }

    public function storePaymentMethod(Request $request)
    {
        $request->validate([
            'card_holder' => 'required|string',
            'card_number' => 'required|digits:16',
            'expiry'      => 'required|string',
            'card_type'   => 'required|string',
        ]);

        $methods = session('payment_methods', []);
        $methods[] = [
            'id'          => uniqid(),
            'card_holder' => $request->card_holder,
            'last4'       => substr($request->card_number, -4),
            'expiry'      => $request->expiry,
            'card_type'   => $request->card_type,
        ];
        session(['payment_methods' => $methods]);

        return back()->with('mensaje', __('app.payment_added'));
    }

    public function destroyPaymentMethod($id)
    {
        $methods = session('payment_methods', []);
        $methods = array_filter($methods, fn($m) => $m['id'] !== $id);
        session(['payment_methods' => array_values($methods)]);
        return back()->with('mensaje', __('app.payment_deleted'));
    }
}

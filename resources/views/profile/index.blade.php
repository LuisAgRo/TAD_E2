@extends('layouts.app')

@section('content')
<div class="row">
    {{-- SIDEBAR --}}
    <div class="col-md-3">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body text-center py-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 profile-avatar">
                    <span class="text-white fw-bold fs-3">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <h6 class="fw-bold mb-0">{{ auth()->user()->name }}</h6>
                <small class="text-muted">{{ auth()->user()->email }}</small>
            </div>
        </div>
        <div class="list-group shadow-sm">
            <a href="#datos" class="list-group-item list-group-item-action profile-tab {{ session('active_tab', 'datos') == 'datos' ? 'active' : '' }}"
               data-bs-toggle="list">
                👤 {{ __('app.profile_title') }}
            </a>
            <a href="#password" class="list-group-item list-group-item-action profile-tab {{ session('active_tab') == 'password' ? 'active' : '' }}"
               data-bs-toggle="list">
                🔑 {{ __('app.change_password') }}
            </a>
            <a href="#direcciones" class="list-group-item list-group-item-action profile-tab {{ session('active_tab') == 'direcciones' ? 'active' : '' }}"
               data-bs-toggle="list">
                📍 {{ __('app.addresses') }}
            </a>
            <a href="#pagos" class="list-group-item list-group-item-action profile-tab {{ session('active_tab') == 'pagos' ? 'active' : '' }}"
               data-bs-toggle="list">
                💳 {{ __('app.payment_methods') }}
            </a>
            <a href="#pedidos" class="list-group-item list-group-item-action profile-tab {{ session('active_tab') == 'pedidos' ? 'active' : '' }}"
               data-bs-toggle="list">
                📦 {{ __('app.my_orders_title') }}
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="list-group-item list-group-item-action text-danger w-100 text-start">
                    🚪 {{ __('app.logout') }}
                </button>
            </form>
        </div>
    </div>

    {{-- CONTENIDO --}}
    <div class="col-md-9">
        <div class="tab-content">

            {{-- DATOS PERSONALES --}}
            <div class="tab-pane fade {{ session('active_tab', 'datos') == 'datos' ? 'show active' : '' }}" id="datos">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">{{ __('app.profile_title') }}</h5>
                        <form action="{{ route('profile.updateInfo') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ __('app.name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('app.email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                            </div>
                            <button class="btn text-white bg-brand">{{ __('app.save_changes') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- CONTRASEÑA --}}
            <div class="tab-pane fade {{ session('active_tab') == 'password' ? 'show active' : '' }}" id="password">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">{{ __('app.change_password') }}</h5>
                        <form action="{{ route('profile.updatePassword') }}" method="POST">
                            @csrf
                            @error('current_password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3">
                                <label class="form-label">{{ __('app.current_password') }}</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('app.new_password') }}</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('app.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <button class="btn text-white bg-brand">{{ __('app.update_password') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DIRECCIONES --}}
            <div class="tab-pane fade {{ session('active_tab') == 'direcciones' ? 'show active' : '' }}" id="direcciones">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">{{ __('app.my_addresses') }}</h5>
                        @forelse($addresses as $address)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    @if($address->is_default)
                                        <span class="badge mb-1 bg-brand">{{ __('app.default') }}</span><br>
                                    @endif
                                    <strong>{{ $address->street }}</strong><br>
                                    {{ $address->postal_code }} {{ $address->city }}
                                    @if($address->state), {{ $address->state }}@endif<br>
                                    {{ $address->country }}
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-secondary" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#editAddress{{ $address->id }}"
                                            aria-expanded="false"
                                            aria-controls="editAddress{{ $address->id }}">
                                        {{ __('app.edit') }}
                                    </button>
                                    <form action="{{ route('profile.destroyAddress', $address->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('app.confirm_delete_address') }}')">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </div>

                            <div class="collapse mt-3" id="editAddress{{ $address->id }}">
                                <form action="{{ route('profile.updateAddress', $address->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('app.street') }}</label>
                                        <input type="text" name="street" class="form-control" value="{{ old('street', $address->street) }}">
                                    </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label class="form-label">{{ __('app.city') }}</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city', $address->city) }}">
                                        </div>
                                        <div class="col mb-3">
                                            <label class="form-label">{{ __('app.postal_code') }}</label>
                                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $address->postal_code) }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mb-3">
                                            <label class="form-label">{{ __('app.state') }}</label>
                                            <input type="text" name="state" class="form-control" value="{{ old('state', $address->state) }}">
                                        </div>
                                        <div class="col mb-3">
                                            <label class="form-label">{{ __('app.country') }}</label>
                                            <input type="text" name="country" class="form-control" value="{{ old('country', $address->country) }}">
                                        </div>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_default" class="form-check-input"
                                               id="is_default_{{ $address->id }}"
                                               {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_default_{{ $address->id }}">{{ __('app.set_default') }}</label>
                                    </div>
                                    <button class="btn text-white bg-brand">{{ __('app.update') }}</button>
                                </form>
                            </div>
                        </div>
                        @empty
                            <p class="text-muted">{{ __('app.no_addresses') }}</p>
                        @endforelse
                        <hr>
                        <h6 class="fw-bold mb-3">{{ __('app.add_address') }}</h6>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('profile.storeAddress') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">{{ __('app.street') }}</label>
                                <input type="text" name="street" class="form-control"
                                       placeholder="Calle Mayor 1" value="{{ old('street') }}">
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">{{ __('app.city') }}</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">{{ __('app.postal_code') }}</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label class="form-label">{{ __('app.state') }}</label>
                                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                                </div>
                                <div class="col mb-3">
                                    <label class="form-label">{{ __('app.country') }}</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country', 'España') }}">
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_default" class="form-check-input" id="is_default">
                                <label class="form-check-label" for="is_default">{{ __('app.set_default') }}</label>
                            </div>
                            <button class="btn text-white bg-brand">{{ __('app.add_address') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MÉTODOS DE PAGO --}}
            <div class="tab-pane fade {{ session('active_tab') == 'pagos' ? 'show active' : '' }}" id="pagos">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">{{ __('app.payment_methods') }}</h5>
                        <div class="alert alert-info d-flex align-items-center gap-2">
                            <i class="bi bi-credit-card fs-5"></i>
                            <span>{{ __('app.payment_stripe_info') }}</span>
                        </div>
                        <a href="{{ route('orders.checkout') }}" class="btn text-white bg-brand">
                            <i class="bi bi-bag"></i> {{ __('app.go_to_shop') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- PEDIDOS --}}
            <div class="tab-pane fade {{ session('active_tab') == 'pedidos' ? 'show active' : '' }}" id="pedidos">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">{{ __('app.my_orders_title') }}</h5>
                        @if($orders->isEmpty())
                            <p class="text-muted">{{ __('app.no_orders') }}</p>
                        @else
                            @foreach($orders as $order)
                            <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $order->order_number }}</strong><br>
                                    <small class="text-muted">{{ $order->ordered_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">{{ number_format($order->total_amount, 2) }} €</span><br>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-danger mt-1">{{ __('app.view_detail') }}</a>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

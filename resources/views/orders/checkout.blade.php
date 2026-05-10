@extends('layouts.app')

@section('content')
<h2 class="mb-4">{{ __('app.order_summary') }}</h2>

<div class="row g-4">
    {{-- PRODUCTOS --}}
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ __('app.products') }}</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('app.product') }}</th>
                            <th class="text-center">{{ __('app.quantity') }}</th>
                            <th class="text-end">{{ __('app.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->product->price * $item->quantity, 2) }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">{{ __('app.total') }}</th>
                            <th class="text-end">{{ number_format($total, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- DIRECCIÓN Y CONFIRMAR --}}
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">{{ __('app.shipping_address') }}</h5>

                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf

                    @if($addresses->isEmpty())
                        <div class="alert alert-warning">
                            {{ __('app.no_addresses') }}
                            <a href="{{ route('profile.index') }}">{{ __('app.add_here') }}</a>
                        </div>
                    @else
                        @foreach($addresses as $address)
                        <div class="form-check border rounded p-3 mb-2">
                            <input class="form-check-input" type="radio" name="address_id"
                                   value="{{ $address->id }}"
                                   id="address_{{ $address->id }}"
                                   {{ $loop->first || $address->is_default ? 'checked' : '' }}>
                            <label class="form-check-label" for="address_{{ $address->id }}">
                                <strong>{{ $address->street }}</strong><br>
                                {{ $address->postal_code }} {{ $address->city }}<br>
                                {{ $address->country }}
                                @if($address->is_default)
                                    <span class="badge" style="background-color:#C0392B;">{{ __('app.default') }}</span>
                                @endif
                            </label>
                        </div>
                        @endforeach

                        @error('address_id')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <hr>

                        {{-- DIRECCIÓN DE FACTURACIÓN --}}
                        <h5 class="fw-bold mb-3">{{ __('app.invoice_address') }}</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="same_address" id="same_address" checked
                                onchange="toggleInvoiceAddress()">
                            <label class="form-check-label" for="same_address">
                                {{ __('app.same_address') }}
                            </label>
                        </div>

                        <div id="invoice_address_section" style="display:none;">
                            @foreach($addresses as $address)
                            <div class="form-check border rounded p-3 mb-2">
                                <input class="form-check-input" type="radio" name="invoice_address_id"
                                    value="{{ $address->id }}"
                                    id="invoice_{{ $address->id }}"
                                    {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label" for="invoice_{{ $address->id }}">
                                    <strong>{{ $address->street }}</strong><br>
                                    {{ $address->postal_code }} {{ $address->city }}<br>
                                    {{ $address->country }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                        <script>
                        function toggleInvoiceAddress() {
                            const section = document.getElementById('invoice_address_section');
                            const checked = document.getElementById('same_address').checked;
                            section.style.display = checked ? 'none' : 'block';
                        }
                        </script>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-5">{{ __('app.total') }}:</span>
                            <span class="fw-bold fs-5" style="color:#C0392B;">{{ number_format($total, 2) }} €</span>
                        </div>

                        <button type="submit" class="btn w-100 text-white fw-bold"
                                style="background-color:#C0392B;"
                                onclick="return confirm('{{ __('app.confirm_order_msg') }}')">
                            {{ __('app.confirm_order') }}
                        </button>
                    @endif
                </form>

                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    {{ __('app.back_to_cart') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
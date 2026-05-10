<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BermellonShop') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    @vite(['resources/js/app.js', 'resources/css/app.scss'])
</head>
<body class="d-flex flex-column min-vh-100">
    <div id="app" class="d-flex flex-column flex-grow-1">
        <nav class="navbar navbar-expand-md shadow-sm bg-brand">
            <div class="container">
                <a class="navbar-brand text-white fw-bold" href="{{ url('/') }}">
                    BermellonShop
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                @if(auth()->check() && auth()->user()->role_id === 1)
                    <a class="nav-link text-white" href="{{ route('categories.index') }}">{{ __('app.categories') }}</a>
                @endif

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('products.index') }}">{{ __('app.products') }}</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('cart.index') }}">
                                    {{ __('app.cart') }} ({{ auth()->user()->cartItems()->sum('quantity') }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('favorites.index') }}">
                                    {{ __('app.favorites') }}
                                    <span class="badge rounded-pill bg-white text-danger">
                                        {{ auth()->user()->favoriteLists->count() }}
                                    </span>
                                </a>
                            </li>
                        @endauth
                    </ul>
                    <ul class="navbar-nav ms-auto">

                        {{-- Selector de idioma --}}
                        <li class="nav-item d-flex gap-2 align-items-center me-2">
                            <a href="{{ route('lang.switch', 'es') }}"
                               class="btn btn-sm {{ app()->getLocale() == 'es' ? 'btn-light' : 'btn-outline-light' }}">
                                ES
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}"
                               class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-light' : 'btn-outline-light' }}">
                                EN
                            </a>
                        </li>

                        @guest
                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('app.login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register') }}">{{ __('app.register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link text-white dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">{{ __('app.my_profile') }}</a></li>
                                    @if(auth()->check() && auth()->user()->role_id === 1)
                                        <li><a class="dropdown-item" href="{{ route('admin.orders') }}">{{ __('app.manage_orders') }}</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('orders.index') }}">{{ __('app.my_orders') }}</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('app.logout') }}
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container pt-4 flex-grow-1">
            @if(session('mensaje'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('mensaje') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    <footer class="mt-5 py-4 text-white text-center bg-brand">
    <div class="container">
        <p class="mb-1 fw-semibold fs-5">
            <i class="bi bi-palette-fill"></i> BermellónShop
        </p>
        <p class="mb-0 small">{{ __('app.footer_tagline') }} · © {{ date('Y') }}</p>
    </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <title>{{ config('app.name') }} - @yield('title', 'Comida Rápida')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bubblegum+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @if(!View::hasSection('hideCart'))
        <link href="{{ asset('css/cart-bar.css') }}" rel="stylesheet">
        <link href="{{ asset('css/quantity-modal.css') }}" rel="stylesheet">
    @endif
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffffff;
        }
        @if(!View::hasSection('hideCart'))
        body {
            padding-bottom: 80px; /* Espacio para la barra del carrito */
        }
        @endif
        .brand-font {
            font-family: 'Bubblegum Sans', cursive;
        }
        .navbar {
            background-color: #df1518;
        }
        .btn-primary {
            background-color: #df1518;
            border-color: #ff6b35;
        }
        .btn-primary:hover {
            background-color: #e85a2c;
            border-color: #e85a2c;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .price {
            color: #df1518;
            font-weight: bold;
            font-size: 1.2em;
        }

        /* Ocultar el carrito en la barra de navegación en móviles */
        @media (max-width: 768px) {
            .navbar .cart-link {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4" style="background-color: {{ config('ui.navbar_bg', '#df1518') }};">
        <div class="container">
            <a class="navbar-brand brand-font fs-3" href="{{ route('products.index') }}">
                <img src="{{ asset(config('ui.navbar_logo', 'logo.png')) }}" alt="Fast Food Logo" style="height: 40px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-outline-primary ms-2" href="{{ route('login', ['redirect' => request()->path()]) }}">Iniciar sesión</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger ms-2">Cerrar sesión</button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    @if(!View::hasSection('hideCart'))
        <x-cart-bar />
    @endif

    <!-- Toast container para notificaciones -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1070;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    @if(!View::hasSection('hideCart'))
        <script>
            $(document).ready(function() {
                // Inicialización del carrito
                initializeCart();

                // Función para inicializar el carrito
                function initializeCart() {
                    if ($('.cart-items').length === 0) {
                        $('#cartOffcanvas .offcanvas-body').html(`
                            <div class="cart-items"></div>
                            <div class="cart-summary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Total:</h6>
                                    <span class="total-amount fs-5 fw-bold">Bs 0.00</span>
                                </div>
                            </div>
                        `);
                    }
                }

                // Event delegation para los botones de agregar al carrito
                $(document).on('click', '.add-to-cart', function(e) {
                    e.preventDefault();
                    const productId = $(this).data('product-id');
                    $('#productId').val(productId);
                    $('#quantityModal').modal('show');
                });

                // Inicializar tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-tooltip="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover focus'
                    });
                });

                // Función para actualizar la cantidad en el carrito
                window.updateCartBadge = function(count) {
                    $('.cart-count').text(count);
                    $('.cart-count').addClass('updating');
                    setTimeout(() => {
                        $('.cart-count').removeClass('updating');
                    }, 500);
                };

                // Función para mostrar notificación toast
                window.showToast = function(message, type = 'success') {
                    const toast = $('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">')
                        .html(`
                            <div class="toast-header bg-${type} text-white">
                                <strong class="me-auto">Notificación</strong>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                            </div>
                            <div class="toast-body">
                                ${message}
                            </div>
                        `);
                    
                    $('.toast-container').append(toast);
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                };
            });
        </script>
    @endif
    
    @stack('scripts')
</body>
</html>

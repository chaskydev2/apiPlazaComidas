@if(Route::is('products.index') && !View::hasSection('hideCart'))
<!-- Barra inferior del carrito -->
<div class="cart-bar" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" style="cursor: pointer;">
    <div class="cart-bar" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" style="cursor: pointer; background-color: {{ config('ui.cart_bar_bg', config('ui.navbar_bg', '#df1518')) }};">
    <div class="container">
        <div class="cart-bar-content">
            <div class="cart-info">
                <div>
                    <i class="fas fa-shopping-cart fa-lg"></i>
                    <span class="cart-count ms-2">{{ count(session('cart', [])) }}</span>
                    <span class="ms-1">items</span>
                </div>
                <div class="cart-total">
                    <span class="fw-bold">Total:</span>
                    <span class="ms-2">Bs {{ number_format(array_reduce(session('cart', []), function($carry, $item) { return $carry + ($item['price'] * $item['quantity']); }, 0), 2) }}</span>
                </div>
                <i class="fas fa-chevron-up cart-expand-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Barra de confirmación de pedido -->
<div id="confirmOrderBar" class="confirm-order-bar {{ count(session('cart', [])) === 0 ? 'disabled' : '' }}"
    @if(count(session('cart', [])) > 0)
        onclick="window.location.href='{{ route('checkout') }}'"
    @endif
    style="background-color: {{ config('ui.cart_bar_bg', config('ui.navbar_bg', '#df1518')) }};">
    <div class="container">
        <div class="confirm-order-content">
            @if(count(session('cart', [])) === 0)
                Agrega productos para continuar <i class="fas fa-shopping-cart ms-2"></i>
            @else
                Confirmar Pedido <i class="fas fa-arrow-right ms-2"></i>
            @endif
        </div>
    </div>
</div>

<!-- Panel emergente del carrito -->
<div class="offcanvas offcanvas-bottom cart-sheet" tabindex="-1" id="cartOffcanvas">
    <div class="offcanvas-header" style="cursor: pointer;" data-bs-dismiss="offcanvas">
        <h5 class="offcanvas-title brand-font">Tu Pedido</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" onclick="event.stopPropagation();"></button>
    </div>
    <div class="offcanvas-body">
        @if(count(session('cart', [])) > 0)
            <div class="cart-items">
                @foreach(session('cart') as $id => $details)
                    <div class="cart-item" data-id="{{ $id }}">
                        <div class="item-details">
                            <img src="{{ $details['image_url'] }}" alt="{{ $details['name'] }}" class="item-image">
                            <div class="item-info">
                                <h6 class="brand-font">{{ $details['name'] }}</h6>
                                <div class="quantity-controls">
                                    <button class="btn btn-sm btn-outline-primary update-cart" data-action="decrease">-</button>
                                    <span class="quantity">{{ $details['quantity'] }}</span>
                                    <button class="btn btn-sm btn-outline-primary update-cart" data-action="increase">+</button>
                                </div>
                                <div class="price-info">
                                    <span class="price">Bs {{ number_format($details['price'], 2) }}</span>
                                    <span class="subtotal">Bs {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-danger remove-from-cart">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="cart-summary position-fixed bottom-0 start-0 end-0 bg-white p-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Total:</h6>
                    <span class="total-amount fs-5 fw-bold">Bs {{ number_format(array_reduce(session('cart', []), function($carry, $item) { return $carry + ($item['price'] * $item['quantity']); }, 0), 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                    Proceder al Checkout
                </a>
            </div>
        @else
            <div class="empty-cart">
                <div class="text-center mb-4">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h5 class="brand-font">Tu carrito está vacío</h5>
                    <p class="text-muted">¡Agrega algunos productos deliciosos!</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Ver Productos
                </a>
            </div>
        @endif
    </div>
</div>
@endif

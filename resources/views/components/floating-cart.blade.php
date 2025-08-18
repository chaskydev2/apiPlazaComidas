<!-- Botón flotante del carrito -->
<div class="floating-cart-wrapper">
    <button type="button" 
            class="floating-cart-button" 
            data-bs-toggle="offcanvas" 
            data-bs-target="#cartOffcanvas" 
            aria-controls="cartOffcanvas"
            data-bs-tooltip="tooltip"
            title="Ver Carrito">
        <i class="fas fa-shopping-cart cart-icon"></i>
        <span class="cart-badge">{{ count(session('cart', [])) }}</span>
    </button>
</div>

<!-- Panel emergente del carrito -->
<div class="offcanvas offcanvas-bottom cart-sheet" tabindex="-1" id="cartOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title brand-font">🛒 Tu Carrito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @if(count(session('cart', [])) > 0)
            <div class="cart-items">
                @php $total = 0 @endphp
                @foreach(session('cart', []) as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
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
                                    <span>${{ number_format($details['price'], 2) }} c/u</span>
                                    <span class="subtotal">${{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-danger remove-from-cart">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="cart-summary">
                <div class="total">
                    <strong>Total:</strong>
                    <span class="total-price">${{ number_format($total, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary w-100 checkout-button">
                    Proceder al Checkout
                </a>
            </div>
        @else
            <div class="empty-cart">
                <p class="text-center">Tu carrito está vacío</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Ver Productos</a>
            </div>
        @endif
    </div>
</div>

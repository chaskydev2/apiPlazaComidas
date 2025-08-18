@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h1 class="text-center brand-font">🛒 Tu Carrito</h1>
    </div>
    
    @if(count($cart) > 0)
        <div class="col-md-8">
            @php $total = 0 @endphp
            @foreach($cart as $id => $details)
                @php $total += $details['price'] * $details['quantity'] @endphp
                <div class="card mb-3 cart-item" data-id="{{ $id }}">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{ $details['image_url'] }}" class="img-fluid rounded-start" alt="{{ $details['name'] }}" style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title brand-font">{{ $details['name'] }}</h5>
                                    <button class="btn btn-sm btn-danger remove-from-cart">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="mt-3">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <button class="btn btn-outline-primary btn-sm update-cart" data-action="decrease">-</button>
                                        <span class="quantity">{{ $details['quantity'] }}</span>
                                        <button class="btn btn-outline-primary btn-sm update-cart" data-action="increase">+</button>
                                    </div>
                                    <p class="card-text">
                                        Precio unitario: <span class="price">Bs {{ number_format($details['price'], 2) }}</span><br>
                                        Subtotal: <span class="subtotal">Bs {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-4">
            <div class="card shadow" style="background-color: #fff5e6;">
                <div class="card-body">
                    <h5 class="card-title brand-font fs-4">Resumen del Pedido</h5>
                    <hr>
                    <p class="card-text fs-5">
                        <strong>Total: <span class="total-price">Bs {{ number_format($total, 2) }}</span></strong>
                    </p>
                    <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3">
                        Proceder al Checkout
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-info text-center p-5">
                <h4 class="brand-font">Tu carrito está vacío</h4>
                <p class="mt-3">¿Qué tal si añades algunos productos deliciosos? 😋</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Ver Productos</a>
            </div>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function updateQuantity(el, action) {
        const item = el.closest('.cart-item');
        const id = item.data('id');
        let quantity = parseInt(item.find('.quantity').text());
        
        if (action === 'increase' && quantity < 10) {
            quantity++;
        } else if (action === 'decrease' && quantity > 1) {
            quantity--;
        }
        
        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                id: id,
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                updateCartDisplay(response.cart);
            }
        });
    }

    $('.update-cart').click(function() {
        updateQuantity($(this), $(this).data('action'));
    });

    $('.remove-from-cart').click(function() {
        const item = $(this).closest('.cart-item');
        const id = item.data('id');

        $.ajax({
            url: '{{ route("cart.remove") }}',
            method: 'POST',
            data: {
                id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                item.remove();
                updateCartDisplay(response.cart);
                
                if (Object.keys(response.cart).length === 0) {
                    location.reload();
                }
            }
        });
    });

    function updateCartDisplay(cart) {
        let total = 0;
        
        Object.keys(cart).forEach(function(id) {
            const item = cart[id];
            const itemEl = $(`.cart-item[data-id="${id}"]`);
            const subtotal = item.price * item.quantity;
            
            itemEl.find('.quantity').text(item.quantity);
            itemEl.find('.subtotal').text('Bs ' + subtotal.toFixed(2));
            
            total += subtotal;
        });
        
        $('.total-price').text('Bs ' + total.toFixed(2));
        $('.cart-count').text(Object.keys(cart).length);
    }
});
</script>
@endpush

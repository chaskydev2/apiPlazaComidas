@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<style>
    .promotion-slider {
        margin: 2rem 0;
        padding: 2rem 0;
        background-color: #fff5e6;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .swiper {
        width: 100%;
        height: 400px;
    }
    .swiper-slide {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .promotion-slide {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
        width: 90%;
        background-color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .promotion-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .promotion-content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        color: white;
        text-align: center;
    }
    .promotion-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: #df1518;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: bold;
        animation: pulse 2s infinite;
        box-shadow: 0 2px 8px rgba(223, 21, 24, 0.3);
        z-index: 1;
    }
    .discount-percentage {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: #ff6b6b;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: bold;
        font-size: 1.2rem;
        z-index: 1;
    }
    .swiper-button-next,
    .swiper-button-prev {
        color: #df1518;
    }
    .swiper-pagination-bullet-active {
        background-color: #df1518;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .original-price {
        text-decoration: line-through;
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.1em;
        margin-right: 1rem;
    }
    .promotion-price {
        font-size: 1.5em;
        font-weight: bold;
        color: white;
    }
</style>
@endpush

@section('content')
@if($promotions->count() > 0)
<div class="promotion-slider">
    <div class="container">
        <h2 class="text-center brand-font mb-4">🔥 ¡Promociones Especiales! 🔥</h2>
        <div class="swiper promotionSwiper">
            <div class="swiper-wrapper">
                @foreach($promotions as $promotion)
                @php
                    $discountPercentage = round((($promotion->original_price - $promotion->price) / $promotion->original_price) * 100);
                @endphp
                <div class="swiper-slide">
                    <div class="promotion-slide">
                        <img src="{{ $promotion->image_url }}" alt="{{ $promotion->name }}" class="promotion-image">
                        <div class="promotion-badge">
                            {{ $promotion->promotion_badge ?? '¡OFERTA ESPECIAL!' }}
                        </div>
                        <div class="discount-percentage">
                            -{{ $discountPercentage }}%
                        </div>
                        <div class="promotion-content">
                            <h3 class="brand-font mb-3 fs-2">{{ $promotion->name }}</h3>
                            <p class="mb-3">{{ $promotion->description }}</p>
                            <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                                <span class="original-price">Bs {{ number_format($promotion->original_price, 2) }}</span>
                                <span class="promotion-price">Bs {{ number_format($promotion->price, 2) }}</span>
                            </div>
                            <button class="btn btn-warning btn-lg add-to-cart" data-product-id="{{ $promotion->id }}">
                                <i class="fas fa-shopping-cart me-2"></i>¡Aprovechar Oferta!
                            </button>
                            @if($promotion->promotion_ends_at)
                            <div class="mt-3">
                                <small class="text-white">
                                    <i class="fas fa-clock me-1"></i>Oferta válida hasta: {{ \Carbon\Carbon::parse($promotion->promotion_ends_at)->format('d/m/Y') }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12 mb-5">
        <h1 class="text-center brand-font display-4">{{ config('ui.menu_title', '🌟 Nuestro Menú 🌟') }}</h1>
    </div>
    @foreach($products as $product)
    <div class="col-md-4 mb-5">
        <div class="card h-100 shadow-lg">
            <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}" 
                 style="height: 250px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
            @if($product->is_promotion)
            <div class="position-absolute top-0 end-0 m-3">
                <span class="badge bg-danger">
                    <i class="fas fa-tag me-1"></i>En Promoción
                </span>
            </div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title brand-font fs-4">{{ $product->name }}</h5>
                <p class="card-text flex-grow-1">{{ $product->description }}</p>
                <div class="mt-auto">
                    @if($product->is_promotion)
                    <p class="mb-1">
                        <span class="text-muted text-decoration-line-through">
                            Bs {{ number_format($product->original_price, 2) }}
                        </span>
                    </p>
                    @endif
                    <p class="price fs-3 mb-3">Bs {{ number_format($product->price, 2) }}</p>
                    <button class="btn btn-primary w-100 add-to-cart" data-product-id="{{ $product->id }}">
                         Añadir al Carrito
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal para cantidad -->
<div class="modal fade" id="quantityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <div class="product-info mb-4">
                    <img src="" alt="" class="product-image mb-3" style="width: 120px; height: 120px; object-fit: cover; border-radius: 10px;">
                    <h5 class="product-name brand-font mb-2"></h5>
                    <p class="product-price fs-4 text-primary mb-4"></p>
                </div>
                
                <input type="hidden" id="productId" name="productId" value="">
                
                <h6 class="mb-3">Seleccionar Cantidad</h6>
                <div class="quantity-selector mx-auto mb-4" style="max-width: 200px;">
                    <div class="d-flex align-items-center justify-content-between p-2" style="border: 2px solid #eee; border-radius: 50px;">
                        <button class="btn quantity-btn" data-action="decrease" style="width: 40px; height: 40px; padding: 0;">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" class="form-control border-0 text-center bg-transparent" id="quantity" 
                               min="1" max="10" value="1" style="width: 60px; font-size: 1.2rem;">
                        <button class="btn quantity-btn" data-action="increase" style="width: 40px; height: 40px; padding: 0;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <button type="button" class="btn btn-primary px-5 py-2 rounded-pill" id="confirmQuantity">
                    Añadir al Carrito
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar Swiper
        const swiper = new Swiper(".promotionSwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 2,
                slideShadows: false,
            },
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 20
                },
                640: {
                    slidesPerView: "auto",
                    spaceBetween: 30
                }
            }
        });

        $('.quantity-btn').click(function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            const input = $('#quantity');
            let value = parseInt(input.val());
            
            if (action === 'increase' && value < 10) {
                input.val(value + 1);
            } else if (action === 'decrease' && value > 1) {
                input.val(value - 1);
            }
        });

        $('.add-to-cart').click(function() {
            const button = $(this);
            const productId = button.data('product-id');
            const card = button.closest('.card');
            
            // Obtener información del producto
            const productName = card.find('.card-title').text();
            const productPrice = card.find('.price').text();
            const productImage = card.find('img').attr('src');
            
            // Actualizar el modal con la información del producto
            $('#productId').val(productId);
            $('#quantity').val(1);
            $('.product-name').text(productName);
            $('.product-price').text(productPrice);
            $('.product-image').attr('src', productImage);
            
            $('#quantityModal').modal('show');
        });

        // Función para actualizar el contenido del carrito
        function updateCartContent(response) {
            // Actualizar contadores
            $('.cart-count').text(response.count);
            $('.cart-total').text('Bs ' + response.total);
            
            // Actualizar estado de la barra de confirmación
            const confirmOrderBar = document.getElementById('confirmOrderBar');
            if (confirmOrderBar) {
                if (response.count > 0) {
                    confirmOrderBar.classList.remove('disabled');
                    confirmOrderBar.onclick = () => window.location.href = '{{ route('checkout') }}';
                    confirmOrderBar.querySelector('.confirm-order-content').innerHTML = 'Confirmar Pedido <i class="fas fa-arrow-right ms-2"></i>';
                } else {
                    confirmOrderBar.classList.add('disabled');
                    confirmOrderBar.onclick = null;
                    confirmOrderBar.querySelector('.confirm-order-content').innerHTML = 'Agrega productos para continuar <i class="fas fa-shopping-cart ms-2"></i>';
                }
            }
            
            // Obtener el offcanvas del carrito
            const cartOffcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));

            // Actualizar el contenido del carrito
            const cartItems = $('.cart-items');
            cartItems.empty();

            // Si hay items en el carrito, construir el nuevo contenido
            if (Object.keys(response.cart).length > 0) {
                Object.keys(response.cart).forEach(id => {
                    const item = response.cart[id];
                    const itemHtml = `
                        <div class="cart-item" data-id="${id}">
                            <div class="item-details">
                                <img src="${item.image_url}" alt="${item.name}" class="item-image">
                                <div class="item-info">
                                    <h6 class="brand-font">${item.name}</h6>
                                    <div class="quantity-controls">
                                        <button class="btn btn-sm btn-outline-primary update-cart" data-action="decrease">-</button>
                                        <span class="quantity">${item.quantity}</span>
                                        <button class="btn btn-sm btn-outline-primary update-cart" data-action="increase">+</button>
                                    </div>
                                    <div class="price-info">
                                        <span class="price">Bs ${parseFloat(item.price).toFixed(2)}</span>
                                        <span class="subtotal">Bs ${(item.price * item.quantity).toFixed(2)}</span>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-danger remove-from-cart">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    cartItems.append(itemHtml);
                });

                // Mostrar el offcanvas después de agregar el primer producto
                if (response.count === 1) {
                    cartOffcanvas.show();
                }
            } else {
                // Contenido para carrito vacío con atributos de cierre
                const emptyCartContent = `
                    <div class="empty-cart">
                        <div class="text-center mb-4" style="cursor: pointer;" data-bs-dismiss="offcanvas">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="brand-font">Tu carrito está vacío</h5>
                            <p class="text-muted">¡Agrega algunos productos deliciosos!</p>
                        </div>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            Ver Productos
                        </a>
                    </div>
                `;
                
                cartItems.html(emptyCartContent);
                
                // Asegurarnos de que la cabecera mantenga su funcionalidad
                const header = $('.offcanvas-header');
                if (header.length) {
                    header.attr('data-bs-dismiss', 'offcanvas')
                          .css('cursor', 'pointer');
                }
            }

            // Actualizar el resumen del carrito
            const totalAmount = $('.total-amount');
            totalAmount.text('Bs ' + response.total);

            // Actualizar botón de checkout
            const cartActions = $('.cart-actions');
            if (response.count > 0) {
                cartActions.html(`
                    <a href="{{ route('checkout') }}" class="btn btn-warning checkout-btn" onclick="event.stopPropagation();">
                        Hacer pedido
                    </a>
                `);
            } else {
                cartActions.empty();
            }

            // Animación de la barra del carrito
            $('.cart-bar').addClass('highlight');
            $('.cart-info').addClass('highlight');
            setTimeout(() => {
                $('.cart-bar').removeClass('highlight');
                $('.cart-info').removeClass('highlight');
            }, 500);
        }

        $('#confirmQuantity').click(function() {
            const productId = $('#productId').val();
            const quantity = $('#quantity').val();

            $.ajax({
                url: '{{ route("cart.add") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    product_id: parseInt(productId),
                    quantity: parseInt(quantity)
                }),
                success: function(response) {
                    $('#quantityModal').modal('hide');
                    
                    // Animar la barra del carrito
                    $('.cart-bar').addClass('highlight');
                    $('.cart-info').addClass('highlight');
                    
                    // Actualizar el contenido del carrito
                    updateCartContent(response);
                    
                    // Remover las clases de animación después de que termine
                    setTimeout(() => {
                        $('.cart-bar').removeClass('highlight');
                        $('.cart-info').removeClass('highlight');
                    }, 500);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Hubo un error al agregar el producto al carrito. Por favor intenta de nuevo.');
                }
            });
        });

        // Manejar eliminación de items del carrito
        $(document).on('click', '.remove-from-cart', function(e) {
            e.preventDefault();
            const item = $(this).closest('.cart-item');
            const id = item.data('id');

            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({ id: id }),
                success: function(response) {
                    // Actualizar el contenido del carrito
                    updateCartContent(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Hubo un error al eliminar el producto del carrito. Por favor intenta de nuevo.');
                }
            });
        });

        // Manejar actualización de cantidad en el carrito
        $(document).on('click', '.update-cart', function(e) {
            e.preventDefault();
            const action = $(this).data('action');
            const item = $(this).closest('.cart-item');
            const id = item.data('id');
            let quantity = parseInt(item.find('.quantity').text());
            
            if (action === 'increase' && quantity < 10) {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            } else {
                return;
            }

            $.ajax({
                url: '{{ route("cart.update") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    id: id,
                    quantity: quantity
                }),
                success: function(response) {
                    // Actualizar el contenido del carrito
                    updateCartContent(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('Hubo un error al actualizar la cantidad. Por favor intenta de nuevo.');
                }
            });
        });

        // Añadir manejador para cerrar el carrito cuando está vacío
        $(document).on('click', '.empty-cart, .offcanvas-header[data-bs-dismiss="offcanvas"]', function() {
            const cartOffcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('cartOffcanvas'));
            if (cartOffcanvas) {
                cartOffcanvas.hide();
            }
        });
    });
</script>
@endpush

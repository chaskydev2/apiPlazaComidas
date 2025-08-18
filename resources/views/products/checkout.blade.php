@extends('layouts.app')

@push('styles')
<style>
    #map {
        height: 100vh;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
    }
    
    .location-pin {
        animation: bounce 1s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .confirm-location-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 1rem;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .location-address {
        padding: 1rem;
        background: white;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: #666;
        display: flex;
        align-items: center;
        min-height: 3.5rem;
    }

    .confirm-button {
        display: block;
        width: 100%;
        padding: 1rem;
        background: #df1518;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .confirm-button:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .confirm-button:not(:disabled):hover {
        background: #c61216;
        transform: translateY(-1px);
    }

    .confirm-button.bg-success {
        background: #28a745;
    }

    .confirm-button.bg-success:hover {
        background: #218838;
    }

    /* Animación de carga */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .fa-spin {
        animation: spin 1s linear infinite;
    }

    .back-button {
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 1000;
        background: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    .back-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    }

    /* Estilos mejorados para la vista de resumen */
    .order-summary {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #f8f9fa;
        z-index: 2000;
        padding: 0;
        display: none;
        overflow-y: auto;
    }

    /* Mostrar resumen cuando se confirma la ubicación */
    body.show-summary .order-summary {
        display: block !important;
    }

    body.show-summary #map,
    body.show-summary .confirm-location-bar {
        display: none !important;
    }

    .summary-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem 1rem;
        background: white;
        min-height: 100vh;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
    }

    .summary-header {
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .summary-header h2 {
        font-size: 1.5rem;
        color: #333;
        margin: 0;
    }

    .summary-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .summary-section h4 {
        color: #333;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .summary-address {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
    }

    .summary-address p {
        margin: 0;
        color: #666;
        display: flex;
        align-items: start;
        gap: 0.5rem;
    }

    .summary-address i {
        margin-top: 0.2rem;
    }

    .summary-items {
        margin-bottom: 2rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid #eee;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-item-details {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .summary-item-quantity {
        background: #f0f0f0;
        padding: 0.2rem 0.6rem;
        border-radius: 15px;
        font-size: 0.9rem;
        color: #666;
    }

    .summary-item-name {
        color: #333;
    }

    .summary-item-price {
        font-weight: 500;
        color: #333;
    }

    .summary-total {
        background: #f8f9fa;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        font-size: 1.2rem;
        font-weight: bold;
        text-align: right;
        margin: 1.5rem 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .summary-total-label {
        color: #666;
        font-weight: normal;
    }

    .summary-total-amount {
        color: #df1518;
    }

    .whatsapp-button {
        display: flex;
        width: 100%;
        padding: 1.2rem;
        background: #25D366;
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        margin-top: 2rem;
        justify-content: center;
        align-items: center;
        gap: 0.8rem;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.2);
    }

    .whatsapp-button:disabled {
        background: #ccc;
        box-shadow: none;
    }

    .whatsapp-button:not(:disabled):hover {
        background: #128C7E;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 211, 102, 0.3);
    }

    .whatsapp-button:not(:disabled):active {
        transform: translateY(0);
    }

    .whatsapp-button i {
        font-size: 1.3rem;
    }

    /* Animación de entrada */
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    body.show-summary .summary-container {
        animation: slideUp 0.3s ease-out;
    }
</style>
@endpush

@section('content')
<!-- Botón de regresar -->
<a href="{{ route('products.index') }}" class="back-button">
    <i class="fas fa-arrow-left"></i>
</a>

<!-- Mapa -->
<div id="map"></div>

<!-- Barra de confirmación -->
<div class="confirm-location-bar">
    <div class="location-address" id="selectedAddress">
        <i class="fas fa-map-marker-alt text-danger me-2"></i>
        <span>Selecciona un punto en el mapa</span>
    </div>
    <button class="confirm-button" id="confirmLocation" disabled>
        Confirmar ubicación
    </button>
</div>

<!-- Vista de resumen del pedido -->
<div class="order-summary">
    <div class="summary-container">
        <div class="summary-header">
            <h2>Resumen del Pedido</h2>
        </div>

        <div class="summary-section">
            <h4><i class="fas fa-map-marker-alt text-danger"></i> Dirección de entrega:</h4>
            <div class="summary-address">
                <p id="summaryAddress"></p>
            </div>
        </div>

        <div class="summary-section">
            <h4><i class="fas fa-shopping-bag text-primary"></i> Productos:</h4>
            <div class="summary-items">
                @foreach(session('cart', []) as $id => $details)
                    <div class="summary-item">
                        <div class="summary-item-details">
                            <span class="summary-item-quantity">{{ $details['quantity'] }}</span>
                            <span class="summary-item-name">{{ $details['name'] }}</span>
                        </div>
                        <span class="summary-item-price">Bs. {{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="summary-total">
            <span class="summary-total-label">Total:</span>
            <span class="summary-total-amount">Bs. {{ number_format(collect(session('cart', []))->sum(function($item) { 
                return $item['price'] * $item['quantity']; 
            }), 2) }}</span>
        </div>

        <button class="whatsapp-button" id="sendToWhatsApp" disabled>
            <i class="fab fa-whatsapp"></i>Enviar pedido por WhatsApp
        </button>
    </div>
</div>

<input type="hidden" id="selected_lat">
<input type="hidden" id="selected_lng">
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
<script>
let map;
let marker;
let geocoder;

function initMap() {
    // Coordenadas iniciales (puedes ajustarlas a tu ubicación)
    const initialPosition = { lat: -17.783333, lng: -63.182778 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: initialPosition,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false
    });
    
    geocoder = new google.maps.Geocoder();
    
    // Solicitar ubicación actual
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                map.setCenter(pos);
                placeMarker(pos);
            },
            () => {
                placeMarker(initialPosition);
            }
        );
    } else {
        placeMarker(initialPosition);
    }
    
    map.addListener('click', (e) => {
        placeMarker(e.latLng);
    });
}

function placeMarker(position) {
    if (marker) {
        marker.setMap(null);
    }
    
    marker = new google.maps.Marker({
        position: position,
        map: map,
        animation: google.maps.Animation.DROP
    });
    
    $('#selected_lat').val(position.lat);
    $('#selected_lng').val(position.lng);
    
    // Mostrar indicador de carga
    $('#selectedAddress').html('<i class="fas fa-spinner fa-spin me-2"></i>Obteniendo dirección...');
    $('#confirmLocation').prop('disabled', true);
    
    geocoder.geocode({ location: position }, (results, status) => {
        if (status === 'OK' && results[0]) {
            const address = results[0].formatted_address;
            $('#selectedAddress').html(`<i class="fas fa-map-marker-alt text-danger me-2"></i>${address}`);
            $('#confirmLocation').prop('disabled', false);
        } else {
            $('#selectedAddress').html('<i class="fas fa-exclamation-triangle text-warning me-2"></i>No se pudo obtener la dirección');
            $('#confirmLocation').prop('disabled', true);
        }
    });
}

$(document).ready(function() {
    initMap();
    
    $('#confirmLocation').click(function() {
        const $button = $(this);
        const address = $('#selectedAddress').text();
        
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Confirmando...');
        
        // Actualizar la dirección en el resumen
        $('#summaryAddress').html(`<i class="fas fa-map-marker-alt text-danger me-2"></i>${address}`);
        
        // Mostrar la vista de resumen
        setTimeout(() => {
            document.body.classList.add('show-summary');
            $('#sendToWhatsApp').prop('disabled', false);
            $button.html('¡Ubicación confirmada!').addClass('bg-success');
        }, 500);
    });
    
    $('#sendToWhatsApp').click(function() {
        const $button = $(this);
        const address = $('#selectedAddress').text();
        const lat = $('#selected_lat').val();
        const lng = $('#selected_lng').val();
        
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Generando enlace...');
        
        $.ajax({
            url: '{{ route("generate.whatsapp.link") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            data: JSON.stringify({
                address: address,
                lat: lat,
                lng: lng
            }),
            success: function(response) {
                window.location.href = response.whatsappLink;
            },
            error: function() {
                $button.prop('disabled', false)
                    .html('Error al generar enlace. Intentar de nuevo')
                    .removeClass('btn-success')
                    .addClass('btn-danger');
            }
        });
    });
});
</script>
@endpush

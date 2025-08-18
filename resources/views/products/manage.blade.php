@extends('layouts.app')

@section('title', 'Gestión de Productos')
@section('hideCart')

@push('styles')
<style>
    .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    .promotion-badge {
        font-size: 0.8rem;
        padding: 0.2rem 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="brand-font">Gestión de Productos</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Producto
            </a>
            <a href="{{ route('style.edit') }}" class="btn btn-secondary">
                <i class="fas fa-paint-brush me-2"></i>Editar estilo
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th>Promoción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ asset($product->image_url) }}" 
                                     alt="{{ $product->name }}" 
                                     class="product-image">
                            </td>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                            </td>
                            <td>
                                <strong>Bs {{ number_format($product->price, 2) }}</strong>
                                @if($product->is_promotion)
                                    <br>
                                    <small class="text-muted text-decoration-line-through">
                                        Bs {{ number_format($product->original_price, 2) }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($product->is_promotion)
                                    <span class="badge bg-danger promotion-badge">
                                        {{ $product->promotion_badge ?? '¡En Promoción!' }}
                                    </span>
                                    @if($product->promotion_ends_at)
                                        <br>
                                        <small class="text-muted">
                                            Hasta: {{ \Carbon\Carbon::parse($product->promotion_ends_at)->format('d/m/Y') }}
                                        </small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmDelete({{ $product->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $product->id }}" 
                                      action="{{ route('products.destroy', $product) }}" 
                                      method="POST" 
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(productId) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        document.getElementById('delete-form-' + productId).submit();
    }
}
</script>
@endpush

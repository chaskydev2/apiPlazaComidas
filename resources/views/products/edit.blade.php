@extends('layouts.app')

@section('title', 'Editar Producto')
@section('hideCart')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="brand-font">Editar Producto</h1>
        <a href="{{ route('products.manage') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    @if($product->image_url)
        <div class="text-center mb-4">
            <img src="{{ asset($product->image_url) }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid rounded" 
                 style="max-height: 200px;">
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @include('components.products.form', ['product' => $product])

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Actualizar Producto
            </button>
        </div>
    </form>
</div>
@endsection

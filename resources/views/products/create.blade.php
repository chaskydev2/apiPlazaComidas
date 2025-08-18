@extends('layouts.app')

@section('title', 'Crear Producto')
@section('hideCart')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="brand-font">Crear Nuevo Producto</h1>
        <a href="{{ route('products.manage') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        @include('components.products.form', ['product' => null])

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Guardar Producto
            </button>
        </div>
    </form>
</div>
@endsection

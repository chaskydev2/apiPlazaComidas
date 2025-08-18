@extends('layouts.app')

@section('title', 'Editar Estilo')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Editar Estilo del Navbar</div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('style.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label for="navbar_bg" class="form-label">Color de fondo del navbar</label>
                        <input type="color" class="form-control form-control-color" id="navbar_bg" name="navbar_bg" value="{{ old('navbar_bg', $navbar_bg) }}" title="Elige un color">
                    </div>
                    <div class="mb-3">
                        <label for="navbar_logo" class="form-label">Logo actual</label><br>
                        <img src="{{ asset($navbar_logo) }}" alt="Logo actual" style="height: 60px;">
                    </div>
                    <div class="mb-3">
                        <label for="navbar_logo" class="form-label">Cambiar logo (opcional)</label>
                        <input class="form-control" type="file" id="navbar_logo" name="navbar_logo" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label for="menu_title" class="form-label">Título de la sección de menú</label>
                        <input type="text" class="form-control" id="menu_title" name="menu_title" value="{{ old('menu_title', $menu_title ?? config('ui.menu_title')) }}" maxlength="100">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

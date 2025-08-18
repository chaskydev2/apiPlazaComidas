@props(['product' => null])

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Producto</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $product?->name) }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              required>{{ old('description', $product?->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       step="0.01" 
                                       value="{{ old('price', $product?->price) }}" 
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/jpeg,image/png,image/jpg" 
                                   {{ $product ? '' : 'required' }}>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="is_promotion" 
                                   name="is_promotion" 
                                   {{ old('is_promotion', $product?->is_promotion) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_promotion">¿Es una promoción?</label>
                        </div>
                    </div>
                    <div class="card-body promotion-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="original_price" class="form-label">Precio Original</label>
                            <div class="input-group">
                                <span class="input-group-text">Bs</span>
                                <input type="number" 
                                       class="form-control @error('original_price') is-invalid @enderror" 
                                       id="original_price" 
                                       name="original_price" 
                                       step="0.01" 
                                       value="{{ old('original_price', $product?->original_price) }}">
                                @error('original_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="promotion_ends_at" class="form-label">Fecha de Fin de Promoción</label>
                            <input type="date" 
                                   class="form-control @error('promotion_ends_at') is-invalid @enderror" 
                                   id="promotion_ends_at" 
                                   name="promotion_ends_at" 
                                   value="{{ old('promotion_ends_at', $product?->promotion_ends_at ? \Carbon\Carbon::parse($product?->promotion_ends_at)->format('Y-m-d') : '') }}">
                            @error('promotion_ends_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="promotion_badge" class="form-label">Texto del Badge</label>
                            <input type="text" 
                                   class="form-control @error('promotion_badge') is-invalid @enderror" 
                                   id="promotion_badge" 
                                   name="promotion_badge" 
                                   value="{{ old('promotion_badge', $product?->promotion_badge) }}" 
                                   placeholder="¡OFERTA ESPECIAL!">
                            @error('promotion_badge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    function togglePromotionFields() {
        $('.promotion-fields').toggle($('#is_promotion').is(':checked'));
    }

    $('#is_promotion').change(togglePromotionFields);
    togglePromotionFields();
});
</script>
@endpush

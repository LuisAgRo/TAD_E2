@extends('layouts.app')

@section('content')
<h2 class="mb-4">Editar producto</h2>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('price')<div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('stock')<div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('sku')<div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('category_id')<div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('image')<div class="alert alert-danger">{{ $message }}</div>@enderror

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="row">
        <div class="col mb-3">
            <label class="form-label">Precio (€)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}">
        </div>
        <div class="col mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Categoría</label>
        <select name="category_id" class="form-select">
            <option value="">Selecciona una categoría</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Imagen</label>
        @if($product->image)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $product->image) }}" class="product-edit-preview">
                <div class="form-text">Imagen actual. Sube una nueva para reemplazarla.</div>
            </div>
        @endif
        <input type="file" name="image" class="form-control" accept="image/*">
        <div class="form-text">Máximo 2MB. Formatos: jpg, png, webp.</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Activo</label>
        <select name="active" class="form-select">
            <option value="1" {{ $product->active ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ !$product->active ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <button type="submit" class="btn btn-danger">Actualizar producto</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

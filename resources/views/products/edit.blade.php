@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Product</h1>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $product->category) }}" list="cat-list">
                    <datalist id="cat-list">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Price ($) *</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                    @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Quantity *</label>
                    <input type="number" name="qty" class="form-control" value="{{ old('qty', $product->qty) }}" min="0" required>
                    @error('qty') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Product Image</label>
                @if($product->image)
                    <div style="margin-bottom:8px;">
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="max-height:120px;border-radius:8px;border:1px solid var(--border);">
                        <p style="font-size:12px;color:var(--muted);margin-top:4px;">Current image · Upload a new one to replace it</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImg(event)">
                <img id="imgPreview" src="#" alt="preview" style="display:none;margin-top:10px;max-height:140px;border-radius:8px;border:1px solid var(--border);">
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:var(--accent);">
                <label for="is_active" style="font-weight:500;cursor:pointer;">Active</label>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImg(e) {
    const file = e.target.files[0];
    if (!file) return;
    const preview = document.getElementById('imgPreview');
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}
</script>
@endpush

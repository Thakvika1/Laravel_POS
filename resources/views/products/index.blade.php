@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div class="page-header">
    <h1 class="page-title">Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

{{-- Search & Filter --}}
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Search products…" value="{{ request('search') }}" style="max-width:280px;">
            <select name="category" class="form-control" style="max-width:180px;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Search</button>
            @if(request()->anyFilled(['search','category']))
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Product Grid --}}
@if($products->isEmpty())
    <div class="card">
        <div class="card-body" style="text-align:center;padding:48px;color:var(--muted);">
            <div style="font-size:40px;margin-bottom:12px;">📦</div>
            <p style="font-size:16px;font-weight:600;">No products found</p>
            <p style="margin-top:6px;">Add your first product to get started.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top:16px;">+ Add Product</a>
        </div>
    </div>
@else
    <div class="grid grid-4">
        @foreach($products as $product)
        <div class="product-card">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
            @else
                <div style="height:160px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:40px;">🛍️</div>
            @endif
            <div class="pc-body">
                <div class="pc-name" title="{{ $product->name }}">{{ $product->name }}</div>
                <div class="pc-price">${{ number_format($product->price, 2) }}</div>
                <div class="pc-qty">
                    @if($product->qty > 0)
                        <span class="badge badge-green">{{ $product->qty }} in stock</span>
                    @else
                        <span class="badge badge-red">Out of stock</span>
                    @endif
                    @if($product->category)
                        <span class="badge badge-purple" style="margin-left:4px;">{{ $product->category }}</span>
                    @endif
                </div>
            </div>
            <div class="pc-footer">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-secondary btn-sm">Edit</a>
                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $products->links() }}
    </div>
@endif
@endsection

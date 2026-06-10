<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureAuthenticated;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureAuthenticated::class);
    }

    public function index(Request $request)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('category', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $categories = Product::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('products.index', compact('products', 'categories'));
    }

    public function recent(Request $request)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('category', 'like', "%{$request->search}%");
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->take(12)->get();

        return response()->json([
            'products' => $products->map(function (Product $product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $product->qty,
                    'category' => $product->category,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'is_active' => $product->is_active,
                ];
            }),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $categories = Product::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'qty'         => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($validated);

        Notification::create([
            'type' => 'product_created',
            'message' => 'New product created: ' . $product->name,
            'data' => ['product_id' => $product->id, 'product_name' => $product->name],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $categories = Product::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'qty'         => 'required|integer|min:0',
            'category'    => 'nullable|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        if ($product->orderItems()->exists()) {
            return redirect()->route('products.index')->with('error', 'Product cannot be deleted because it has associated orders.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}

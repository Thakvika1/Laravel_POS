<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->search) {
            $query->where('order_number', 'like', "%{$request->search}%")
                ->orWhere('customer_name', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->where('qty', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'nullable|string|max:255',
            'customer_email'   => 'nullable|email|max:255',
            'customer_phone'   => 'nullable|string|max:20',
            'payment_method'   => 'required|in:cash,card,transfer',
            'discount'         => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
            'items'            => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->qty < $item['quantity']) {
                    abort(422, "Insufficient stock for {$product->name}");
                }

                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'unit_price'   => $product->price,
                    'quantity'     => $item['quantity'],
                    'subtotal'     => $lineTotal,
                ];

                $product->decrement('qty', $item['quantity']);
            }

            $tax      = round($subtotal * 0.10, 2);
            $discount = (float) ($request->discount ?? 0);
            $total    = $subtotal + $tax - $discount;

            $order = Order::create([
                'order_number'   => Order::generateOrderNumber(),
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => $discount,
                'total'          => $total,
                'status'         => 'completed',
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
            ]);

            foreach ($itemsData as $data) {
                $data['order_id'] = $order->id;
                OrderItem::create($data);
            }

            session(['last_order_id' => $order->id]);
        });

        return redirect()->route('orders.show', session('last_order_id'))
            ->with('success', 'Order created successfully!');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,completed,cancelled']);

        if ($order->status === 'cancelled' && $request->status !== 'cancelled') {
            // Restore stock if un-cancelling
            foreach ($order->items as $item) {
                $item->product?->increment('qty', $item->quantity);
            }
        }

        if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
            // Restore stock on cancellation
            foreach ($order->items as $item) {
                $item->product?->increment('qty', $item->quantity);
            }
        }

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product?->increment('qty', $item->quantity);
            }

            $order->items()->delete();
            $order->delete();
        });

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function invoice(Order $order)
    {
        $order->load('items');
        return view('orders.invoice', compact('order'));
    }
}

@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $order->order_number }}</h1>
            <p style="color:var(--muted);margin-top:4px;">{{ $order->created_at->format('F d, Y · H:i') }}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('orders.invoice', $order) }}" class="btn btn-primary" target="_blank">🧾 Print Invoice</a>
            <form method="POST" action="{{ route('orders.destroy', $order) }}"
                onsubmit="return confirm('Delete this order?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Delete Order</button>
            </form>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </div>

    <div class="grid grid-2" style="gap:20px;">
        {{-- Order Info --}}
        <div>
            <div class="card mb-4">
                <div class="card-header">Customer & Payment</div>
                <div class="card-body">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="padding:6px 0;color:var(--muted);width:140px;">Customer</td>
                            <td style="font-weight:500;">{{ $order->customer_name ?? 'Walk-in' }}</td>
                        </tr>
                        @if ($order->customer_email)
                            <tr>
                                <td style="padding:6px 0;color:var(--muted);">Email</td>
                                <td>{{ $order->customer_email }}</td>
                            </tr>
                        @endif
                        @if ($order->customer_phone)
                            <tr>
                                <td style="padding:6px 0;color:var(--muted);">Phone</td>
                                <td>{{ $order->customer_phone }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="padding:6px 0;color:var(--muted);">Payment</td>
                            <td style="text-transform:capitalize;font-weight:500;">{{ $order->payment_method }}</td>
                        </tr>
                        @if ($order->notes)
                            <tr>
                                <td style="padding:6px 0;color:var(--muted);">Notes</td>
                                <td>{{ $order->notes }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Status</div>
                <div class="card-body">
                    @php
                        $badges = [
                            'completed' => 'badge-green',
                            'pending' => 'badge-amber',
                            'cancelled' => 'badge-red',
                        ];
                    @endphp
                    <p>Current: <span class="badge {{ $badges[$order->status] ?? '' }}"
                            style="font-size:13px;">{{ ucfirst($order->status) }}</span></p>
                    <hr class="divider">
                    <form method="POST" action="{{ route('orders.status', $order) }}"
                        style="display:flex;gap:8px;align-items:center;">
                        @csrf @method('PATCH')
                        <select name="status" class="form-control">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Items & Totals --}}
        <div>
            <div class="card">
                <div class="card-header">Order Items</div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th style="text-align:right;">Price</th>
                                <th style="text-align:right;">Qty</th>
                                <th style="text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td style="font-weight:500;">{{ $item->product_name }}</td>
                                    <td class="mono" style="text-align:right;color:var(--muted);">
                                        ${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="mono" style="text-align:right;">{{ $item->quantity }}</td>
                                    <td class="mono" style="text-align:right;font-weight:600;">
                                        ${{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="padding:16px;border-top:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:4px;">
                        <span>Subtotal</span><span class="mono">${{ number_format($order->subtotal, 2) }}</span></div>
                    <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:4px;"><span>Tax
                            (10%)</span><span class="mono">${{ number_format($order->tax, 2) }}</span></div>
                    @if ($order->discount > 0)
                        <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:4px;">
                            <span>Discount</span><span class="mono">-${{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div
                        style="display:flex;justify-content:space-between;font-weight:700;font-size:16px;margin-top:8px;padding-top:8px;border-top:1px solid var(--border);">
                        <span>Total</span><span class="mono"
                            style="color:var(--accent);">${{ number_format($order->total, 2) }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection

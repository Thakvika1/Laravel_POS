@extends('layouts.app')
@section('title', 'Orders')

@section('content')
<div class="page-header">
    <h1 class="page-title">Orders</h1>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">+ New Order</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Order # or customer…" value="{{ request('search') }}" style="max-width:260px;">
            <select name="status" class="form-control" style="max-width:160px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-secondary">Search</button>
            @if(request()->anyFilled(['search','status']))
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="mono" style="font-weight:600;color:var(--accent);">{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name ?? '—' }}</td>
                    <td style="color:var(--muted);">{{ $order->items->count() }} item(s)</td>
                    <td class="mono" style="font-weight:600;">${{ number_format($order->total, 2) }}</td>
                    <td style="text-transform:capitalize;">{{ $order->payment_method }}</td>
                    <td>
                        @php
                            $badges = ['completed' => 'badge-green', 'pending' => 'badge-amber', 'cancelled' => 'badge-red'];
                        @endphp
                        <span class="badge {{ $badges[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td style="color:var(--muted);font-size:12.5px;">{{ $order->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary btn-sm">View</a>
                            <a href="{{ route('orders.invoice', $order) }}" class="btn btn-secondary btn-sm" target="_blank">🧾</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:32px;">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination">{{ $orders->links() }}</div>
@endsection

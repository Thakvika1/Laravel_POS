<?php $__env->startSection('title', 'Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1 class="page-title">Orders</h1>
    <a href="<?php echo e(route('orders.create')); ?>" class="btn btn-primary">+ New Order</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Order # or customer…" value="<?php echo e(request('search')); ?>" style="max-width:260px;">
            <select name="status" class="form-control" style="max-width:160px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending"   <?php echo e(request('status') === 'pending'   ? 'selected' : ''); ?>>Pending</option>
                <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-secondary">Search</button>
            <?php if(request()->anyFilled(['search','status'])): ?>
                <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
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
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="mono" style="font-weight:600;color:var(--accent);"><?php echo e($order->order_number); ?></td>
                    <td><?php echo e($order->customer_name ?? '—'); ?></td>
                    <td style="color:var(--muted);"><?php echo e($order->items->count()); ?> item(s)</td>
                    <td class="mono" style="font-weight:600;">$<?php echo e(number_format($order->total, 2)); ?></td>
                    <td style="text-transform:capitalize;"><?php echo e($order->payment_method); ?></td>
                    <td>
                        <?php
                            $badges = ['completed' => 'badge-green', 'pending' => 'badge-amber', 'cancelled' => 'badge-red'];
                        ?>
                        <span class="badge <?php echo e($badges[$order->status] ?? ''); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                    </td>
                    <td style="color:var(--muted);font-size:12.5px;"><?php echo e($order->created_at->format('M d, Y H:i')); ?></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-secondary btn-sm">View</a>
                            <a href="<?php echo e(route('orders.invoice', $order)); ?>" class="btn btn-secondary btn-sm" target="_blank">🧾</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:32px;">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="pagination"><?php echo e($orders->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/orders/index.blade.php ENDPATH**/ ?>
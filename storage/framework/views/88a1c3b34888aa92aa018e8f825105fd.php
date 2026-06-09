<?php $__env->startSection('title', 'Order ' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <div>
            <h1 class="page-title"><?php echo e($order->order_number); ?></h1>
            <p style="color:var(--muted);margin-top:4px;"><?php echo e($order->created_at->format('F d, Y · H:i')); ?></p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="<?php echo e(route('orders.invoice', $order)); ?>" class="btn btn-primary" target="_blank">🧾 Print Invoice</a>
            <form method="POST" action="<?php echo e(route('orders.destroy', $order)); ?>"
                onsubmit="return confirm('Delete this order?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger">🗑️ Delete Order</button>
            </form>
            <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-secondary">← Back</a>
        </div>
    </div>

    <div class="grid grid-2" style="gap:20px;">
        
        <div>
            <div class="card mb-4">
                <div class="card-header">Customer & Payment</div>
                <div class="card-body">
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td style="padding:6px 0;color:var(--muted);width:140px;">Customer</td>
                            <td style="font-weight:500;"><?php echo e($order->customer_name ?? 'Walk-in'); ?></td>
                        </tr>
                        <?php if($order->customer_email): ?>
                            <tr>
                                <td style="padding:6px 0;color:var(--muted);">Email</td>
                                <td><?php echo e($order->customer_email); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if($order->customer_phone): ?>
                            <tr>
                                <td style="padding:6px 0;color:var(--muted);">Phone</td>
                                <td><?php echo e($order->customer_phone); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="padding:6px 0;color:var(--muted);">Payment</td>
                            <td style="text-transform:capitalize;font-weight:500;"><?php echo e($order->payment_method); ?></td>
                        </tr>
                        <?php if($order->notes): ?>
                            <tr>
                                <td style="padding:6px 0;color:var(--muted);">Notes</td>
                                <td><?php echo e($order->notes); ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Status</div>
                <div class="card-body">
                    <?php
                        $badges = [
                            'completed' => 'badge-green',
                            'pending' => 'badge-amber',
                            'cancelled' => 'badge-red',
                        ];
                    ?>
                    <p>Current: <span class="badge <?php echo e($badges[$order->status] ?? ''); ?>"
                            style="font-size:13px;"><?php echo e(ucfirst($order->status)); ?></span></p>
                    <hr class="divider">
                    <form method="POST" action="<?php echo e(route('orders.status', $order)); ?>"
                        style="display:flex;gap:8px;align-items:center;">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <select name="status" class="form-control">
                            <option value="pending" <?php echo e($order->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                            <option value="completed" <?php echo e($order->status === 'completed' ? 'selected' : ''); ?>>Completed
                            </option>
                            <option value="cancelled" <?php echo e($order->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled
                            </option>
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                </div>
            </div>
        </div>

        
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
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="font-weight:500;"><?php echo e($item->product_name); ?></td>
                                    <td class="mono" style="text-align:right;color:var(--muted);">
                                        $<?php echo e(number_format($item->unit_price, 2)); ?></td>
                                    <td class="mono" style="text-align:right;"><?php echo e($item->quantity); ?></td>
                                    <td class="mono" style="text-align:right;font-weight:600;">
                                        $<?php echo e(number_format($item->subtotal, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div style="padding:16px;border-top:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:4px;">
                        <span>Subtotal</span><span class="mono">$<?php echo e(number_format($order->subtotal, 2)); ?></span></div>
                    <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:4px;"><span>Tax
                            (10%)</span><span class="mono">$<?php echo e(number_format($order->tax, 2)); ?></span></div>
                    <?php if($order->discount > 0): ?>
                        <div style="display:flex;justify-content:space-between;color:var(--muted);margin-bottom:4px;">
                            <span>Discount</span><span class="mono">-$<?php echo e(number_format($order->discount, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <div
                        style="display:flex;justify-content:space-between;font-weight:700;font-size:16px;margin-top:8px;padding-top:8px;border-top:1px solid var(--border);">
                        <span>Total</span><span class="mono"
                            style="color:var(--accent);">$<?php echo e(number_format($order->total, 2)); ?></span></div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/orders/show.blade.php ENDPATH**/ ?>
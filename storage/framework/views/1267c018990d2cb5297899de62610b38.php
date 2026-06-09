<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?php echo e($order->order_number); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: #1a1a2e;
            background: #f0f2f8;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 32px 16px;
        }

        .invoice {
            background: #fff;
            width: 680px;
            border-radius: 12px;
            box-shadow: 0 8px 40px rgba(0,0,0,.12);
            overflow: hidden;
        }

        /* Header */
        .inv-header {
            background: linear-gradient(135deg, #6c63ff 0%, #4c46d6 100%);
            padding: 36px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            color: #fff;
        }

        .inv-brand { font-size: 28px; font-weight: 800; letter-spacing: -1px; }
        .inv-brand span { opacity: .65; }
        .inv-tagline { font-size: 11px; opacity: .7; margin-top: 3px; letter-spacing: .5px; }

        .inv-meta { text-align: right; }
        .inv-meta h2 { font-size: 13px; font-weight: 600; opacity: .7; letter-spacing: 2px; text-transform: uppercase; }
        .inv-meta .inv-number { font-size: 22px; font-weight: 700; margin-top: 4px; }
        .inv-meta .inv-date   { font-size: 12px; opacity: .7; margin-top: 4px; }

        /* Status ribbon */
        .inv-status {
            padding: 8px 40px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .status-completed { background: #f0fdf4; color: #16a34a; border-bottom: 1px solid #dcfce7; }
        .status-pending   { background: #fffbeb; color: #d97706; border-bottom: 1px solid #fef3c7; }
        .status-cancelled { background: #fef2f2; color: #dc2626; border-bottom: 1px solid #fee2e2; }

        /* Body */
        .inv-body { padding: 36px 40px; }

        /* Customer / Payment */
        .inv-details { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 32px; }
        .detail-block h4 { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; margin-bottom: 10px; }
        .detail-block p  { font-size: 13px; line-height: 1.6; color: #374151; }
        .detail-block strong { color: #111827; }

        /* Items table */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .items-table thead th {
            background: #f9fafb;
            padding: 10px 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        .items-table thead th:last-child { text-align: right; }
        .items-table thead th:nth-child(2),
        .items-table thead th:nth-child(3) { text-align: right; }

        .items-table tbody td {
            padding: 12px 12px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }
        .items-table tbody td:nth-child(2),
        .items-table tbody td:nth-child(3) { text-align: right; color: #6b7280; }
        .items-table tbody td:last-child    { text-align: right; font-weight: 600; }
        .items-table tbody tr:last-child td { border-bottom: none; }

        /* Totals */
        .inv-totals { padding: 20px 40px; background: #f9fafb; border-top: 1px solid #e5e7eb; }
        .totals-inner { margin-left: auto; width: 260px; }
        .total-row { display: flex; justify-content: space-between; padding: 4px 0; font-size: 13px; color: #6b7280; }
        .total-row.grand { color: #111827; font-size: 16px; font-weight: 800; padding-top: 10px; margin-top: 6px; border-top: 2px solid #6c63ff; }
        .total-row.grand span:last-child { color: #6c63ff; }

        /* Footer */
        .inv-footer {
            padding: 20px 40px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
        }

        /* Print btn */
        .print-bar {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            gap: 10px;
        }
        .print-btn {
            padding: 10px 20px;
            background: #6c63ff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(108,99,255,.4);
        }
        .back-btn {
            padding: 10px 20px;
            background: #fff;
            color: #374151;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice { box-shadow: none; border-radius: 0; width: 100%; }
            .print-bar { display: none !important; }
        }
    </style>
</head>
<body>
<div class="invoice">

    
    <div class="inv-header">
        <div>
            <div class="inv-brand">Swift<span>POS</span></div>
            <div class="inv-tagline">Point of Sale System</div>
        </div>
        <div class="inv-meta">
            <h2>Invoice</h2>
            <div class="inv-number"><?php echo e($order->order_number); ?></div>
            <div class="inv-date"><?php echo e($order->created_at->format('F d, Y')); ?></div>
        </div>
    </div>

    
    <div class="inv-status status-<?php echo e($order->status); ?>">
        ● <?php echo e(ucfirst($order->status)); ?>

    </div>

    
    <div class="inv-body">
        <div class="inv-details">
            <div class="detail-block">
                <h4>Bill To</h4>
                <p>
                    <strong><?php echo e($order->customer_name ?? 'Walk-in Customer'); ?></strong><br>
                    <?php if($order->customer_email): ?> <?php echo e($order->customer_email); ?><br> <?php endif; ?>
                    <?php if($order->customer_phone): ?> <?php echo e($order->customer_phone); ?> <?php endif; ?>
                </p>
            </div>
            <div class="detail-block">
                <h4>Payment Details</h4>
                <p>
                    <strong>Method:</strong> <?php echo e(ucfirst($order->payment_method)); ?><br>
                    <strong>Date:</strong> <?php echo e($order->created_at->format('M d, Y H:i')); ?><br>
                    <strong>Status:</strong> <?php echo e(ucfirst($order->status)); ?>

                </p>
            </div>
        </div>

        
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align:left;">Description</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->product_name); ?></td>
                    <td>$<?php echo e(number_format($item->unit_price, 2)); ?></td>
                    <td><?php echo e($item->quantity); ?></td>
                    <td>$<?php echo e(number_format($item->subtotal, 2)); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    
    <div class="inv-totals">
        <div class="totals-inner">
            <div class="total-row"><span>Subtotal</span><span>$<?php echo e(number_format($order->subtotal, 2)); ?></span></div>
            <div class="total-row"><span>Tax (10%)</span><span>$<?php echo e(number_format($order->tax, 2)); ?></span></div>
            <?php if($order->discount > 0): ?>
            <div class="total-row"><span>Discount</span><span>-$<?php echo e(number_format($order->discount, 2)); ?></span></div>
            <?php endif; ?>
            <div class="total-row grand"><span>Total</span><span>$<?php echo e(number_format($order->total, 2)); ?></span></div>
        </div>
    </div>

    
    <?php if($order->notes): ?>
    <div style="padding:16px 40px;background:#fffbeb;border-top:1px solid #fef3c7;">
        <p style="font-size:12px;color:#92400e;"><strong>Note:</strong> <?php echo e($order->notes); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="inv-footer">
        <p>Thank you for your purchase! · Generated by SwiftPOS on <?php echo e(now()->format('M d, Y')); ?></p>
    </div>
</div>

<div class="print-bar">
    <a href="<?php echo e(route('orders.show', $order)); ?>" class="back-btn">← Back</a>
    <button class="print-btn" onclick="window.print()">🖨️ Print Invoice</button>
</div>
</body>
</html>
<?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/orders/invoice.blade.php ENDPATH**/ ?>
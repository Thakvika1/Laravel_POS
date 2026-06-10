<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'POS System'); ?> — SwiftPOS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg: #0f1117;
            --surface: #1a1d27;
            --surface2: #222535;
            --border: #2d3148;
            --accent: #6c63ff;
            --accent-h: #5b53ee;
            --green: #22c55e;
            --red: #ef4444;
            --amber: #f59e0b;
            --text: #e2e4ef;
            --muted: #7b7f9e;
            --radius: 10px;
            --font: 'Inter', sans-serif;
            --mono: 'JetBrains Mono', monospace;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font);
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAV ─────────────────────────────── */
        nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            gap: 0;
            height: 56px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            font-weight: 700;
            font-size: 18px;
            color: var(--accent);
            letter-spacing: -0.5px;
            margin-right: auto;
            text-decoration: none;
        }

        .nav-brand span {
            color: var(--text);
        }

        .nav-links {
            display: flex;
            gap: 2px;
        }

        .nav-link {
            padding: 6px 14px;
            border-radius: 6px;
            color: var(--muted);
            text-decoration: none;
            font-weight: 500;
            transition: all .15s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--text);
            background: var(--surface2);
        }

        /* ── MAIN ────────────────────────────── */
        main {
            flex: 1;
            padding: 28px 24px;
            max-width: 1280px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── ALERTS ──────────────────────────── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            font-size: 13.5px;
            font-weight: 500;
            border: 1px solid;
        }

        .alert-success {
            background: #052e16;
            border-color: #166534;
            color: var(--green);
        }

        .alert-error {
            background: #2d0f0f;
            border-color: #7f1d1d;
            color: var(--red);
        }

        .notification-list {
            display: grid;
            gap: 8px;
        }

        .notification-item {
            padding: 10px 12px;
            border-radius: 8px;
            background: var(--surface2);
            border: 1px solid var(--border);
        }

        .notification-item strong {
            display: block;
            margin-bottom: 2px;
        }

        .notification-item small {
            color: var(--muted);
        }

        /* ── PAGE HEADER ─────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
        }

        /* ── BUTTONS ─────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 7px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .15s;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--accent-h);
        }

        .btn-secondary {
            background: var(--surface2);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-danger {
            background: #450a0a;
            color: var(--red);
            border: 1px solid #7f1d1d;
        }

        .btn-danger:hover {
            background: var(--red);
            color: #fff;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12.5px;
            border-radius: 5px;
        }

        .btn-success {
            background: #052e16;
            color: var(--green);
            border: 1px solid #166534;
        }

        .btn-success:hover {
            background: var(--green);
            color: #fff;
        }

        /* ── CARDS ───────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .card-body {
            padding: 20px;
        }

        .card-header {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
        }

        /* ── FORMS ───────────────────────────── */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--text);
            padding: 9px 12px;
            font-size: 14px;
            font-family: var(--font);
            transition: border-color .15s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--accent);
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        select.form-control option {
            background: var(--surface2);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .invalid-feedback {
            color: var(--red);
            font-size: 12px;
            margin-top: 4px;
        }

        /* ── TABLE ───────────────────────────── */
        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: var(--surface2);
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 10px 14px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, .015);
        }

        /* ── BADGE ───────────────────────────── */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .badge-green {
            background: #052e16;
            color: var(--green);
        }

        .badge-red {
            background: #2d0f0f;
            color: var(--red);
        }

        .badge-amber {
            background: #2d1a00;
            color: var(--amber);
        }

        .badge-purple {
            background: #1a1040;
            color: var(--accent);
        }

        /* ── GRID ────────────────────────────── */
        .grid {
            display: grid;
        }

        .grid-2 {
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .grid-3 {
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .grid-4 {
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        /* ── PRODUCT CARD ────────────────────── */
        .product-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: border-color .15s, transform .1s;
            cursor: pointer;
        }

        .product-card:hover {
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .product-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
            background: var(--surface2);
        }

        .product-card .pc-body {
            padding: 12px;
        }

        .product-card .pc-name {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-card .pc-price {
            color: var(--accent);
            font-family: var(--mono);
            font-size: 15px;
            font-weight: 700;
        }

        .product-card .pc-qty {
            color: var(--muted);
            font-size: 11.5px;
            margin-top: 2px;
        }

        .product-card .pc-footer {
            display: flex;
            gap: 6px;
            padding: 10px 12px;
            border-top: 1px solid var(--border);
        }

        .product-card .pc-footer .btn {
            flex: 1;
            justify-content: center;
        }

        /* ── PAGINATION ──────────────────────── */
        .pagination {
            display: flex;
            gap: 4px;
            margin-top: 20px;
        }

        .pagination a,
        .pagination span {
            padding: 7px 12px;
            border-radius: 6px;
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            font-size: 13px;
        }

        .pagination .active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        /* ── MISC ────────────────────────────── */
        .text-muted {
            color: var(--muted);
        }

        .text-accent {
            color: var(--accent);
        }

        .text-green {
            color: var(--green);
        }

        .text-red {
            color: var(--red);
        }

        .mono {
            font-family: var(--mono);
        }

        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 20px 0;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .gap-2 {
            gap: 8px;
        }

        .gap-3 {
            gap: 12px;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .mb-4 {
            margin-bottom: 16px;
        }

        .fw-600 {
            font-weight: 600;
        }

        .text-right {
            text-align: right;
        }

        .w-100 {
            width: 100%;
        }

        @media (max-width: 768px) {
            .grid-4 {
                grid-template-columns: 1fr 1fr;
            }

            .grid-3 {
                grid-template-columns: 1fr 1fr;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <nav>
        <a href="<?php echo e(route('products.index')); ?>" class="nav-brand">Swift<span>POS</span></a>
        <div class="nav-links">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('products.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                    📦 Products
                </a>
                <a href="<?php echo e(route('orders.create')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('orders.create') ? 'active' : ''); ?>">
                    🛒 New Order
                </a>
                <a href="<?php echo e(route('orders.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('orders.index') ? 'active' : ''); ?>">
                    📋 Orders
                </a>
                <?php if(Auth::user()?->is_admin): ?>
                    <a href="<?php echo e(route('admin.users.index')); ?>"
                        class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                        🔐 Admins
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <div class="nav-links" style="margin-left:12px;">
            <?php if(auth()->guard()->check()): ?>
                <span class="nav-link" style="cursor:default;"><?php echo e(Auth::user()->name); ?></span>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <?php if(auth()->guard()->check()): ?>
            <div class="card mb-4" id="live-updates" style="display:none;">
                <div class="card-header">Live Activity</div>
                <div class="card-body">
                    <div class="notification-list" id="live-updates-list"></div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('success')): ?>
            <div class="alert alert-success">✓ <?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-error">✕ <?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if(auth()->guard()->check()): ?>
        <script>
            (function() {
                const container = document.getElementById('live-updates');
                const list = document.getElementById('live-updates-list');

                if (!container || !list) {
                    return;
                }

                const render = (notifications) => {
                    if (!notifications.length) {
                        container.style.display = 'none';
                        return;
                    }

                    container.style.display = 'block';
                    list.innerHTML = notifications.map((item) => `
            <div class="notification-item">
                <strong>${item.message}</strong>
                <small>${item.created_at}</small>
            </div>
        `).join('');
                };

                const load = () => {
                    fetch('<?php echo e(route('notifications.recent')); ?>', {
                            headers: {
                                Accept: 'application/json'
                            }
                        })
                        .then((response) => response.json())
                        .then((data) => render(data.notifications || []))
                        .catch(() => {});
                };

                load();
                setInterval(load, 5000);
            })
            ();
        </script>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>
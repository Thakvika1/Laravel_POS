<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <h1 class="page-title">Products</h1>
        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">+ Add Product</a>
    </div>

    
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                <input type="text" name="search" class="form-control" placeholder="Search products…"
                    value="<?php echo e(request('search')); ?>" style="max-width:280px;">
                <select name="category" class="form-control" style="max-width:180px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat); ?>" <?php echo e(request('category') === $cat ? 'selected' : ''); ?>>
                            <?php echo e($cat); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn btn-secondary">Search</button>
                <?php if(request()->anyFilled(['search', 'category'])): ?>
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    
    <div id="products-feed">
        <?php if($products->isEmpty()): ?>
            <div class="card">
                <div class="card-body" style="text-align:center;padding:48px;color:var(--muted);">
                    <div style="font-size:40px;margin-bottom:12px;">📦</div>
                    <p style="font-size:16px;font-weight:600;">No products found</p>
                    <p style="margin-top:6px;">Add your first product to get started.</p>
                    <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary" style="margin-top:16px;">+ Add
                        Product</a>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-4">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card">
                        <?php if($product->image): ?>
                            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>">
                        <?php else: ?>
                            <div
                                style="height:160px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:40px;">
                                🛍️</div>
                        <?php endif; ?>
                        <div class="pc-body">
                            <div class="pc-name" title="<?php echo e($product->name); ?>"><?php echo e($product->name); ?></div>
                            <div class="pc-price">$<?php echo e(number_format($product->price, 2)); ?></div>
                            <div class="pc-qty">
                                <?php if($product->qty > 0): ?>
                                    <span class="badge badge-green"><?php echo e($product->qty); ?> in stock</span>
                                <?php else: ?>
                                    <span class="badge badge-red">Out of stock</span>
                                <?php endif; ?>
                                <?php if($product->category): ?>
                                    <span class="badge badge-purple"
                                        style="margin-left:4px;"><?php echo e($product->category); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="pc-footer">
                            <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('products.destroy', $product)); ?>"
                                onsubmit="return confirm('Delete this product?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="pagination">
                <?php echo e($products->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        (function() {
            const container = document.getElementById('products-feed');

            if (!container) {
                return;
            }

            const buildUrl = () => {
                const url = new URL('<?php echo e(route('products.recent')); ?>', window.location.origin);
                const params = new URLSearchParams(window.location.search);

                if (params.get('search')) {
                    url.searchParams.set('search', params.get('search'));
                }

                if (params.get('category')) {
                    url.searchParams.set('category', params.get('category'));
                }

                return url.toString();
            };

            const renderProducts = (products) => {
                if (!products.length) {
                    container.innerHTML = `
                <div class="card">
                    <div class="card-body" style="text-align:center;padding:48px;color:var(--muted);">
                        <div style="font-size:40px;margin-bottom:12px;">📦</div>
                        <p style="font-size:16px;font-weight:600;">No products found</p>
                        <p style="margin-top:6px;">Add your first product to get started.</p>
                        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary" style="margin-top:16px;">+ Add Product</a>
                    </div>
                </div>
            `;
                    return;
                }

                container.innerHTML = `
            <div class="grid grid-4">
                ${products.map((product) => `
                        <div class="product-card">
                            ${product.image ? `<img src="${product.image}" alt="${product.name}">` : `<div style="height:160px;background:var(--surface2);display:flex;align-items:center;justify-content:center;font-size:40px;">🛍️</div>`}
                            <div class="pc-body">
                                <div class="pc-name" title="${product.name}">${product.name}</div>
                                <div class="pc-price">$${Number(product.price).toFixed(2)}</div>
                                <div class="pc-qty">
                                    ${Number(product.qty) > 0
                                        ? `<span class="badge badge-green">${product.qty} in stock</span>`
                                        : `<span class="badge badge-red">Out of stock</span>`}
                                    ${product.category ? `<span class="badge badge-purple" style="margin-left:4px;">${product.category}</span>` : ''}
                                </div>
                            </div>
                            <div class="pc-footer">
                                <a href="/products/${product.id}/edit" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="/products/${product.id}" onsubmit="return confirm('Delete this product?')">
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    `).join('')}
            </div>
        `;
            };

            const loadProducts = () => {
                fetch(buildUrl(), {
                        headers: {
                            Accept: 'application/json'
                        }
                    })
                    .then((response) => response.ok ? response.json() : Promise.reject())
                    .then((data) => renderProducts(data.products || []))
                    .catch(() => {});
            };

            loadProducts();
            setInterval(loadProducts, 5000);
        })();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/products/index.blade.php ENDPATH**/ ?>
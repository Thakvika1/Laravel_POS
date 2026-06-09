<?php $__env->startSection('title', 'Add Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1 class="page-title">Add Product</h1>
    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">← Back</a>
</div>

<div class="card" style="max-width:680px;">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('products.store')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Product Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required placeholder="e.g. Espresso">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="<?php echo e(old('category')); ?>" list="cat-list" placeholder="e.g. Beverages">
                    <datalist id="cat-list">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat); ?>">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                </div>
            </div>

            <div class="grid grid-2">
                <div class="form-group">
                    <label class="form-label">Price ($) *</label>
                    <input type="number" name="price" class="form-control" value="<?php echo e(old('price')); ?>" step="0.01" min="0" required placeholder="0.00">
                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Quantity *</label>
                    <input type="number" name="qty" class="form-control" value="<?php echo e(old('qty', 0)); ?>" min="0" required>
                    <?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" placeholder="Product description…"><?php echo e(old('description')); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" id="imgInput" onchange="previewImg(event)">
                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <img id="imgPreview" src="#" alt="preview" style="display:none;margin-top:10px;max-height:140px;border-radius:8px;border:1px solid var(--border);">
            </div>

            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', 1) ? 'checked' : ''); ?> style="width:16px;height:16px;accent-color:var(--accent);">
                <label for="is_active" style="font-weight:500;cursor:pointer;">Active (visible in POS)</label>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary">Save Product</button>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function previewImg(e) {
    const file = e.target.files[0];
    if (!file) return;
    const preview = document.getElementById('imgPreview');
    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/products/create.blade.php ENDPATH**/ ?>
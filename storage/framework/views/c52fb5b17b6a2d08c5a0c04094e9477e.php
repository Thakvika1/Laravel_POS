

<?php $__env->startSection('title', 'Edit Admin Credentials'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <h1 class="page-title">Edit Admin Credentials</h1>
    </div>

    <div class="card" style="max-width: 560px;">
        <div class="card-header">Update account details</div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>

                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control"
                        value="<?php echo e(old('email', $user->email)); ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <input id="password" name="password" type="password" class="form-control"
                        placeholder="Leave blank to keep the current password">
                    <small class="text-muted">Only fill this if you want to change the password.</small>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/admin/edit-user.blade.php ENDPATH**/ ?>
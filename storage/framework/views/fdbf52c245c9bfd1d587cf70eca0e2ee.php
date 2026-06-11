

<?php $__env->startSection('title', 'Admin Accounts'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header">
        <h1 class="page-title">Admin Accounts</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="flex items-center gap-3">
                <span class="badge badge-purple"><?php echo e($admins->count()); ?> admin<?php echo e($admins->count() === 1 ? '' : 's'); ?></span>
                <span class="text-muted">System admins can force logout or remove other admins from the system.</span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if($admins->isEmpty()): ?>
                <p class="text-muted">No admin accounts found.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($admin->name); ?></td>
                                    <td><?php echo e($admin->email); ?></td>
                                    <td>
                                        <?php if($admin->is_system_admin): ?>
                                            <span class="badge badge-green">System Admin</span>
                                        <?php else: ?>
                                            <span class="badge badge-purple">Admin</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($admin->created_at ? $admin->created_at->format('M d, Y') : '—'); ?></td>
                                    <td>
                                        <div class="flex gap-2">
                                            <?php if(Auth::id() === $admin->id || Auth::user()?->is_system_admin): ?>
                                                <a href="<?php echo e(route('admin.users.edit', $admin)); ?>"
                                                    class="btn btn-secondary btn-sm">Edit</a>
                                            <?php endif; ?>

                                            <?php if(Auth::user()?->is_system_admin && $admin->id !== Auth::id()): ?>
                                                <form method="POST" action="<?php echo e(route('admin.users.logout', $admin)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-secondary btn-sm">Logout</button>
                                                </form>
                                                <form method="POST" action="<?php echo e(route('admin.users.destroy', $admin)); ?>"
                                                    onsubmit="return confirm('Delete this admin account?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            <?php elseif(Auth::id() === $admin->id): ?>
                                                <span class="text-muted">You</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-4" style="max-width:560px;">
        <div class="card-header">Create New Admin</div>
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Admin</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\thakvika\Downloads\pos-laravel\pos-laravel\resources\views/admin/users.blade.php ENDPATH**/ ?>
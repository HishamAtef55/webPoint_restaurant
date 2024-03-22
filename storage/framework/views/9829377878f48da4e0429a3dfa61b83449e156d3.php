<?php
$title = 'Permission';
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Role Management</h2>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-create')): ?>
        <a class="btn btn-success mb-3" href="<?php echo e(route('roles.create')); ?>"> Create New Role</a>
        <?php endif; ?>

        <?php if($message = Session::get('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e($message); ?></p>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(++$i); ?></td>
                        <td><?php echo e($role->name); ?></td>
                        <td>
                            <!-- <a class="btn btn-info" href="<?php echo e(route('roles.show',$role->id)); ?>">Show</a> -->
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-edit')): ?>
                                <a class="text-dark btn px-1" href="<?php echo e(route('roles.edit',$role->id)); ?>">
                                    <i class="far fa-edit fa-lg"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-delete')): ?>
                                <?php echo Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']); ?>

                                <button type="submit" class="btn px-1">
                                    <i class="fas fa-trash fa-lg"></i>
                                </button>
                                <!-- <?php echo Form::submit('Delete', ['class' => 'btn btn-danger']); ?> -->
                                <?php echo Form::close(); ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php echo $roles->render(); ?>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\BackEnd\htdocs\webpoint\resources\views/roles/index.blade.php ENDPATH**/ ?>
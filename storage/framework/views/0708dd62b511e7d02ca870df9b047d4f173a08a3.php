<?php
$title = 'Edit Role';
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Edit Role</h2>
        <div class="row">
            <div class="col-lg-8 offset-lg-2 col-md-12">
                <a class="btn btn-warning mb-2" href="<?php echo e(route('roles.index')); ?>"> Back</a>

                <?php if(count($errors) > 0): ?>
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php echo Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]); ?>

                    <div class="mb-3">
                        <strong>Name:</strong>
                        <?php echo Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')); ?>

                    </div>

                    <div class="mb-3">
                        <strong>Permission:</strong>
                        <br/>
                        <?php $__currentLoopData = $permission; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label><?php echo e(Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name'))); ?>

                                <?php echo e($value->name); ?></label>
                            <br/>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class='col-md-6 offset-md-3'>
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/roles/edit.blade.php ENDPATH**/ ?>
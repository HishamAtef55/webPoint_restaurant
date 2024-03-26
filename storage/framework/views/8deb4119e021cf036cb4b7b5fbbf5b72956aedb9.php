<?php
    $title = 'Welcome';
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class="center" style="background-color: #1b3958;height: calc(100vh);margin-top: -1.5rem;display: flex;align-items: center;justify-content: center">
	<img style="width: 30%; margin: auto;display: block" src="<?php echo e(asset('global/image/logo.png')); ?>"/>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Xampp\htdocs\webpoint\resources\views/control/welcome.blade.php ENDPATH**/ ?>
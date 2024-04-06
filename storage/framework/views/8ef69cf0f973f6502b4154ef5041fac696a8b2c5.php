<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>
        <?php
            if(isset($title))
            {
                echo $title;
            }
            else{
                echo TITLE;
            }
        ?>

    </title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('stock/public/js/app.js')); ?>" defer></script>
    <script src="<?php echo e(asset('stock/js/fontawesome.min.js')); ?>"></script>
    <script src="<?php echo e(asset('stock/js/jquery-3.5.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('stock/js/sweetalert2@10.js')); ?>"></script>
    <script src="<?php echo e(asset('stock/js//main.js')); ?>" defer></script>
    <script src="<?php echo e(asset('stock/js/select2.min.js')); ?>" defer></script>
    <script src="<?php echo e(asset('stock/js/datatables.min.js')); ?>" ></script>
    <script src="<?php echo e(asset('stock/js/vfs_fonts.js')); ?>" ></script>
    <script src="<?php echo e(asset('stock/js/font.js')); ?>" ></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="<?php echo e(asset('stock/css/datatables.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('stock/public/css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('stock/css/fontawesome.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('stock/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('stock/css/style.css')); ?>" rel="stylesheet">
</head>
<body>
    <div id="app">

        <?php echo $__env->make('layouts.stock.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="stock-wrapper">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\web_point\resources\views/layouts/stock/app.blade.php ENDPATH**/ ?>
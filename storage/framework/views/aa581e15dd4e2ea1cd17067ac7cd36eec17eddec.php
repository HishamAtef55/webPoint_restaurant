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
    <link rel="icon" href="<?php echo e(URL::asset('control/images/0440.jpg')); ?>" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Michroma&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(URL::asset('global/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('global/css/all.min.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(URL::asset('global/css/jquery-ui.min.css')); ?>" type="text/css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> -->

    <link rel="stylesheet" href="<?php echo e(URL::asset('menu/css/datatables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('control/css/hc-offcanvas-nav.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('control/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('control/css/search_select.css')); ?>">
    <script src="<?php echo e(URL::asset('global/js/jquery-3.5.1.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(URL::asset('plapla/css/keyboard.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('plapla/css/logoin.css')); ?>">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(URL::asset('global/js/sweetalert2@10.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('menu/js/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('menu/js/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('menu/js/font.js')); ?>"></script>
</head>

<body>
    <div id="app">
        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
    <script src="<?php echo e(URL::asset('global/js/jquery-ui.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('global/js/jquery.tabledit.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('control/js/jquery.ui.touch-punch.min.js')); ?>"></script>

        <script src="<?php echo e(URL::asset('global/js/popper-1.16.0.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('global/js/bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('control/js/hc-offcanvas-nav.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('control/js/main.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('control/js/search_select.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('plapla/js/keyboard.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('plapla/js/login.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\web_point\resources\views/layouts/app.blade.php ENDPATH**/ ?>
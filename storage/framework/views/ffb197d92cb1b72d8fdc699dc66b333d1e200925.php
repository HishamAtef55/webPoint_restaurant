
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"> -->
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo e(URL::asset('global/css/bootstrap.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(URL::asset('global/css/all.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(URL::asset('plapla/css/keyboard.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(URL::asset('control/css/search_select.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(URL::asset('menu/css/datatables.min.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(URL::asset('menu/css/nav.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(URL::asset('menu/css/style.css')); ?>">
        <script src="<?php echo e(URL::asset('global/js/jquery-3.5.1.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('menu/js/datatables.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('menu/js/vfs_fonts.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('menu/js/font.js')); ?>"></script>
        <style>
            .loader {
                color: #ffffff;
                font-size: 90px;
                text-indent: -9999em;
                overflow: hidden;
                width: 1em;
                height: 1em;
                border-radius: 50%;
                margin: 72px auto;
                margin-top: 200px;
                position: relative;
                -webkit-transform: translateZ(0);
                -ms-transform: translateZ(0);
                transform: translateZ(0);
                -webkit-animation: load6 1.7s infinite ease, round 1.7s infinite ease;
                animation: load6 1.7s infinite ease, round 1.7s infinite ease;
            }
            @-webkit-keyframes load6 {
                0% {
                    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
                }
                5%,
                95% {
                    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
                }
                10%,
                59% {
                    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
                }
                20% {
                    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
                }
                38% {
                    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
                }
                100% {
                    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
                }
            }
            @keyframes  load6 {
                0% {
                    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
                }
                5%,
                95% {
                    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
                }
                10%,
                59% {
                    box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
                }
                20% {
                    box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
                }
                38% {
                    box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
                }
                100% {
                    box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
                }
            }
            @-webkit-keyframes round {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }
            @keyframes  round {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }

        </style>
    </head>
    <body>

<?php echo $__env->make('includes.menu.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('content'); ?>
<?php echo $__env->make('includes.menu.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo csrf_field(); ?>
<script>
    $('.delivery').height($(window).outerHeight() - ($('.navbar').outerHeight() + $('footer').outerHeight() ));

    $(window).on('resize', function() {

        $('.delivery').height($(window).outerHeight() - ($('.navbar').outerHeight() + $('footer').outerHeight() ));

    });

    function getStatusHold() {
        $.ajax({
            url:"<?php echo e(route('takeOrderHold')); ?>",
            method:'post',
            data: {
                _token :_token,
            },
            success:function(data) {
                if(data.status == "check"){
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Take Order " + data.order,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "<?php echo e(route('takeOrderHold')); ?>",
                                method: 'post',
                                enctype: "multipart/form-data",
                                data: {
                                    _token  : _token,
                                    order:data.order,
                                    status:true,
                                },
                                success: function (data) {
                                    if(data.status == true){
                                        Swal.fire({
                                            position: 'center-center',
                                            icon: 'success',
                                            title: 'Send To Kitchen Order Hold',
                                            showConfirmButton: false,
                                            timer: 1250
                                        });
                                    }
                                }
                            });
                        }
                    });
                }
                if(data.status == true){
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Send To Kitchen Order Hold',
                        showConfirmButton: false,
                        timer: 1250
                    });
                }
            }
        });
    }

    $(document).ready(function(){
        getStatusHold()
    })


    setInterval(() => {
        getStatusHold()
    }, 30000);


</script>

        <script src="<?php echo e(URL::asset('global/js/popper-1.16.0.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('global/js/bootstrap.min.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('global/js/sweetalert2@10.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('plapla/js/keyboard.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('menu/js/main.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('menu/js/navbar.js')); ?>"></script>
        <script src="<?php echo e(URL::asset('control/js/search_select.js')); ?>"></script>

    </body>
</html>
<?php /**PATH D:\Xampp\htdocs\webpoint\resources\views/layouts/menu.blade.php ENDPATH**/ ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>
            @php
                if(isset($title))
                {
                    echo $title;
                }
                else{
                    echo TITLE;
                }
            @endphp
        </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=2000">

        <script src="{{URL::asset('global/js/jquery-3.5.1.min.js')}}"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"> -->
                <link rel="stylesheet" href="{{URL::asset('global/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('global/css/all.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('menu/css/style.css')}}">
        <link rel="stylesheet" href="{{URL::asset('menu/css/nav.css')}}">
        <link rel="stylesheet" href="{{URL::asset('plapla/css/keyboard.css')}}">

    </head>
    <body>

        @include('includes.menu.header')
        @yield('content')
        @include('includes.menu.footer')
        @csrf
        <script>
            let _token     = $('input[name="_token"]').val();
            function getStatusHold() {
                $.ajax({
                    url:"{{route('takeOrderHold')}}",
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
                                        url: "{{route('takeOrderHold')}}",
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
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js" integrity="sha512-zMfrMAZYAlNClPKjN+JMuslK/B6sPM09BGvrWlW+cymmPmsUT1xJF3P4kxI3lOh9zypakSgWaTpY6vDJY/3Dig==" crossorigin="anonymous"></script> -->
                <script src="{{URL::asset('global/js/popper-1.16.0.js')}}"></script>
        <script src="{{URL::asset('global/js/bootstrap.min.js')}}"></script>
        <script src="{{URL::asset('global/js/sweetalert2@10.js')}}"></script>
        <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->
        <script src="{{URL::asset('menu/js/scrolla.jquery.min.js')}}"></script>
        <script src="{{URL::asset('plapla/js/keyboard.js')}}"></script>
        <script src="{{URL::asset('menu/js/main.js')}}"></script>
        <script src="{{URL::asset('menu/js/navbar.js')}}"></script>


    </body>
</html>

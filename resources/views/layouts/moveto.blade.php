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
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
        <link rel="stylesheet" href="{{URL::asset('moveto/css/nav.css')}}">
        <link rel="stylesheet" href="{{URL::asset('moveto/css/style.css')}}">
        <link rel="stylesheet" href="{{URL::asset('control/css/search_select.css')}}">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    </head>
    <body>
@yield('content')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src='{{URL::asset('moveto/js/date.js')}}'></script>
<script src='{{URL::asset('moveto/js/main.js')}}'></script>
<script src="{{URL::asset('control/js/search_select.js')}}"></script>

    </body>
</html>

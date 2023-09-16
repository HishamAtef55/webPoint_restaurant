    <script>
        $('#add_device').on('click',function (e) {
            e.preventDefault();
            localStorage.setItem("device_number",$('#device_id').val());
            let _token = $('input[name="_token"]').val();
            let ID_DEV = $('#device_id').val();
            let Branch = $('#select').val();
            let printer = $('#printer').val();
            $.ajax({
                url: "{{route('upload.device')}}",
                method: 'post',
                enctype: "multipart/form-data",
                data:
                    {
                        ID_DEV,
                        _token,
                        Branch,
                        printer,
                    },
                success: function (data) {
                    if(data.status == true)
                    {
                        Swal.fire({
                            position: 'center-center',
                            icon: 'success',
                            title: 'Your Device has been saved',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                    else if(data.status == false)
                    {
                        Swal.fire({
                            position: 'center-center',
                            icon: 'success',
                            title: 'This device already exists and has been activated on this device',
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }

                },
                error: function (reject) {
                var response  = $.parseJSON(reject.responseText);
                $.each(response.errors , function (key, val)
                {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: val[0],
                    });
                });
            }
            });
        });
    </script>

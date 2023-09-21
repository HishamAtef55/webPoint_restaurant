<script>
    $(document).ready(function()
    {
        $('.select_Branch').on('change',function ()
        {
            let _token = $('input[name="_token"]').val();
            $('tbody').empty();
            let branch = $(this).val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('getDays.getDaysUsingBranch')}}",
                    'data':
                        {
                            'branch'  : branch,
                            '_token': "{{csrf_token()}}",
                        },
                    success:function(data)
                    {
                        let html = '';
                        html += '<option value=""></option>';
                        for (var count = 0 ; count < data.data.length ; count ++)
                        {
                            html += '<option value="'+data.data[count].date+'">'+data.data[count].date+'</option>';
                        }
                        $('#select_menu').html(html);
                        getOptions ($('#select_menu'));
                    },
                });
            }
        });
    });
    $('#EmptyTable').on('click',function (e) {
        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let Branch = $('.select_Branch').val();
        let date = $('#select_menu').val();
        $.ajax({
            url: "{{route('getDays.emptyTable')}}",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    Branch,
                    date,
                },
            success: function (data) {
                if(data.status == true)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Tables Is Empty Data',
                        showConfirmButton: false,
                        timer: 2500000000000000
                    });
                }
            },
        });
    });

    $('#openDay').on('click',function (e) {
        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let Branch = $('.select_Branch').val();
        let date = $('#select_menu').val();
        $.ajax({
            url: "{{route('getDays.openDay')}}",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    Branch,
                    date,
                },
            success: function (data) {
                if(data.status == true)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Day Is Open',
                        showConfirmButton: true
                    });
                }
                else if(data.status == false)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'error',
                        title: 'Check Orders This Table is not empty',
                        showConfirmButton: false,
                        timer: 2500
                    });
                }

            },
        });
    });

</script>

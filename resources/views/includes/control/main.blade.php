<script type="text/javascript">
    
 // ###################### change branch and include menu ############################
    $(document).ready(function()
    {
        let _token           = $('input[name="_token"]').val();
        $('#select_branch').on('change',function () 
        {
            $('tbody').empty();
            let branch = $(this).val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('view.select.branch')}}",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "{{csrf_token()}}",
                    },
                    success:function(data)
                    {
                        let html = '';
                        html += '<option value=""></option>';
                        for (var count = 0 ; count < data.length ; count ++)
                        {
                            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
                        }
                        $('#select_menu').html(html);
                        getOptions ($('#select_menu'));
                    },
                });
            }
        });
    });
     //#################################  change menu and include group ##################
    $(document).ready(function()
    {
        let _token           = $('input[name="_token"]').val();
        $('#select_menu').on('change',function () 
        {
            $('tbody').empty();
            let branch = $(this).val();
            let menu   = $('#select_menu').val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('view.select.menu')}}",
                    'data':
                    {
                        branch : branch,
                        menu   : menu,
                        _token : _token,
                    },
                    success:function(data)
                    {
                        let html = '';
                        html += '<option value=""></option>';
                        for (var count = 0 ; count < data.length ; count ++)
                        {
                            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
                        }
                        $('#select_group').html(html);
                        getOptions ($('#select_group'));
                    },
                });
            }
        });
    });
//#################################  change group and include subgroup ##################
    $(document).ready(function()
    {
        let _token           = $('input[name="_token"]').val();

        $('#select_group').on('change',function () 
        {
            $('tbody').empty();
            $('#select_subgroup').html('');

            let branch = $('#select_branch').val();
            let menu   = $('#select_menu').val();
            let group  = $(this).val();
            
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('view.select.group')}}",
                    'data':
                    {
                        _token  : _token,
                        branch  : branch,
                        menu    : menu,
                        group   : group,
                        
                    },
                    success:function(data)
                    {
                        let html = '';
                        html += '<option value=""></option>';
                        for (var count = 0 ; count < data.length ; count ++)
                        {
                            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
                        }
                        $('#select_subgroup').html(html);
                        getOptions ($('#select_subgroup'));
                    },
                });
            }
        });
    });

//#################################  ##################
</script>    

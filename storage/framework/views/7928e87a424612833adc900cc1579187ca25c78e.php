
    <script  type='module' >
        function getOptions(select) {
            select.children('option').each(function() {
                let text = $(this).text().split(' ').join('-');

                $(this).attr('data-value', text);
            });

            let options = Array.from(select.children('option'));

            for ( let i = 0; i < options.length ; i++ ) {
                let item = $(`<li class="item" data-value='${options[i].value.split(' ').join('-')}'></li>`);

                item.text(options[i].text);

                item.appendTo(select.next('.search-select').find('ul'))
            }
        }








    $(document).ready(function(){
        $('#branch-name').keyup(function ()
        {
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"<?php echo e(route('search.branch')); ?>",
                    method:'post',
                    data:{query:query, _token:_token},
                    success:function(data)
                    {
                        $('#select_branch').show();
                        var html = '';
                        for(var count = 0 ; count < data.length ; count++)
                        {
                            html += '<li id="'+data[count].id+'">'+data[count].name+'';
                            html += '</li>';
                        }$('#select_branch').html(html);
                    },
                });
            }
            else{
                $('#select_branch').hide();
            }
        });
    });





    $(document).ready(function(){
        $('#menu-name').keyup(function ()
        {

            var query = $(this).val();
            var ID = $('#branch').val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"<?php echo e(route('search.menu')); ?>",
                    method:'post',
                    data:{ID:ID ,query:query, _token:_token},

                    success:function(data)
                    {
                        if(data == '')
                        {

                        }
                        else
                        {
                            $('#select_menu').show();
                            var html = '';
                            for(var count = 0 ; count < data.length ; count++)
                            {
                                html += '<li id="'+data[count].id+'">'+data[count].name+'';
                                html += '</li>';
                        }
                        }$('#select_menu').html(html);
                    }
                });
            }else{
                $('#select_menu').hide();
            }
        });
    });





    $(document).ready(function(){
        $('#group-name').keyup(function ()
        {
            var query = $(this).val();
            var ID = $('#branch_test').val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"<?php echo e(route('search.group')); ?>",
                    method:'post',
                    data:{ID:ID, query:query, _token:_token},
                    success:function(data)
                    {
                        if(data == '')
                        {

                        }
                        else
                        {
                            $('#select_group').show();
                            var html = '';
                            for(var count = 0 ; count < data.length ; count++)
                            {
                                html += '<li id="'+data[count].id+'">'+data[count].name+'';
                                html += '</li>';
                            }
                        }$('#select_group').html(html);
                    }
                });
            }else{
                $('#select_group').hide();
            }
        });
    });





    $(document).ready(function(){
        $('#sub-group-name').keyup(function ()
        {
            var query = $(this).val();
             let ID = $('#branch_test2').val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"<?php echo e(route('search.subgroup')); ?>",
                    method:'post',
                    data:{ID:ID , query:query,_token:_token},
                    success:function(data)
                    {
                        if(data == '')
                        {

                        }
                        else
                        {
                            $('#select_subgroup').show();
                            var html = '';
                            for(var count = 0 ; count < data.length ; count++)
                            {
                                html += '<li id="'+data[count].id+'">'+data[count].name+'';
                                html += '</li>';
                            }

                        }$('#select_subgroup').html(html);
                    }
                });
            }
            else{
                $('#select_subgroup').hide();
            }
        });
    });
    
    
    $(document).ready(function()
    {
        $('#group_select').on('change',function () {
            $('#subgroup_select_select').empty();
            $('#subgroup_select').empty();
            var ID = '1';
            var branchID = $(this).val();
            if(branchID)
            {
                $.ajax({
                    'type':'POST',
                    'url':"<?php echo e(Route('view.select.group')); ?>",
                    'data':{
                        'group' : branchID},
                        'ID'    : ID,
                        '_token': "<?php echo e(csrf_token()); ?>",
                    success:function(data)
                    {
                        let html = '';
                        for (var count = 0 ; count < data.length ; count ++)
                        {
                            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
                        }
                        $('#subgroup_select_select').html(html);
                        getOptions ($('#subgroup_select_select'));
                    },
                });
            }
        });
    });

        

</script>




<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/control/add_genral_ajax.blade.php ENDPATH**/ ?>
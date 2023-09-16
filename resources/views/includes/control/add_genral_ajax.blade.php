
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


{{--################## Start Search Menu ###########################--}}



{{--###################### Start Search Branch  ########################### --}}

    $(document).ready(function(){
        $('#branch-name').keyup(function ()
        {
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.branch')}}",
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

{{--###################### Enbd Search Branch  ########################### --}}

{{--###################### Start Search Menu  ########################### --}}

    $(document).ready(function(){
        $('#menu-name').keyup(function ()
        {

            var query = $(this).val();
            var ID = $('#branch').val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.menu')}}",
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

{{--###################### Start Search Menu   ########################### --}}

{{--###################### Start Search Group  ########################### --}}

    $(document).ready(function(){
        $('#group-name').keyup(function ()
        {
            var query = $(this).val();
            var ID = $('#branch_test').val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{route('search.group')}}",
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

{{--###################### End Menu Branch  ########################### --}}

{{--###################### Start Search Sub Group  ########################### --}}

    $(document).ready(function(){
        $('#sub-group-name').keyup(function ()
        {
            var query = $(this).val();
             let ID = $('#branch_test2').val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.subgroup')}}",
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
    {{--###################### End Search Sub Group  ########################### --}}
    {{--###################### Start Select Items########################### --}}
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
                    'url':"{{Route('view.select.group')}}",
                    'data':{
                        'group' : branchID},
                        'ID'    : ID,
                        '_token': "{{csrf_token()}}",
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

        {{--###################### End Select Items  ########################### --}}

</script>





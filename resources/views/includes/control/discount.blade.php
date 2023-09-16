<script>
    {{-- ######################################## Start Save Discount ################### --}}
    $(document).on('click','#save_discount',function (e)
    {

        let allInputs = $(this).parents('.row').find('.input-empty input');
        let allLabels = $(this).parents('.row').find('.input-empty label');
        let allLines = $(this).parents('.row').find('.input-empty span');

        

        var formData = new FormData($('#Save_form_dis')[0]);
        e.preventDefault();
        
        $.ajax({
            'type':'POST',
            'url':"{{Route('save.discount')}}",
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data':formData,
            success: function (data)
            {
                if(data.status == true)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Discount has been saved',
                        showConfirmButton: false,
                        timer: 1250
                    });

                    // For Remove All Values After Save ====== Start
                    allInputs.each(function() {
                        $(this).val('')
                    });

                    allLabels.each(function() {
                        $(this).removeClass('focused')
                    });

                    $('.radio-box input[type="radio"]:checked').prop('checked', false)

                    allLines.each(function() {
                        $(this).removeClass('fill')
                    });

                    // For Remove All Values After Save ====== End
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
    {{-- ######################################## End Save Discount ################### --}}

    {{-- ######################################## Start search  Save Discount ################### --}}

    /* $(document).ready(function(){
        $('#discount-type').keyup(function ()
        {
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{route('search.discount')}}",
                    method:'post',
                    data:{query:query, _token:_token},
                    success:function(data)
                    {
                        $('#select').show();
                        var html = '';
                        for(var count = 0 ; count < data.length ; count++)
                        {
                            html += '<li id="'+data[count].id+'">'+data[count].name+'';
                            html += '</li>';
                        }$('#select').html(html);
                    }
                });
            }else{
                $('#select').hide();
            }
        });
    }); */
    {{-- ######################################## End sarch save Discount Page ################### --}}


    {{-- ######################################## Start Search in update  Discount  ################### --}}
    $(document).ready(function(){
        $('#search').keyup(function ()
        {
            var html = '';
            var query = $(this).val();
            var ID = $('#branch').val();
            if(query != '')
            {
                $('#tbody').show();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.discount')}}",
                    method:'post',
                    data:{ID:ID ,query:query, _token:_token},
                    success:function(data)
                    {

                        for(var count = 0 ; count < data.length ; count ++)
                        {
                            html += '<tr id="'+data[count].id+'">';
                            html += '<td>';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].id+'</span>';
                            html += '<input id="id" class="tabledit-input tabledit-identifier" type="hidden" name="id" value="'+data[count].id+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].name+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="name" value="'+data[count].name+'">';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode" id="">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].type+'</span>';
                            html += '<select class="tabledit-input form-control input-sm" name="type'+data[count].id+'"  style="display: none;">';
                            html += '<option value="Value"> Value </option>';
                            html += '<option value="Ratio"> Ratio </option>';
                            html += '</select>';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].value+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="value" value="'+data[count].value+'">';
                            html +='</td>';

                            html += '<td class="">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].branch.name+'</span>';
                            html += '<input id="group_id" class="tabledit-input tabledit-identifier" type="hidden" name="branch" value="'+data[count].branch_id+'">';
                            html += '</td>';

                            html += '<td style="white-space: nowrap;"><div class="tabledit-toolbar btn-toolbar" style="text-align: left; font-size: 50px;"><div class="btn-group btn-group-sm" style="float: none; font_Size:50px;"><button type="button" class="tabledit-edit-button btn btn-sm btn-default" style="float: none; font_Size:50px;"><span><i class="far fa-edit fa-2x"></i></span></button><button type="button" class="tabledit-delete-button btn btn-sm btn-default" style="float: none;"><span style="font-size: 15px;" ><i class="fas fa-trash fa-2x"></i></span></button></div><button type="button" class="tabledit-save-button btn btn-sm btn-success" style=" font-size:10px;display: none; float: none;">Confirm</button><button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style=" font-size:10px;display: none; float: none;">Confirm</button></div></td>';
                            html += '</tr>';
                        }$('tbody').html(html);

                    }
                });
            }else{
                $('tbody').hide();
            }
        });
    });
    {{-- ######################################## End Search in update  Discount  ################### --}}


    {{-- ######################################## Start Update  Discount  ################### --}}
    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });

        $('#editable').Tabledit({
            url:'{{ route("tablediscount.action") }}',
            dataType:"json",
            columns:{
                identifier:[[0, 'id'],[4,'branch_id']],
                editable:[[1, 'name'], [2, 'type'], [3,'value']]
            },
            restoreButton:false,
            onSuccess:function(data, textStatus, jqXHR)
            {
                if(data.action == 'delete')
                {
                    $('#'+data.id).remove();
                }
                if(data.action == 'edit')
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Updated',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    });
    {{-- ######################################## End Update  Discount ################### --}}
</script>

<script type="text/javascript">
    {{--###################### Start Save User  ##################################### --}}

$(document).on('click','#save_user',function (e) {
    let allInputs = $(this).parents('form').find('.input-empty input');
    let allLabels = $(this).parents('form').find('.input-empty label');
    let allLines = $(this).parents('form').find('.input-empty span');
    var formData = new FormData($('#form_save_user')[0]);
    e.preventDefault();
    $.ajax({
        'type' : 'POST',
        'url'  :"{{route('save.user')}}",
        enctype:"multipart/form-data",
        processData:false,
        cache : false,
        contentType:false,
        'data' :formData,
            success: function (data)
            {
                if(data.status == true)
                {

                                        // For Remove All Values After Save ====== Start
                    allInputs.each(function() {
                        $(this).val('')
                    });


                    $('input[type="radio"]').prop('checked', false)

                    allLabels.each(function() {
                        $(this).removeClass('focused')
                    });

                    allLines.each(function() {
                        $(this).removeClass('fill')
                    });

                    $('i.reset').click();
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your menu has been saved',
                        showConfirmButton: false,
                        timer: 1250
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
{{--###################### End Save User  ####################################### --}}


    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });

        $('#editable').Tabledit({
            url:'{{ route("tableuser.action") }}',
            dataType:"json",
            columns:{
                identifier:[[0, 'id']],
                editable:[[1, 'name'], [2, 'email'], [3,'mopile'],[4,'dialy_salary'],[5,'pass'],[6,'job_id']]
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

    $(document).ready(function(){
        $('#search').keyup(function ()
        {
            var branch = $('#select_branch').val();
            var query = $(this).val();
            if(query != '')
            {
                $('#tbody').show();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.user')}}",
                    method:'post',
                    enctype:"multipart/form-data",
                    data:{branch:branch ,query:query, _token:_token},
                    success:function(data)
                    {
                        var html = '';
                        for(var count = 0 ; count < data.length ; count ++)
                        {

                            html += '<tr id="'+data[count].id+'">';
                            html += '<td>'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].id+'</span>';
                            html += '<input id="id" class="tabledit-input tabledit-identifier" type="hidden" name="id" value="'+data[count].id+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].name+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="name" value="'+data[count].name+'">';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].email+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="email" value="'+data[count].email+'">';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].mopile+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="mopile" value="'+data[count].mopile+'">';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].dialy_salary+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="dialy_salary" value="'+data[count].dialy_salary+'">';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode">'
                            // html += '<span class="tabledit-span tabledit-identifier">*****</span>';
                            html += '<input type="password" style="display: none;" disabled class="tabledit-input tabledit-identifier" name="pass" value="">';
                            html +='</td>';

                            html += '<td class="tabledit-view-mode" id="show-hide">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].job.name+'</span>';
                            html += '<select class="tabledit-input form-control input-sm" name="type'+data[count].id+'"  style="display: none;">';
                            html += '<option value="1"> Cashier </option>';
                            html += '<option value="2"> Capitain </option>';
                            html += '<option value="3"> Pilot </option>';
                            html += '<option value="4"> Waiter </option>';
                            html += '<option value="5"> Take Away </option>';
                            html += '<option value="6"> Car Service </option>';
                            html += '<option value="7"> Other </option>';
                            html += '</select>';
                            html +='</td>';

                            html += `<td style="white-space: nowrap;">
                            <div class="tabledit-toolbar btn-toolbar" style="text-align: left; font-size: 50px;">
                                <div class="btn-group btn-group-sm" style="float: none; font_Size:50px;">
                                <button type="button" id='edit-btn' class="tabledit-edit-button btn btn-sm btn-default" style="float: none; font_Size:50px;">
                                    <span><i class="far fa-edit fa-2x"></i></span>
                                </button>
                                <button type="button" class="tabledit-delete-button btn btn-sm btn-default" style="float: none;">
                                    <span style="font-size: 15px;" ><i class="fas fa-trash fa-2x"></i></span>
                                </button>
                                </div>
                                <button type="button" class="tabledit-save-button btn btn-sm btn-success" style=" font-size:10px;display: none; float: none;">Confirm</button><button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style=" font-size:10px;display: none; float: none;">Confirm</button>
                            </div>
                        </td>`;
                            html += '</tr>';
                        }$('tbody').html(html);
                    }
                });
            }
            else
            {
                $('tbody').hide();
            }
        });

        $(document).on('click', '#edit-btn', function() {
            let showHideParent = $(this).parents('tr').find('#show-hide');

            let spanValue      = showHideParent.children('span');

            let mySelect      = showHideParent.children('select');

            mySelect.children(`option:contains(${spanValue.text()})`).prop('selected', true)

        });
    });
</script>

<script type="text/javascript">
    let _token           = $('input[name="_token"]').val();
    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });

        $('#editable').Tabledit({
            url:'<?php echo e(route("tablesubgroup.action")); ?>',
            dataType:"json",
            columns:{
                identifier:[[0, 'id'],[2,'group_id']],
                editable:[[1, 'name']],
            },
            restoreButton:false,
            onSuccess:function(data, textStatus, jqXHR)
            {
                if(data.action == 'delete')
                {
                    $('#'+data.id).remove();
                }
                if(data.status == 'false')
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'This SubGroup Has Items',
                    });
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
            var html  = '';
            var query = $(this).val();
            let branch     = $('#select_branch').val();
            let menu       = $('#select_menu').val();
            let group      = $('#select_group').val();
            if(query != '')
            {
                $('#tbody').show();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"<?php echo e(route('search.subgroup')); ?>",
                    method:'post',
                    data:
                        {
                            branch       :branch ,
                            query        :query,
                            menu         : menu,
                            group        : group,
                             _token      :_token
                         },
                    success:function(data)
                    {

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

                            html += '<td value="'+data[count].group.name+'" class="">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].group.name+'</span>';
                            html += '<input id="group_id" class="tabledit-input tabledit-identifier" type="hidden" name="group_id" value="'+data[count].group_id+'">';
                            html += '</td>';

                            html += '<td class="tabledit-view-mode" id="show-hide">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].active+'</span>';
                            html += '<select class="tabledit-input form-control input-sm" name="active'+data[count].id+'"  style="display: none;">';
                            html += '<option value="Hide"> Hide </option>';
                            html += '<option value="Show"> Show </option>';
                            html += '</select>';
                            html +='</td>';

                            html += `<td>
                            <div class="tabledit-toolbar btn-toolbar">
                                <div class="btn-group btn-group-sm">
                                <button type="button" id='edit-btn' class="tabledit-edit-button btn">
                                    <span><i class="far fa-edit fa-lg"></i></span>
                                </button>
                                <button type="button" class="tabledit-delete-button btn">
                                    <span" ><i class="fas fa-trash fa-lg"></i></span>
                                </button>
                                </div>
                                <button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none">Confirm</button>
                                <button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none">Confirm</button>
                            </div>
                        </td>`;
                            html += '</tr>';
                        }
                        $('tbody').html(html);

                    }
                });
            }else{
                $('tbody').hide();
            }
        });
    });

    $(document).on('click', '#edit-btn', function() {
        let showHideParent = $(this).parents('tr').find('#show-hide');

        let spanValue      = showHideParent.children('span');

        let mySelect      = showHideParent.children('select');

        mySelect.children(`option[value='${spanValue.text()}']`).prop('selected', true)

    });

    $(document).on('click','#save_subgroup',function (e)
    {
        let allInputs = $(this).parents('.d-flex').find('input');
        let allLabels = $(this).parents('.d-flex').find('label');
        let allLines = $(this).parents('.d-flex').find('span');

        let branch     = $('#select_branch').val();
        let menu       = $('#select_menu').val();
        let group      = $('#select_group').val();
        let sub_group  = $('#search').val();
        e.preventDefault();

        $.ajax({
            url     :"<?php echo e(route('save.subgroup')); ?>",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
            {
               _token       : _token,
               branch       : branch,
               menu         : menu,
               group        : group,
               sub_group    : sub_group,
            },
            success: function (data)
            {
                if(data.status == true)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your menu has been saved',
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

                    allLines.each(function() {
                        $(this).removeClass('fill')
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
<?php /**PATH D:\Xampp\htdocs\webpoint\resources\views/includes/control/sub_group.blade.php ENDPATH**/ ?>
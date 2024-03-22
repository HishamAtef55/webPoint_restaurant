<script type="text/javascript">
    let _token           = $('input[name="_token"]').val();
    $(document).ready(function(){

        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });

        $('#editable').Tabledit({
            url:'<?php echo e(route("tablegroup.action")); ?>',
            dataType:"json",
            columns:{
                identifier:[[0, 'id'],[2,'menu_id']],
                editable:[[1, 'name']],
            },
            restoreButton:false,
            onSuccess:function(data, textStatus, jqXHR)
            {
                if(data.status == 'false')
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'This Group Has SubGroups',
                    });
                }
                if(data.action == 'delete')
                {
                    $('#'+data.id).remove();
                }
                else if(data.action == 'edit')
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
            var html = '';

            var query = $(this).val();
            var ID    = $('#select_branch').val();
            var menu  = $('#select_menu').val();
            if(query != '')
            {
                $('#tbody').show();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"<?php echo e(route('search.group')); ?>",
                    method:'post',
                    data:{ID:ID,menu,menu , query:query, _token:_token},
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

                            html += '<td class="">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].menu.name+'</span>';
                            html += '<input id="branch_id" class="tabledit-input tabledit-identifier" type="hidden" name="branch_id" value="'+data[count].menu_id+'">';
                            html += '</td>';

                            html += `<td>
                                <div class="tabledit-toolbar btn-toolbar">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="tabledit-edit-button btn">
                                            <span><i class="far fa-edit fa-lg"></i></span>
                                        </button>
                                        <button type="button" class="tabledit-delete-button btn">
                                            <span><i class="fas fa-trash fa-lg"></i></span>
                                        </button>
                                    </div>
                                    <button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none">Confirm</button>
                                    <button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none">Confirm</button>
                                </div>
                            </td>`;
                            html += '</tr>';
                        }$('tbody').html(html);

                    }
                });
            }else{
                $('tbody').hide();
            }
        });
    });

    // ################## Save Group #######################
    $(document).on('click','#save_group',function (e)
    {

        let allInputs = $(this).parents('.d-flex').find('input');
        let allLabels = $(this).parents('.d-flex').find('label');
        let allLines = $(this).parents('.d-flex').find('span');

        e.preventDefault();
        let branch = $('#select_branch').val();
        let menu   = $('#select_menu').val();
        let group  = $('#search').val();
        $.ajax({
            url     :"<?php echo e(route('save.group')); ?>",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
            {
               _token       : _token,
               branch       : branch,
               menu         : menu,
               group        : group,
            },
            success: function (data)
            {
                if(data.status == true)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Group has been saved',
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
<?php /**PATH D:\Xampp\htdocs\webpoint\resources\views/includes/control/group.blade.php ENDPATH**/ ?>
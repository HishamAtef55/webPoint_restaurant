<script type="text/javascript">
	let _token = $('input[name="_token"]').val();

    

    $(document).on("change", "#form_save_mincharge input[type='checkbox']", function() {

        let checkBoxs = $("#form_save_mincharge input[type='checkbox']:not('#check-all')")

        if ($(this).attr('id') == 'check-all' && $(this).is(":checked")) {
            checkBoxs.each(function() {
                $(this).prop('checked', true)
            });
        } else if ($(this).attr('id') == 'check-all' && $(this).is(":checked") == false) {
            checkBoxs.each(function() {
                $(this).prop('checked', false)
            });
        }
    });

    $(document).on('click','#save_mincharge',function (e) {
        e.preventDefault();
        let _token           = $('input[name="_token"]').val();
        let min_charge      = $('#all_holes').val();
        let branch          = $('#select_branch').val();
        let holearray       = [];
        let allCheckes = $(this).parents('form').find("#editable input:not('#check-all'):checked");

        allCheckes.each(function() {
            let idNum = $(this).parents('tr').attr('id');
            let holeObj = {id: idNum}

            holearray.push(holeObj);
        });

        $.ajax({
            type    : 'POST',
            url     :"<?php echo e(route('Save.all.min')); ?>",
            method  : 'post',
            enctype : "multipart/form-data",
            data: {
                _token         : _token,
                min_charge     : min_charge,
                branch         : branch,
                holearray      : holearray
            },
            success: function (data) {
                if(data.status == true) {
                    allCheckes.each(function() {
                        let columnCheck = $(this).parents('tr').find('.min-charge-value');
                        columnCheck.children('span').text(min_charge);
                        columnCheck.children('input').val(min_charge);
                    });

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
                $.each(response.errors , function (key, val) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: val[0],
                    });
                });
            }
        });
    });




$(document).ready(function() {
    $('#select_branch').on('change',function () {
        let branch = $(this).val();
        let html ='';
        if(branch) {
            $.ajax({
                'type':'POST',
                'url':"<?php echo e(Route('get.mincharge')); ?>",
                'data': {
                    'branch'  : branch,
                    '_token': "<?php echo e(csrf_token()); ?>",
                },
                success:function(data) {
                    for(var count = 0 ; count < data.length ; count ++) {
                        html += '<tr id="'+data[count].number_holes+'">';

                        html += '<td class="check-column">'
                        html += '<input class="tabledit-span tabledit-identifier" type="checkbox" name="id" value="'+data[count].number_holes+'">';
                        html +='</td>';

                        html += '<td>'
                        html += '<span class="tabledit-span tabledit-identifier">'+data[count].number_holes+'</span>';
                        html += '<input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="'+data[count].number_holes+'">';
                        html +='</td>';


                        html += '<td>'
                        html += '<span class="tabledit-span tabledit-identifier">'+data[count].name+'</span>';
                        html += '<input class="tabledit-input tabledit-identifier" type="hidden" name="hole_name" value="'+data[count].name+'">';
                        html +='</td>';


                        html += '<td class="tabledit-edit-mode min-charge-value">'
                        html += '<span class="tabledit-span tabledit-identifier">'+data[count].min_charge+'</span>';
                        html += '<input type="number" style="display: none;" disabled class="tabledit-input form-control input-sm" name="min_charge" value="'+data[count].min_charge+'">';
                        html +='</td>';

                        html += '<td class="d-none">'
                        html += '<span class="tabledit-span tabledit-identifier">'+data[count].branch_id+'</span>';
                        html += '<input class="tabledit-input tabledit-identifier" type="hidden" name="branch" value="'+data[count].branch_id+'">';
                        html +='</td>';

                        html += '<td style="white-space: nowrap;"><div class="tabledit-toolbar btn-toolbar" style="text-align: left; font-size: 50px;"><div class="btn-group btn-group-sm" style="float: none; font_Size:50px;"><button type="button" class="tabledit-edit-button btn btn-sm btn-default" style="float: none; "><i class="far fa-edit fa-2x"></i></button></div><button type="button" class="tabledit-save-button btn btn-sm btn-success" style=" font-size:10px;display: none; float: none;">Save</button></div></td>';
                        html += '</tr>';
                    }
                    $('tbody').html(html);
                },
            });
        }
    });

    $.ajaxSetup({
        headers:{
            'X-CSRF-Token' : $("input[name=_token]").val()
        }
    });
    let branch = $(this).val();
    $('#editable').Tabledit({
        url:'<?php echo e(route("Save.one.min")); ?>',
        dataType:"json",
        columns: {
            identifier:[[1, 'id']],
            editable:[[3, 'min_charge'],[4,'branch']],
            branch  : branch
        },
        restoreButton:false,
        onSuccess:function(data, textStatus, jqXHR) {
            if(data.action == 'edit') {
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

</script>
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/control/mincharge.blade.php ENDPATH**/ ?>
<script type="text/javascript">
let _token           = $('input[name="_token"]').val();
$(document).ready(function(){

    $.ajaxSetup({
        headers:{
            'X-CSRF-Token' : $("input[name=_token]").val()
        }
    });

    $('#editable').Tabledit({
        url:'{{ route("tablemenu.action") }}',
        dataType:"json",
        columns:{
            identifier:[[0, 'id'],[2,'branch_id']],
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
                    text: 'This Menu Has Groups',
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

    $('#search').keyup(function ()
    {
        var html = '';

        let query = $(this).val();
        let ID    = $('#select').val();
        if(query != '')
        {
            $('#tbody').show();
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url:"{{route('search.menu')}}",
                method:'post',
                data:{ID:ID, query:query, _token:_token},
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
                        html += '<span class="tabledit-span tabledit-identifier">'+data[count].branch.name+'</span>';
                        html += '<input id="branch_id" class="tabledit-input tabledit-identifier" type="hidden" name="branch_id" value="'+data[count].branch_id+'">';
                        html += '</td>';

                        html += '<td class="tabledit-view-mode" id="show-hide">';
                        html += '<span class="tabledit-span tabledit-identifier">'+data[count].activation+'</span>';
                        html += '<select class="tabledit-input form-control input-sm" name="active'+data[count].id+'"  style="display: none;">';
                        html += '<option value="Hide"> Hide </option>';
                        html += '<option value="Show"> Show </option>';
                        html += '</select>';
                        html +='</td>';

                        html += '<td class="tabledit-edit-mode">'
                        if (data[count].active) {
                            html += '<span class="tabledit-span tabledit-identifier radio-span"> <input type="radio" disabled checked name="per.blade.php" value="'+data[count].active+'"> </span>';
                        } else {
                            html += '<span class="tabledit-span tabledit-identifier"> <input type="radio" disabled name="per" value="'+data[count].active+'"> </span>';
                        }
                        html += '<input type="radio" style="display: none;" disabled class="tabledit-input radio-edit" name="per" value="'+data[count].active+'">';
                        html +='</td>';

                        html += `<td>
                            <div class="tabledit-toolbar btn-toolbar">
                                <div class="btn-group btn-group-sm">
                                <button type="button" id='edit-btn' class="tabledit-edit-button btn btn-default">
                                    <span><i class="far fa-edit fa-lg"></i></span>
                                </button>
                                <button type="button" class="tabledit-delete-button btn btn-default">
                                    <span><i class="fas fa-trash fa-lg"></i></span>
                                </button>
                                </div>
                                <button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none;">Confirm</button><button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none;">Confirm</button>
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

        let mySelect       = showHideParent.children('select');

        mySelect.children(`option[value='${spanValue.text()}']`).prop('selected', true);

    });

    $(document).on('click', 'input[type="radio"]', function() {

        $(this).val('1');
    });


    // ################################# Save Menu ##############################
    $(document).on('click','#save_menu',function (e)
    {

        let allInputs = $(this).parents('.d-flex').find('input');
        let allLabels = $(this).parents('.d-flex').find('label');
        let allLines = $(this).parents('.d-flex').find('span');

        let _token           = $('input[name="_token"]').val();
        e.preventDefault();
        let name      = $('#search').val();
        let branch_id = $('#select').val();

        $.ajax({
            type    : 'POST',
            url     :"{{route('add.menu')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
            {
            _token         : _token,
            name           : name,
            branch_id      : branch_id,
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
// ################################# Save Menu ##############################
});
</script>

<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });

        $('#editable').Tabledit({
            url:'{{ route("update.printers.action") }}',
            dataType:"json",
            columns:{
                identifier:[[0, 'id']],
                editable:[[1, 'printer']],
            },
            restoreButton:false,
            onSuccess:function(data, textStatus, jqXHR)
            {
                if(data.action == 'delete') {
                    $('tr#'+data.id).remove();
                }
                if(data.status == 'false')
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                    });
                }
                if(data.status == 'true')
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });

        $('#printer-name').keyup(function () {
            var html = '';

            let query = $(this).val();
            let ID    = $('#select').val();
            if(query != '') {
                $('#tbody').show();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.printers.action')}}",
                    method:'post',
                    data:{ID:ID, query:query, _token:_token},
                    success:function(data)
                    {

                        for(var count = 0 ; count < data.printers.length ; count ++) {
                            html += '<tr id="'+data.printers[count].id+'">';
                            html += '<td>'
                            html += '<span class="tabledit-span tabledit-identifier">'+data.printers[count].id+'</span>';
                            html += '<input id="id" class="tabledit-input tabledit-identifier" type="hidden" name="id" value="'+data.printers[count].id+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data.printers[count].printer+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="printer" value="'+data.printers[count].pronter+'">';
                            html +='</td>';

                            html += `<td style="white-space: nowrap;">
                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left">
                                    <div class="btn-group btn-group-sm" style="float: none">
                                    <button type="button" id='edit-btn' class="tabledit-edit-button btn btn-default" style="float: none">
                                        <span><i class="far fa-edit fa-lg"></i></span>
                                    </button>
                                    <button type="button" class="tabledit-delete-button btn btn-default" style="float: none;">
                                        <span><i class="fas fa-trash fa-lg"></i></span>
                                    </button>
                                    </div>
                                    <button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none; float: none;">Confirm</button><button type="button" class="tabledit-confirm-button btn btn-danger" style="display: none; float: none;">Confirm</button>
                                </div>
                            </td>`;
                            html += '</tr>';
                        }
                        $('#tbody').html(html);
                    }
                });
            } else {
                $('#tbody').hide();
            }
        });
            
        $(document).on('click','#save_printer',function (e) {
            e.preventDefault();
            let allInputs = $(this).parents('.d-flex').find('input');
            let allLabels = $(this).parents('.d-flex').find('label');
            let allLines = $(this).parents('.d-flex').find('span');
            let _token = $('input[name="_token"]').val();
            let printer = $('#printer-name').val();
            let branch = $('#select').val();

            $.ajax({
                type    : 'POST',
                url     :"{{route('Save.Printers')}}",
                method  : 'post',
                enctype : "multipart/form-data",
                data:
                {
                _token ,
                printer,
                branch,
                },
                success: function (data)
                {
                    if(data.status == 'true')
                    {
                        Swal.fire({
                            position: 'center-center',
                            icon: 'success',
                            title: 'Your Printer is saved',
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
                }
            });
        });
    });
</script>
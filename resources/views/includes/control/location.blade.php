<script>
    $('#savelocation').on('click',function (e) {
        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let location = $('#location').val();
        let price    = $('#price').val();
        let time     = $('#time').val();
        let branch   = $('#select_branch').val();
        let pilotValue   = $('#pilotValue').val();
        $.ajax({
            url: "{{route('save.location')}}",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token     : _token,
                    location   :location,
                    price      :price,
                    time       :time,
                    branch     :branch,
                    pilotValue :pilotValue
                },
            success: function (data) {
                if(data.status == 'true')
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Saved',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
                else if(data.status == false)
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Updated',
                        showConfirmButton: false,
                        timer: 1000
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
    $(document).ready(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });
        $('#editable').Tabledit({
            url:'{{ route("update.location") }}',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            dataType:"json",
            columns:{
                identifier:[[0, 'id']],
                editable:[[1, 'location'], [2, 'price'], [3,'time'],[4,'pilotValue']]
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

        $('#select_branch').on('change',function ()
        {
            let _token           = $('input[name="_token"]').val();
            $('tbody').empty();
            let branch = $(this).val();
            if(branch)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('Search.location')}}",
                    'data':
                        {
                            'branch'  : branch,
                            '_token': "{{csrf_token()}}",
                        },
                    success:function(data)
                    {
                        let html = '';
                        for(var count = 0 ; count < data.length ; count ++)
                        {
                            html += '<tr id="'+data[count].id+'">';
                            html += '<td>'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].id+'</span>';
                            html += '<input id="id" class="tabledit-input tabledit-identifier" type="hidden" name="id" value="'+data[count].id+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].location+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="location" value="'+data[count].name+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].price+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="price" value="'+data[count].name+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].time+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="time" value="'+data[count].name+'">';
                            html +='</td>';

                            html += '<td class="tabledit-edit-mode">'
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].pilot_value+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="pilotValue" value="'+data[count].pilot_value+'">';
                            html +='</td>';

                            html += '<td style="white-space: nowrap;"><div class="tabledit-toolbar btn-toolbar" style="text-align: left;"><div class="btn-group btn-group-sm" style="float: none"><button type="button" class="tabledit-edit-button btn btn-default" style="float: none"><span><i class="far fa-edit fa-lg"></i></span></button><button type="button" class="tabledit-delete-button btn btn-default" style="float: none;"><span><i class="fas fa-trash fa-lg"></i></span></button></div><button type="button" class="tabledit-save-button btn btn-success" style="display: none; float: none;">Confirm</button><button type="button" class="tabledit-confirm-button btn btn-danger" style="display: none; float: none;">Confirm</button></div></td>';
                            html += '</tr>';
                        }$('tbody').html(html);
                    },
                });
            }else{
                $('tbody').hide();
            }
        });
    });




</script>

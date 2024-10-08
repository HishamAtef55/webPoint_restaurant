<script type="text/javascript">
    let _token           = $('input[name="_token"]').val();

{{-- ############################### Start Update Branch ##########################  --}}
    $(document).ready(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-Token' : $("input[name=_token]").val()
            }
        });

        $('#editable').Tabledit({
            url:'{{ route("tablebranch.action") }}',
            dataType:"json",
            columns:{
                identifier:[0, 'id'],
                editable:[[1, 'name']],
            },
            restoreButton:false,
            onSuccess:function(data, textStatus, jqXHR)
            {
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
            },
        });

    });

    $(document).ready(function(){
        $('#search').keyup(function ()
        {
            var html = '';

            var query = $(this).val();
            if(query != '')
            {
                $('#tbody').show();
                var _token = $('input[name="_token"]').val();

                $.ajax({
                    url:"{{route('search.branch')}}",
                    method:'post',
                    data:{query:query, _token:_token},
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

                            html += `<td>
                                <div class="tabledit-toolbar btn-toolbar">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="tabledit-edit-button btn btn-sm btn-default">
                                            <i class="far fa-edit fa-xl"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="tabledit-save-button btn btn-success" style="display: none">Save</button>
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
{{-- ############################### End Update Branch ##########################  --}}

{{-- ############################### start Save Branch ##########################  --}}
    $(document).on('click','#save_branch',function (e)
    {
        let name = $('#search').val();
        $('#name_error').text('');
        e.preventDefault();
        $.ajax({
        url: "{{route('add.branch')}}",
        method: 'post',
        enctype: "multipart/form-data",
        data:
        {
           _token         : _token,
           name           : name,
        },
        success: function (data)
        {
            if(data.status == true)
            {
                Swal.fire({
                position: 'center-center',
                icon: 'success',
                title: 'Your Branch Has Been Saved',
                showConfirmButton: false,
                timer: 1000
              });

              $('#search').val('');
              $('.input-label').removeClass('focused');
              $('.under-line').removeClass('fill');
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
{{-- ############################### End Update Branch ##########################  --}}

<script>

{{--###################### Start Search Data User  ####################################### --}}
$(document).ready(function(){
    $('#search').keyup(function ()
    {

        var query = $(this).val();
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url:"{{route('search.user')}}",
                method:'post',
                data:{query:query, _token:_token},
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
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].image+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="image" value="'+data[count].image+'">';
                            html +='</td>';

                            html +='<td class="tabledit-view-mode">';
                            html += '<span class="tabledit-span tabledit-identifier">'+data[count].job_id+'</span>';
                            html += '<input type="text" style="display: none;" disabled class="tabledit-input form-control input-sm" name="job_id" value="'+data[count].job_id+'">';
                            html +='</td>';

                            html += '<td  style="white-space: nowrap; width:5%; font_Siz:16px;><div class="tabledit-toolbar btn-toolbar" style="text-align: left;"><div class="btn-group btn-group-sm" style="float: none; font_Size:16px;"><button type="button" class="tabledit-edit-button btn btn-sm btn-default" style="float: none; font_Size:16px;"><span class="glyphicon glyphicon-pencil"></span></button><button type="button" class="tabledit-delete-button btn btn-sm btn-default" style="float: none;"><span class="glyphicon glyphicon-trash"></span></button></div><button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none; float: none;">Save</button><button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none; float: none;">Confirm</button></div></td>';
                            html += '</tr>';
                    }$('tbody').html(html);
                }
            });
        }
    });
});
</script>
<script type="text/javascript">
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
                identifier:[0, 'id'],
                editable:[[1, 'name'], [2, 'email'], [3,'mopile'],[4,'image'],[5,'job_id']]
            },
            restoreButton:false,
            onSuccess:function(data, textStatus, jqXHR)
            {
                if(data.action == 'delete')
                {
                    $('#'+data.id).remove();
                }
            }
        });

    });
    </script>
{{--###################### End Search Data User ####################################### --}}


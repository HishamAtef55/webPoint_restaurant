<script>
let _token = $('input[name="_token"]').val();


{{--###################### Start srarch item  ##################################### --}}

$(document).ready(function()
    {
        $('#select_subgroup').on('change',function () {
            $('#select_items').empty();
            let branch    = $('#select_branch').val();
            let menu      = $('#select_menu').val();
            let group     = $('#select_group').val();
            let sub_group = $(this).val();
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('search.select.item')}}",
                    'data':
                    {
                        _token: "{{csrf_token()}}",
                        branch: branch,
                        menu  : menu,
                        group : group,
                        sub_group : sub_group
                    },
                    success:function(data)
                    {
                        var html = '';
                        html += '<option value=""></option>';
                        for(var count = 0 ; count < data.length ; count++)
                        {
                            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
                        }
                        $('#select_items_sub').html(html);
                        getOptions ($('#select_items_sub'));
                    },
                });
        });
    });
{{--###################### End srarch item  ####################################### --}}

{{--###################### Start Search extra  ########################### --}}

    $(document).ready(function(){
        $('#extra_search').keyup(function ()
        {

            var query = $(this).val();
            var ID = $('#select_branch').val();
            if(query != '')
            {


                $.ajax({
                    url:"{{route('search.select.extra')}}",
                    method:'post',
                    data:{ID:ID ,query:query, _token:_token},

                    success:function(data)
                    {
                        if(data == '')
                        {

                        }
                        else
                        {
                            $('#view_extra').show();
                            var html = '';
                            for(var count = 0 ; count < data.length ; count++)
                            {
                                html += '<option name="'+data[count].name+'" value="'+data[count].id+'">'+data[count].name+'';
                                html += '</option>';
                        }
                        }$('#view_extra').html(html);
                    }
                });
            }else{
                $('#view_extra').hide();
            }
        });
    });

{{--###################### Start Search extra   ########################### --}}

{{--###################### Start add extra   ########################### --}}
$(document).on('click','#export_extra',function (e)
{
    e.preventDefault();

    function createRow(id, name) {

        if(name && id) {
            for (let x = 1; x < name.length; x++ ) {

                if($(`tr[row_id=${id[x-1]}]`).length != 1) {

                    let row = $(`<tr row_id='${id[x-1]}'></tr>`),

                    numID = $(`<td>${id[x-1]}</td>`),
                    extraName = $(`<td>${name[x-1]}</td>`),
                    price = $(`<td> <input type='number' value='0' /> </td>`),
                    saveButton = $(`<td> <button  row_id='${id[x-1]}' class='btn btn-primary row_table_update'>Update</button><button  row_id='${id[x-1]}' class='btn btn-danger row_table_delete'>Delete</button></td>`)

                    row.appendTo($('#editable tbody'));

                    numID.appendTo(row);
                    extraName.appendTo(row);
                    price.appendTo(row);
                    saveButton.appendTo(row);
                }
            }
        }
    }

    let extraItem   = Array.from($('#view_extra option:selected')),
        extraName   = [];
    let branch    = $('#select_branch').val();
    let menu      = $('#select_menu').val();
    let group     = $('#select_group').val();
    let sub_group = $('#select_subgroup').val();
    let extra       = $('#view_extra').val();
    let item        = $('#select_items_sub option:selected').val();

    extraItem.forEach(item => {
        extraName.push(item.getAttribute('name'))
    });


    if (item) {
        createRow(extra, extraName)

        console.log('hi')
    }
    console.log(branch);
    $.ajax({
        url      :"{{route('export.extra')}}",
        method   :'post',
        enctype  :"multipart/form-data",
        data     :
        {
            _token    :_token,
            branch    : branch,
            menu      : menu,
            group     : group,
            sub_group : sub_group,
            item      :item,
            extra     :extra,
        },
        success: function (data)
        {
            if(data.status == true)
            {
                Swal.fire({
                position: 'center-center',
                icon: 'success',
                title: 'Your Extra Export in item',
                showConfirmButton: false,
                timer: 1250
              });
            }

            if(data.status == false)
            {
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Your Extra already exists!',
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
{{--###################### end add extra   ########################### --}}

{{--###################### Start get extra item extra   ########################### --}}
$(document).ready(function()
    {
        $('#select_items_sub').on('change',function () {
            $('#tbody').empty();
            var item = $(this).val();
            if(item)
            {
                $.ajax({
                    'type':'POST',
                    'url':"{{Route('get.item.extra')}}",
                    'data':{
                        '_token': "{{csrf_token()}}",
                        'item': item},
                    success:function(data)
                    {
                        let html = '';
                        for(var count = 0 ; count < data.length ; count ++)
                        {
                            for(var i = 0 ; i < data[count].extra.length ; i ++)
                            {
                                html += '<tr row_id="'+data[count].extra[i].id+'">';
                                    html +=`<td>${data[count].extra[i].id}</td>`;
                                    html +=`<td>${data[count].extra[i].name}</td>`;
                                    html +=`<td> <input type='number' value='${data[count].extra[i].pivot.price}' /> </td>`;
                                    html +=`<td><button  row_id='${data[count].extra[i].id}' class='btn btn-primary row_table_update'>Update</button><button  row_id='${data[count].extra[i].id}' class='btn btn-danger row_table_delete'>Delete</button></td>`;
                                html +='</tr>';
                            }
                        }$('tbody').html(html);
                    },
                });
            }
        });
    });
{{--###################### end get extra item  ########################### --}}

{{--###################### Start update extra   ########################### --}}

$(document).on('click', '.row_table_update', function (e) {
    e.preventDefault();
    let extra      = $(this).attr('row_id');
    let tdArr      = Array.from($(this).parents('tr').children('td'));
    let price      = tdArr[2].childNodes[1].value;
    let item       = $('#select_items_sub option:selected').val();
    $.ajax({
        url: "{{route('update.export.extra')}}",
        method: 'post',
        data:
        {
            _token           : _token,
            price            : price,
            extra            : extra,
            item             : item
        },
        success: function (data)
        {
            if(data.status == true) {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Your Extra Export in item',
                    showConfirmButton: false,
                    timer: 1250
                });
            }

        }
    });
});
{{--###################### end update extra   ########################### --}}



{{--###################### Start delete extra   ########################### --}}
$(document).ready(function(){

$.ajaxSetup({
    headers:{
        'X-CSRF-Token' : $("input[name=_token]").val()
    }
});


$(document).on('click', '.row_table_delete', function (e)
{
e.preventDefault();
let extra      = $(this).attr('row_id');
let item       = $('#select_items option:selected').val();

$.ajax({
    url: "{{route('delete.export.extra')}}",
    method: 'post',
    data:
    {
        _token            : _token,
        extra             : extra,
        item              : item
    },
    success: function (data)
    {
        if(data.status == true)
            {
                $(`tr[row_id=${extra}]`).remove();
            }
    }
});
});

});
{{--###################### end delete extra   ########################### --}}
</script>

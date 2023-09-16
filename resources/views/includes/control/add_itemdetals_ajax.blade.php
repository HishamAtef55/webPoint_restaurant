<script>
    function getOptions(select) {
        select.children('option').each(function() {
            let text = $(this).text().split(' ').join('-');

            $(this).attr('data-value', text);
        });

        let options = Array.from(select.children('option'));

        for ( let i = 0; i < options.length ; i++ ) {
            let item = $(`<li class="item" data-value='${options[i].value.split(' ').join('-')}'></li>`);

            item.text(options[i].text);

            item.appendTo(select.next('.search-select').find('ul'))
        }
    }
    {{--################## Start Search Items Details ###########################--}}
    $(document).ready(function ()
    {
        $('#select').on('change',function ()
        {
            $('#select_data').empty();
            $('#editable tbody') .empty();
            $('#select1').empty();
            var branchID = $(this).val();
            let Branch    = $('#select_branch').val();
            if(branchID)
            {
                $.ajax({
                   'type':'POST',
                   'url':"{{Route('item.detalis')}}",
                    'data':
                        {
                            '_token': "{{csrf_token()}}",
                            'select': branchID,
                            Branch  : Branch
                        },
                    success:function(data)
                    {
                        var html = '';
                        html +='<option></option>';
                        for(var count = 0 ; count < data.length ; count ++)
                        {

                            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';

                        }$('#select_data').html(html);
                        getOptions ($('#select_data'));
                    },
                });
            }
        });
    })
    {{--################## End Search Items Details  ###########################--}}



    {{--################## Start Search Items Details ###########################--}}
    $(document).ready(function ()
    {
        $('#select_data').on('change',function ()
        {
            let type       = $('#select option:selected').val();
            let item_id    = $(this).val();
            if(item_id)
            {
                $.ajax({
                   'type':'POST',
                   'url':"{{Route('extract.details.table')}}",
                    'data':
                        {
                            '_token': "{{csrf_token()}}",
                            'select': item_id,
                            'type'  : type
                        },
                    success:function(data)
                    {
                        let html = '';
                        for(var count = 0 ; count < data.length ; count ++)
                        {
                            for(var i = 0 ; i < data[count].details.length ; i ++)
                            {
                                html += '<tr row_id="'+data[count].details[i].id+'">';
                                    html +=`<td>${data[count].details[i].id}</td>`;
                                    html +=`<td>${data[count].details[i].name}</td>`;
                                    html +=`<td> <input type='number' value='${data[count].details[i].pivot.price}' /> </td>`;
                                    html +=`<td max="${data[count].details[i].pivot.max}"><input type='text' value="${data[count].details[i].pivot.section}" /></td>`;
                                    html +=`<td><input type='number' value='${data[count].details[i].pivot.max}' /></td>`;
                                    html +=`<td>${data[count].name}</td>`;
                                    html +=`<td><button  row_id='${data[count].details[i].id}' class='btn btn-primary row_table_update'>Update</button><button  row_id='${data[count].details[i].id}' class='btn btn-danger row_table_delete'>Delete</button></td>`;
                                html +='</tr>';
                            }
                        }$('tbody').html(html);

                    },
                });
            }
        });
    })
    {{--################## End Search Items Details  ###########################--}}


    {{--################## Strat Search Items Details  #############################--}}
    $(document).ready(function(){
        $('#details-name').keyup(function ()
        {
            $('#select_datatable').empty();
            $('#select2').empty();
            var _token = $('input[name="_token"]').val();

            var html = '';

            var query = $(this).val();
            if(query != '')
            {
                $.ajax({
                    url:"{{route('Search.item.details')}}",
                    method:'post',
                    data:{query:query, _token:_token},
                    success:function(data)
                    {
                        for(var count = 0 ; count < data.length ; count ++)
                        {
                            html+='<option value="'+data[count].id+'">'+data[count].name+'</option>'

                        }$('#select_datatable').html(html);
                        // getOptions ($('#select_datatable'));
                    }
                });
            }
        });
    });
    {{--################## End Search Items Details  #############################--}}
    $(document).on('click','#save_new_details',function (e)
    {
        e.preventDefault();
        let Branch    = $('#select_branch').val();
        let Details   = $('#details-name').val();
        let _token    = $('input[name="_token"]').val();

        let myInputs = $(this).parent().find('.input-empty input');
        let myLabels = $(this).parent().find('.input-empty label');
        let myLines = $(this).parent().find('.input-empty span');

        $.ajax({
            url: "{{route('save.details')}}",
            method: 'post',
            enctype: "multipart/form-data",
            data: {_token: _token,Branch:Branch,Details:Details},
            success: function (data) {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Your Details has been saved',
                    showConfirmButton: false,
                    timer: 1250
                });
                // For Remove All Values After Save ====== Start
                myInputs.val('')

                myLabels.removeClass('focused')

                myLines.removeClass('fill')
            }
        });
     });

    function createRow(name, section, max, id, nameItem, rows) {

        if(name && section && max && id) {
            let newsection = section.replace(' ', '_');
            for (let x = 1; x <= rows; x++ ) {
                if($(`tr[row_id=${id[x-1]}]`).length != 1) {
                    let row            = $(`<tr row_id='${id[x-1]}'></tr>`),
                        numID          = $(`<td>${id[x-1]}</td>`),
                        detailsName    = $(`<td>${name[x-1]}</td>`),
                        price          = $(`<td> <input type='number' value='0' /> </td>`),
                        detailsSection = $(`<td max="${max}"><input type="text" value="${newsection}" /></td>`),
                        detailsMax     = $(`<td><input tyoe="text" value="${max}" /></td>`),
                        itemName       = $(`<td>${nameItem}</td>`)
                        saveButton     = $(`<td> <button  row_id='${id[x-1]}' class='btn btn-primary row_table_update'>Update</button><button  row_id='${id[x-1]}' class='btn btn-danger row_table_delete'>Delete</button></td>`)

                    row.appendTo($('#editable tbody'));

                    numID.appendTo(row);
                    detailsName.appendTo(row);
                    price.appendTo(row);
                    detailsSection.appendTo(row);
                    detailsMax.appendTo(row);
                    itemName.appendTo(row);
                    saveButton.appendTo(row);
                }
            }
        }
    }

    $(document).on('click', '#add_to_table', function (e) {
        e.preventDefault();
        let _token           = $('input[name="_token"]').val();
        let details_select   = Array.from($('#select_datatable option:selected')),
            details_name     = [],
            id_details       = [],
            details_section  = $('#section_').val(),
            max              = $('#max').val();
        let id_item          = $('#select_data option:selected').val();
        let type             = $('#select option:selected').val();
        let ItemName         = $('#select_data option:selected').data('value');
        let branch           =$('#select_branch').val();

        let allInputs = $(this).parents('.row').find('.input-empty input');
        let allLabels = $(this).parents('.row').find('.input-empty label');
        let allLines = $(this).parents('.row').find('.input-empty span');

        details_select.forEach(details => {
            details_name.push(details.textContent)
            id_details.push(details.value)
        });

        createRow(details_name, details_section, max, id_details, ItemName,details_select.length);

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



        $.ajax({
            url: "{{route('export.details')}}",
            method: 'post',
            data:
            {
                _token           : _token,
                branch           : branch,
                id_item          : id_item,
                type             : type,
                details_section  : details_section,
                max              : max,
                id_details       : id_details
            },
            success: function (data)
            {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Your Details Export In Item',
                    showConfirmButton: false,
                    timer: 1250
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
        $(document).on('click', '.row_table_update', function (e) {
            e.preventDefault();
            let _token     = $('input[name="_token"]').val();
            let Details_ID = $(this).attr('row_id');
            let tdArr      = Array.from($(this).parents('tr').children('td'));
            let price      = tdArr[2].childNodes[1].value;
            let section    = tdArr[3].firstElementChild.value;
            let max        = tdArr[4].firstElementChild.value;
            let idItem     = $('#select_data option:selected').val();
            let type       = $('#select option:selected').val();
            let branch     =$('#select_branch').val();

            let route      ='update.dettails.price';
            $.ajax({
                url: "{{route('update.dettails.price')}}",
                method: 'post',
                data:
                {
                    _token           : _token,
                    price            : price,
                    type             : type,
                    section          : section,
                    max              : max,
                    Details_ID       : Details_ID,
                    idItem           : idItem,
                    branch           : branch,
                },
                success: function (data)
                {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Updated',
                    showConfirmButton: false,
                    timer: 1250
                  });
                }
            });
        });

        $(document).on('click', '.row_table_delete', function (e) {
        e.preventDefault();
        let _token     = $('input[name="_token"]').val();
        let Details_ID = $(this).attr('row_id');
        let tdArr      = Array.from($(this).parents('tr').children('td'));
        let idItem     = $('#select_data option:selected').val();
        let type       = $('#select option:selected').val();
        let branch     =$('#select_branch').val();

        let route      ='update.dettails.price';
        $.ajax({
            url: "{{route('delete.item.details')}}",
            method: 'post',
            data:
            {
                _token           : _token,
                type             : type,
                Details_ID       : Details_ID,
                idItem           : idItem,
                branch           : branch,
            },
            success: function (data)
            {
                $(`tr[row_id=${Details_ID}]`).remove();
            }
        });
    });
    });
</script>


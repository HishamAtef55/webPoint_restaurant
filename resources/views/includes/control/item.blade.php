<script type="text/javascript">
$(document).ready(function(){
	let _token = $('input[name="_token"]').val();
	{{--###################### Start Save Item  ##################################### --}}
    $(document).on('click','#save_item',function (e)
    {
        e.preventDefault();
        let allInputs = $(this).parents('form').find('.input-empty input');
        let textArea = $(this).parents('form').find('textarea');
        let allLabels = $(this).parents('form').find('.input-empty label');
        let allLines = $(this).parents('form').find('.input-empty span');

        let formData      = new FormData($('#form_save_item')[0]);

        $.ajax({
            url:"{{route('save.item')}}",
            method:'post',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data' : formData,
            success: function (data)
            {
                if(data.status == true)
                {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    })

                    Toast.fire({
                        icon: 'success',
                        title: 'Your Items has been saved'
                    })

                    // For Remove All Values After Save ====== Start
                    allInputs.each(function() {
                        $(this).val('')
                    });

                    $('#printers option').each(function() {
                        $(this).prop('selected', false)
                    });

                    textArea.val('')

                    $('i.reset').click();

                    $('.custom-control #extra').prop('checked', false)

                    allLabels.each(function() {
                        $(this).removeClass('focused')
                    });

                    allLines.each(function() {
                        $(this).removeClass('fill')
                    });
                    $('#item-name').focus();
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

    $(document).on('click','#item_without',function (e)
    {
        e.preventDefault();
        let branch = $('#select_branch').val()
        $(".label-model").text('');
        $(".listofitem").html('');
        $.ajax({
            url:"{{route('itemWithOutPrinter')}}",
            method:'post',
            data: {
                _token    : _token,
                branch   : branch,
            },
            success: function (data)
            {
                let html ='';
                let label = 'Item Without Printers';
                html +=`<ul class="list-group">`
                for(var count = 0 ; count < data.items.length ; count ++) {
                    html+=`<li class="list-group-item">${data.items[count].chick_name}</li>`;
                }
                html +=`</ul>`
                $(".label-model").text(label);
                $(".listofitem").html(html);
                $('#staticBackdrop').modal('show')
            }
        });
    });

    $(document).on('click','#update_item',function (e)
    {
        let allInputs = $(this).parents('form').find('.input-empty input');
        let textArea = $(this).parents('form').find('textarea');
        let allLabels = $(this).parents('form').find('.input-empty label');
        let allLines = $(this).parents('form').find('.input-empty span');

        let formData      = new FormData($('#form_save_item')[0]);
        e.preventDefault();
        $.ajax({
            url:"{{route('tableitem.action')}}",
            method:'post',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data' : formData,
            success: function (data)
            {
                if(data.status == 'true')
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Items has been saved',
                        showConfirmButton: false,
                        timer: 1250
                    });

                    // For Remove All Values After Save ====== Start
                    allInputs.each(function() {
                        $(this).val('')
                    });
                    $('#printers option').each(function() {
                        $(this).prop('selected', false)
                    });

                    textArea.val('')

                    $('i.reset').click();

                    $('.custom-control input[type="checkbox"]').prop('checked', false)

                    allLabels.each(function() {
                        $(this).removeClass('focused')
                    });

                    allLines.each(function() {
                        $(this).removeClass('fill')
                    });

                    $('#save_item').removeClass('d-none')
                    $('#update_item').addClass('d-none')
                    $('#delete_item').addClass('d-none')
                    $('#item_without').removeClass('d-none')
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

    {{--###################### End Save Item  ####################################### --}}

    $('#item-name').keyup(function () {
        let html      = '';
        let query     = $(this).val();
        let branch    = $('#select_branch').val();
        let menu      = $('#select_menu').val();
        let group     = $('#select_group').val();
        let sub_group = $('#select_subgroup').val();
        if(query != '') {
            $.ajax({
                url:"{{route('search.item')}}",
                method:'post',
                data:
                {
                    _token    : _token,
                    query     : query,
                    branch    : branch,
                    menu      : menu,
                    group     : group,
                    sub_group : sub_group,
                },
                success:function(data)
                {
                    for(var count = 0 ; count < data.length ; count ++)
                    {
                        html+=`<li id="${data[count].id}">${data[count].name}</li>`
                    }
                    if (query == '') {
                        $('#list-of-items').html('');
                    } else {
                        $('#list-of-items').html(html);
                    }

                }
            });
        }
        else
        {
            $('#list-of-items').html('');
        }
    });

    $(document).on('click','#delete_item', function (e) {
        e.preventDefault();
        let allInputs = $(this).parents('form').find('.input-empty input');
        let textArea = $(this).parents('form').find('textarea');
        let allLabels = $(this).parents('form').find('.input-empty label');
        let allLines = $(this).parents('form').find('.input-empty span');

        let html      = '';
        let id_item = $('#item-id').val();
        $('#tbody').show();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then( (result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url:"{{route('del_item_action')}}",
                    method:'post',
                    data:
                    {
                        _token    : _token,
                        id_item   : id_item,
                    },
                    success:function(data)
                    {
                        if(data.status == 'true')
                        {
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            })

                            Toast.fire({
                                icon: 'success',
                                title: 'Your Items has been Deleted'
                            })

                            // For Remove All Values After Save ====== Start
                            allInputs.each(function() {
                                $(this).val('')
                            });

                            $('#printers option').each(function() {
                                $(this).prop('selected', false)
                            });

                            textArea.val('')

                            $('i.reset').click();

                            $('.custom-control input[type="checkbox"]').prop('checked', false)

                            allLabels.each(function() {
                                $(this).removeClass('focused')
                            });

                            allLines.each(function() {
                                $(this).removeClass('fill')
                            });
                            $('#item-name').focus();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: "this Item Can Not Delete",
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click','#list-of-items li', function (e) {
        e.stopPropagation()
        let html      = '';
        let id_item = $(this).attr('id')
        $.ajax({
            url:"{{route('search.item_new_up')}}",
            method:'post',
            data: {
                _token    : _token,
                id_item   : id_item,
            },
            success:function(data)
            {
                let barcodeArr = []
                data.items.barcode.forEach((x) => barcodeArr.push(x.barcode));
                // $('#printers-input-shift').focus().val(data[0].printer_shift);
                $('#save_item').addClass('d-none')
                $('#update_item').removeClass('d-none')
                $('#delete_item').removeClass('d-none')
                $('#item_without').addClass('d-none')
                $('#table-price').focus().val(data.items.price)
                $('#item-name').focus().val(data.items.name)
                $('#item-slep-name').focus().val(data.items.slep_name)
                $('#realname').focus().val(data.items.slep_name)
                $('#item-chick-name').focus().val(data.items.chick_name)
                $('#item-id').val(data.items.id)
                $('#take-away-price').focus().val(data.items.takeaway_price)
                $('#dellvery-price').focus().val(data.items.dellvery_price)
                $('#cost-price').focus().val(data.items.cost_price)
                $('#during-time').focus().val(data.items.time_during)
                $('#calories-time').focus().val(data.items.calories)
                $('#Items-wight').focus().val(data.items.wight)
                $('#unit-input').parents('.search-select').find(`li[data-value='${data.items.unit}']`).click();
                $('#note').focus().val(data.items.note)
                $('#barcode').focus().val(barcodeArr.join('+'))

                $('#item-image').html(`<label for="image" class="image" style="background-image:url({{ URL::asset('control/images/items') }}/${data.items.image})"></label>`);
                $('#list-of-items').html("")

                $('#item-image').html(`<label for="image" class="image unvisibile" style="background-image:url({{ URL::asset('control/images/items') }}/${data.items.image})"></label>`);
                // $('#image').onchange();
                for(var p_count = 0 ; p_count < data.items.printer.length ; p_count ++) {
                    $(`#printers option[value='${data.items.printer[p_count].printer}'`).prop('selected', true)
                }
                if(data.extra == 1){$('#extra').prop('checked',true)}else{$('#extra').prop('checked',false)}
                if(data.items.active == 1){$('#active').prop('checked',true)}else{$('#active').prop('checked',false)}
            }
        });
        $("#list-of-items").html('');
    });

    $(document).on('click', function() {
        $("#list-of-items").html('');
    })

    $(document).on('click', '#edit-btn', function() {
        let showHideParent = $(this).parents('tr').find('#show-hide');

        let spanValue      = showHideParent.children('span');

        let mySelect      = showHideParent.children('select');
        $('#active').prop('checked',true);
        mySelect.children(`option[value='${spanValue.text()}']`).prop('selected', true)

    });

    $(document).on('click','#show_items',function (e)
    {
        e.preventDefault();
        let branch = $('#select_branch').val()
        let group = $('#select_group').val()
        let subgroup_check = $('#select_subgroup').val() | 0;
        $(".label-model").text('');
        $(".listofitem").html('');
        if(branch == '') {
            Swal.fire({
                position: 'center-center',
                icon: 'error',
                title: 'Please Select Branch',
                showConfirmButton: true,
            });
            return false
        }
        $.ajax({
            url:"{{route('show_all_item')}}",
            method:'post',
            data: {
                _token,
                branch,
                group,
            },
            success: function (data)
            {
                let html ='';
                let label ='All Items';
                let count = 1;
                html+=`<div class="table-responsive" style=" margin-bottom: 0;"><table class="table items-table">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">name</th>
                              <th scope="col">price</th>
                              <th scope="col">group</th>
                              <th scope="col">subGroup</th>
                            </tr>
                          </thead>
                          <tbody>`;

                data.groups.forEach((group) => {
                    group.supgroups.forEach((subgroup) => {
                        subgroup.items.forEach((item) => {
                            if(subgroup_check != 0){
                                if(subgroup_check == subgroup.id){
                                    html+=`<tr>
                                      <td>${count++}</td>
                                      <td>${item.name}</td>
                                      <td>${item.price}</td>
                                      <td>${group.name}</td>
                                      <td>${subgroup.name}</td>
                                  </tr>`
                                }
                            }else{
                                html+=`<tr>
                                      <td>${count++}</td>
                                      <td>${item.name}</td>
                                      <td>${item.price}</td>
                                      <td>${group.name}</td>
                                      <td>${subgroup.name}</td>
                                  </tr>`
                            }

                        });
                    })
                });

                html +=`</tbody>
                          </table></div>`;
                $(".label-model").text(label);
                $(".listofitem").html(html);
                $('#staticBackdrop').modal('show')
                $('.items-table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            orientation: 'landscape',
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    $(document).on('click','#update_items_price',function (e){
        e.preventDefault();
        let branch = $('#select_branch').val()
        let group = $('#select_group').val()
        let subgroup_check = $('#select_subgroup').val() | 0;
        $(".label-model").text('');
        $(".listofitem").html('');
        if(branch == '') {
            Swal.fire({
                position: 'center-center',
                icon: 'error',
                title: 'Please Select Branch',
                showConfirmButton: true,
            });
            return false
        }
        $.ajax({
            url:"{{route('show_all_item')}}",
            method:'post',
            data: {
                _token,
                branch,
                group,
            },
            success: function (data)
            {
                let html ='';
                let label ='Update Price';
                let count = 1;
                let colorBut = "btn-success";
                let statusBut = "Enable";
                html+=`<div class="table-responsive" style=" margin-bottom: 0;"><table class="table items-table">
                          <thead>
                            <tr>
                              <th class="text-center" scope="col">#</th>
                              <th colspan="2" class="text-center" scope="col">name</th>
                              <th class="text-center" scope="col">cost price</th>
                              <th class="text-center" scope="col">price</th>
                              <th class="text-center" scope="col">delivery price</th>
                              <th class="text-center" scope="col">togo price</th>
                              <th class="text-center" scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>`;

                data.groups.forEach((group) => {
                    group.supgroups.forEach((subgroup) => {
                        subgroup.items.forEach((item) => {
                            if(item.active == 1){
                                if(item.active == "0"){
                                    colorBut = "btn-danger";
                                    statusBut = "Disable";
                                }else{
                                    colorBut = "btn-success";
                                    statusBut = "Enable";
                                }
                                if(subgroup_check != 0){
                                    if(subgroup_check == subgroup.id){
                                        html+=`<tr itemid="${item.id}">
                                        <td>${count++}</td>
                                        <td colspan="2"><input type="text" class="text-center form-control nameUpdate" value="${item.name}"></td>
                                        <td><input type='text' class="text-center costPriceUpdate" value="${item.cost_price}"></td>
                                        <td><input type='text' class="text-center priceUpdate" value="${item.price}"></td>
                                        <td><input type='text' class="text-center updateDeliveryPrice" id="updateDeliveryPrice_${item.id}" value="${item.dellvery_price}"></td>
                                        <td><input type='text' class="text-center updateTogoPrice" id="updateTogoPrice_${item.id}" value="${item.takeaway_price}"></td>
                                        <td>
                                                <button class="btn btn-primary updatePriceAll color_${item.id}" value="${item.id}">update</button>
                                                <button class="btn ${colorBut} updateActiveAll active_${item.id}" value="${item.id}">${statusBut}</button>
                                        </td>
                                    </tr>`
                                    }
                                }else{
                                    html+=`<tr itemid="${item.id}">
                                        <td>${count++}</td>
                                        <td colspan="2"><input type="text" class="text-center nameUpdate" value="${item.name}"></td>
                                        <td><input type='text' class="text-center costPriceUpdate" value="${item.cost_price}"></td>
                                        <td><input type='text' class="text-center priceUpdate" value="${item.price}"></td>
                                        <td><input type='text' class="text-center updateDeliveryPrice" id="updateDeliveryPrice_${item.id}" value="${item.dellvery_price}"></td>
                                        <td><input type='text' class="text-center updateTogoPrice" id="updateTogoPrice_${item.id}" value="${item.takeaway_price}"></td>
                                        <td>
                                                <button class="btn btn-primary updatePriceAll color_${item.id}" value="${item.id}">update</button>
                                                <button class="btn ${colorBut} updateActiveAll active_${item.id}" value="${item.id}">${statusBut}</button>
                                        </td>
                                    </tr>`
                                }
                            }
                        });
                    })
                });

                html +=`</tbody>
                          </table></div>`;
                $(".label-model").text(label);
                $(".listofitem").html(html);
                $('#staticBackdrop').modal('show')
                $('.items-table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            orientation: 'landscape',
                        },
                        'pageLength',
                    ]
                });
            }
        });
    })

    $(document).on('click','#update_itemsNotActive',function (e){
        e.preventDefault();
        let branch = $('#select_branch').val()
        let group = $('#select_group').val()
        let subgroup_check = $('#select_subgroup').val() | 0;
        $(".label-model").text('');
        $(".listofitem").html('');
        if(branch == '') {
            Swal.fire({
                position: 'center-center',
                icon: 'error',
                title: 'Please Select Branch',
                showConfirmButton: true,
            });
            return false
        }
        $.ajax({
            url:"{{route('show_all_item')}}",
            method:'post',
            data: {
                _token,
                branch,
                group,
            },
            success: function (data)
            {
                let html ='';
                let label ='Update Price';
                let count = 1;
                let colorBut = "btn-success";
                let statusBut = "Enable";
                html+=`<div class="table-responsive" style=" margin-bottom: 0;"><table class="table items-table">
                          <thead>
                            <tr>
                              <th class="text-center" scope="col">#</th>
                              <th colspan="2" class="text-center" scope="col">name</th>
                              <th class="text-center" scope="col">cost price</th>
                              <th class="text-center" scope="col">price</th>
                              <th class="text-center" scope="col">delivery price</th>
                              <th class="text-center" scope="col">togo price</th>
                              <th class="text-center" scope="col">Action</th>
                            </tr>
                          </thead>
                          <tbody>`;

                data.groups.forEach((group) => {
                    group.supgroups.forEach((subgroup) => {
                        subgroup.items.forEach((item) => {
                            if(item.active == 0){
                                if(item.active == "0"){
                                    colorBut = "btn-danger";
                                    statusBut = "Disable";
                                }else{
                                    colorBut = "btn-success";
                                    statusBut = "Enable";
                                }
                                if(subgroup_check != 0){
                                    if(subgroup_check == subgroup.id){
                                        html+=`<tr itemid="${item.id}">
                                        <td>${count++}</td>
                                        <td colspan="2"><input type="text" class="text-center form-control nameUpdate" value="${item.name}"></td>
                                        <td><input type='text' class="text-center costPriceUpdate" value="${item.cost_price}"></td>
                                        <td><input type='text' class="text-center priceUpdate" value="${item.price}"></td>
                                        <td><input type='text' class="text-center updateDeliveryPrice" id="updateDeliveryPrice_${item.id}" value="${item.dellvery_price}"></td>
                                        <td><input type='text' class="text-center updateTogoPrice" id="updateTogoPrice_${item.id}" value="${item.takeaway_price}"></td>
                                        <td>
                                                <button class="btn btn-primary updatePriceAll color_${item.id}" value="${item.id}">update</button>
                                                <button class="btn ${colorBut} updateActiveAll active_${item.id}" value="${item.id}">${statusBut}</button>
                                        </td>
                                    </tr>`
                                    }
                                }else{
                                    html+=`<tr itemid="${item.id}">
                                        <td>${count++}</td>
                                        <td colspan="2"><input type="text" class="text-center nameUpdate" value="${item.name}"></td>
                                        <td><input type='text' class="text-center costPriceUpdate" value="${item.cost_price}"></td>
                                        <td><input type='text' class="text-center priceUpdate" value="${item.price}"></td>
                                        <td><input type='text' class="text-center updateDeliveryPrice" id="updateDeliveryPrice_${item.id}" value="${item.dellvery_price}"></td>
                                        <td><input type='text' class="text-center updateTogoPrice" id="updateTogoPrice_${item.id}" value="${item.takeaway_price}"></td>
                                        <td>
                                                <button class="btn btn-primary updatePriceAll color_${item.id}" value="${item.id}">update</button>
                                                <button class="btn ${colorBut} updateActiveAll active_${item.id}" value="${item.id}">${statusBut}</button>
                                        </td>
                                    </tr>`
                                }
                            }
                        });
                    })
                });

                html +=`</tbody>
                          </table></div>`;
                $(".label-model").text(label);
                $(".listofitem").html(html);
                $('#staticBackdrop').modal('show')
                $('.items-table').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            orientation: 'landscape',
                        },
                        'pageLength',
                    ]
                });
            }
        });
    })


    $(document).on('click','.updatePriceAll',function (e){
        e.preventDefault();
        let currentRow=$(this).closest("tr");
        let itemId = $(this).val()
        let name = currentRow.find("td:eq(1) input[type='text']").val();
        let costPrice = currentRow.find("td:eq(2) input[type='text']").val();
        let price = currentRow.find("td:eq(3) input[type='text']").val();
        let delPrice = currentRow.find("td:eq(4) input[type='text']").val();
        let togoPrice = currentRow.find("td:eq(5) input[type='text']").val();
        $.ajax({
            url:"{{route('update_item_price')}}",
            method:'post',
            data: {
                _token,
                itemId,
                costPrice,
                price,
                delPrice,
                togoPrice,
                name,
            },
            success: function (data)
            {
                if(data.status == true) {
                    $(`.color_${itemId}`).removeClass('btn-success')
                    $(`.color_${itemId}`).addClass('btn-warning')
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Your Items has been updated price'
                    })
                }
            }
        });
    })
    $(document).on('click','.updateActiveAll',function (e){
        e.preventDefault();
        let itemId = $(this).val()
        $.ajax({
            url:"{{route('update_item_active')}}",
            method:'post',
            data: {
                _token,
                itemId,
            },
            success: function (data)
            {
                if(data.status == true) {
                    if(data.item.active == 1 || data.item.active == "1"){
                        $(`.active_${itemId}`).removeClass('btn-danger')
                        $(`.active_${itemId}`).addClass('btn-success')
                        $(`.active_${itemId}`).html("Enable")
                    }else{
                        $(`.active_${itemId}`).addClass('btn-danger')
                        $(`.active_${itemId}`).removeClass('btn-success')
                        $(`.active_${itemId}`).html("Disable")
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Your Items has been updated Activation'
                    })
                }
            }
        });
    })

});
</script>

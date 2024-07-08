@include('includes.stock.Stock_Ajax.public_function')
<script>
    $(document).ready(function() {
        $('.stores_btns').on('click', function() {
            // let storeId = $(this).data('id');
            let action = $(this).data('action');
            let JsonMeta = [];
            let id = $('#store_id');
            let name = $('#store_name');
            let phone = $('#store_phone');
            let address = $('#store_address');
            let branch = 0;
            let methodChecks = $('input[name="storage_method"]:checked');
            let storages = [];
            let meta = [];
            let tbody = $('.table-data tbody');
            switch (action) {
                case 'create':
                    // validateData(name,phone,address,storages)
                    methodChecks.each(function() {
                        let type = $(this).val()
                        let trMethod = $(this).parents('tr');
                        let unit = trMethod.find('.unit').val();
                        let capacity = trMethod.find('input[name="capacity"]').val();

                        if (unit != null && capacity !== '') {
                            storages.push({
                                type: type,
                                unit: unit,
                                capacity: capacity
                            });
                        }
                    });
                    meta = {
                        name: name,
                        phone: phone,
                        address: address,
                    };
                    validateForm(meta)
                    break;
                case 'update':
                    break;
                case 'view':

                    break;
                case 'delete':
                    break;
                default:
                    console.error('Unknown action:', action);
                    return;
            }

            if (validateForm(meta)) {
                JsonMeta = collectJsonMeta(action, meta)
                JsonMeta.body.storages = storages
                // callApi(JsonMeta);
                $.ajax({
                    url: JsonMeta.endPoint,
                    method: JsonMeta.method,
                    DataType: 'json',
                    data: JsonMeta.body,
                    success: function(data) {
                        switch (action) {
                            case 'create':
                                if (data.status == 'true') {
                                    let html = '';
                                    tbody.empty();
                                    data.stores.forEach(store => {
                                        html += `<tr>
                    <th>${store.id}</th>
                    <td>${store.name || '-'}</td>
                    <td>${store.phone || '-'}</td>
                    <td>${store.address || '-'}</td>

                     <td>
                    <button title="تعديل" class="btn btn-success stores_btns"
                        data-id="${ store.id }"data-action="edit">

                        <i class="far fa-edit"></i>
                    </button>

                    <button title="عرض" data-id="${ store.id }" data-action="view"
                        class="btn btn-primary stores_btns">

                        <i class="fa fa-eye" aria-hidden="true"></i>

                    </button>
                    <button class="btn btn-danger stores_btns" data-action="delete"
                        data-id="${ store.id }">
                        <i class="fa fa-trash"></i>
                    </button>
                               </td>
                    </tr>`;
                                    })
                                    tbody.html(html);
                                    id.val(data.new_store);
                                    name.val('');
                                    phone.val('');
                                    address.val('');
                                    $('input[type="checkbox"]').each(function() {
                                        $(this).prop('checked', false)
                                    })
                                    $('.unit').each(function() {
                                        $(this).find(' option:first-child')
                                            .prop('selected', true)
                                    })
                                    $('input[name="capacity"]').each(function() {
                                        $(this).val('')
                                    })

                                    successMsg(data.msg);
                                }
                            default:
                                console.error('Unknown action:', action);
                                return;
                        }
                    },
                    error: function(reject) {
                        let response = $.parseJSON(reject.responseText);
                        $.each(response.errors, function(key, val) {
                            errorMsg(val[0])
                        });
                    }
                });
            }

        });
    });



    $('#store_name').on('keyup', function() {
        let query = $(this).val()
        searchDb('search_store', query, $(this));
    });

    $(document).on('click', '.search-result li', function(e) {
        e.stopPropagation();
        getData('get_store', $(this).attr('data-id'), function(data) {
            id.val(data.id);
            name.val(data.name);
            phone.val(data.phone);
            address.val(data.address);
            $('input[type="checkbox"]').each(function() {
                $(this).prop('checked', false)
            })
            $('.unit').each(function() {
                $(this).find(' option:first-child').prop('selected', true)
            })
            $('input[name="capacity"]').each(function() {
                $(this).val('')
            })
            data.storgecapacity.forEach(storage => {
                let tableRow = $(`.method-check[value='${storage.type}']`).parents('tr');
                tableRow.find('input[type="checkbox"]').prop('checked', true);
                tableRow.find('input[type="text"]').val(storage.capacity);
                tableRow.find(`select option[value='${storage.unit}']`).prop('selected', true)
            });
            $('#save_store').addClass('d-none');
            $('#update_store').removeClass('d-none');
            $('.search-result').html('');
        });
    });

    $('#update_store').on('click', function() {
        let methodChecks = $('input[name="storage_method"]:checked');
        let storages = [];
        methodChecks.each(function() {
            let type = $(this).val()
            let trMethod = $(this).parents('tr');
            let unit = trMethod.find('.unit').val();
            let capacity = trMethod.find('input[name="capacity"]').val();

            if (unit != null && capacity !== '') {
                storages.push({
                    type: type,
                    unit: unit,
                    capacity: capacity
                });
            }
        });

        $.ajax({
            url: "{{ route('update.store') }}",
            method: 'post',
            data: {
                _token: "{{ csrf_token() }}",
                id: id.val(),
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                // storages,
            },
            success: function(data) {
                if (data.status == 'true') {
                    let html = '';
                    tbody.empty();
                    data.stores.forEach(store => {
                        html += `<tr>
                        <th>${store.id}</th>
                        <td>${store.name || '-'}</td>
                        <td>${store.phone || '-'}</td>
                        <td>${store.address || '-'}</td>
                    </tr>`;
                    })
                    tbody.html(html);
                    id.val(data.new_store);
                    name.val('');
                    phone.val('');
                    address.val('');
                    $('input[type="checkbox"]').each(function() {
                        $(this).prop('checked', false)
                    })
                    $('.unit').each(function() {
                        $(this).find(' option:first-child').prop('selected', true)
                    })
                    $('input[name="capacity"]').each(function() {
                        $(this).val('')
                    })
                    $('#save_store').removeClass('d-none');
                    $('#update_store').addClass('d-none');
                    successMsg(data.msg);
                    checkForm();
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0]);
                });
            }
        });
    });

    $(document).on('click', '#delete_store', function() {
        const id = $(this).data('id');
        swal({
                title: 'Delete !',
                text: 'Are you sure you want to delete the store ?',
                type: 'warning',
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
            },
            function() {
                $.ajax({
                    url: "{{ route('delete.store') }}",
                    method: 'post',
                    DataType: 'json',
                    data: {
                        store_id: id,
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response)
                        return false;
                        swal({
                            title: 'Deleted!',
                            text: 'Your store has been deleted successfully.',
                            type: 'success',
                            timer: 2000,

                        });
                        $('#cancelMembership').remove();
                        $('#mobile-group-container').remove();



                    },
                    error: function(error) {
                        swal({
                            title: 'Error!',
                            text: error.responseJSON.message,
                            type: 'error',
                            timer: 5000,
                        })
                    }
                });
            });
    });
</script>

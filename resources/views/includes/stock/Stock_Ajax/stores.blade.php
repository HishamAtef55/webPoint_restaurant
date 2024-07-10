@include('includes.stock.Stock_Ajax.public_function')
<script>
    let id = $('#store_id');
    let name = $('#store_name');
    let phone = $('#store_phone');
    let address = $('#store_address');
    let tbody = $('.table-data tbody');
    let branch = 0;

    $('#save_store').on('click', function() {
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
            url: "{{ route('save.store') }}",
            method: 'post',
            data: {
                _token,
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                storages,
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

                    successMsg(data.msg);
                    checkForm();
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0])
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
                _token,
                id: id.val(),
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                storages,
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
</script>

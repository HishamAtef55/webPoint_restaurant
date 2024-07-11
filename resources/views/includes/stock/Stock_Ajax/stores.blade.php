@include('includes.stock.Stock_Ajax.public_function')
<script>
    let id = $('#store_id');
    let name = $('#store_name');
    let phone = $('#store_phone');
    let address = $('#store_address');
    let tbody = $('.table-data tbody');
    let branch = 0;
    // create store
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
            type: 'POST',
            url: "{{ route('stock.stores.store') }}",
            dataType: 'json',
            data: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                storages: storages,
            },
            ContentType: false,
            processData: false,
            success: function(data) {
                if (data.status == 200) {
                    let html = '';
                    tbody.empty();
                    data.stores.forEach(store => {
                        html += `<tr>
                            <th>${store.id}</th>
                            <td>${store.name || '-'}</td>
                            <td>${store.phone || '-'}</td>
                            <td>${store.address || '-'}</td>
                            <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${store.id}" id="edit_store">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${store.id}" id="view_store"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_store"
                                        data-id="${store.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                            </td>
                        </tr>`;
                    })
                    tbody.html(html);
                    id.val(data.id);
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

                    successMsg(data.message);
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

    // delete store
    $(document).on('click', '#delete_store', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        Swal.fire({
            title: 'حذف !',
            text: 'هل انت متأكد من حذف المخزن',
            icon: 'warning',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            confirmButtonColor: '#5cb85c',
            cancelButtonColor: '#d33',
            cancelButtonText: 'لا',
            confirmButtonText: 'نعم',
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url('stock/stores', '') }}' + '/' + id,
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            id: id,
                        },
                        ContentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status == 200) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000
                                });
                                row.remove();
                                resolve();
                            }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                timer: 5000
                            });
                            resolve();
                        }
                    });
                });
            }
        });
    });

    // view store
    $(document).on('click', '#view_store', function() {
        const id = $(this).data('id');

        $.ajax({
            type: 'GET',
            url: '{{ url('stock/stores', '') }}' + '/' + id,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            cache: false,
            ContentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 200) {
                    const store = response.data;
                    console.log(store);

                    // Populate the modal with the data
                    $('#id').val(store.id);
                    $('#name').val(store.name);
                    $('#phone').val(store.phone);
                    $('#address').val(store.address);

                    // Check the appropriate checkboxes and populate unit and capacity fields
                    $('input[name="storage_method_model"]').prop('checked', false);
                    $('select[name="storage_unit_model"]').empty();
                    $('input[name="storage_capacity_model"]').val('');

                    store.storage_methods.forEach(method => {
                        const methodElement = $(
                            `input[name="storage_method_model"][value="${method.type}"]`
                        );
                        methodElement.prop('checked', true);
                        const selectElement = methodElement.closest('tr').find(
                            'select[name="storage_unit_model"]');
                        selectElement.append(
                            `<option value="${method.unit}">${method.unit}</option>`);

                        methodElement.closest('tr').find(
                            'input[name="storage_capacity_model"]').val(method.capacity);
                    });

                    // Open the modal
                    $('#storeModal').modal('show');
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

    })
</script>

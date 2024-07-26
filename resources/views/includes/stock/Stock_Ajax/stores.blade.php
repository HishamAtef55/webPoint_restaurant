@include('includes.stock.Stock_Ajax.public_function')
<script>
    let tbody = $('.table-data tbody');
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );

    // handle csrf request header

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    })
    // Function to reset modal content
    function resetModal(modalId) {
        // Clear all input fields
        $(`${modalId} #id, ${modalId} #name, ${modalId} #phone, ${modalId} #address`).val('');

        $(` ${modalId} input[type="checkbox"]`).each(function() {
            // Uncheck the checkbox
            $(this).prop('checked', false);

            // Find the associated select element and reset it
            $(this).closest('tr').find('.unit').val($(this).closest('tr').find(
                '.unit option:first').val()).change();

            // Find the associated input field and clear its value
            $(this).closest('tr').find('input[name="capacity"]').val('');
        });
    }
    // Common function to handle AJAX errors
    function handleAjaxError(reject) {
        let response = $.parseJSON(reject.responseText);
        $.each(response.errors, function(key, val) {
            errorMsg(val[0]);
        });
    }
    // Common function to error response message
    function handleResponseMessageError(message, title, icon) {
        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            timer: 5000
        });
    }

    // create store
    $(document).on('click', '#save_store', function() {
        let id = $('#store_id');
        let name = $('#store_name');
        let phone = $('#store_phone');
        let address = $('#store_address');
        let branch = 0;
        let methodChecks = $('#storeModal input[name="storage_method"]:checked');
        let storages = [];
        let button = $(this);
        let originalHtml = button.html();


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

        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.stores.store') }}",
            dataType: 'json',
            data: {
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                storages: storages,
            },
            success: function(response) {
                if (response.status == 200) {
                    newStore = `<tr id=sid${response.data.id}>
                            <td>${response.data.id}</td>
                            <td>${response.data.name || '-'}</td>
                            <td>${response.data.phone || '-'}</td>
                            <td>${response.data.address || '-'}</td>
                            <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${response.data.id}" id="edit_store">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${response.data.id}" id="view_store"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_store"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                            </td>
                        </tr>`;
                    $('tbody tr').each(function() {
                        if ($(this).find('td').attr('colspan') == '5') {
                            $(this).remove();
                        }
                    });
                    tbody.append(newStore);
                }
                id.val(response.data.id += 1);
                name.val('');
                phone.val('');
                address.val('');
                $('#storeModal input[type="checkbox"]').each(function() {
                    // Uncheck the checkbox
                    $(this).prop('checked', false);

                    // Find the associated select element and reset it
                    $(this).closest('tr').find('.unit').val($(this).closest('tr').find(
                        '.unit option:first').val()).change();

                    // Find the associated input field and clear its value
                    $(this).closest('tr').find('input[name="capacity"]').val('');
                });
                successMsg(response.message);
                checkForm();
            },
            error: handleAjaxError,
            complete: function(response) {
                button.html(originalHtml).prop('disabled', false);
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
                        success: function(response) {

                            if (response.status == 200) {
                                handleResponseMessageError(response.message,
                                    'تم الحذف', 'success')

                                row.remove();
                                resolve();
                            }
                            if (response.status == 422) {
                                handleResponseMessageError(response.message,
                                    'خطأ', 'error')
                                resolve();
                            }
                        },
                        error: function(error) {
                            handleResponseMessageError(error.responseJSON
                                .message, 'خطأ', 'error')
                            resolve();
                        },
                        complete: function() {
                            if (tbody.find('tr').length === 0) {
                                tbody.append(
                                    '<tr><td colspan="5">لا توجد مخازن</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view store
    $(document).on('click', '#view_store', function() {
        resetModal('#viewModal');
        const id = $(this).data('id');
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'GET',
            url: '{{ url('stock/stores', '') }}' + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    const store = response.data;
                    console.log(response)



                    // Set new values
                    $('#viewModal #id').val(store.id);
                    $('#viewModal #name').val(store.name);
                    $('#viewModal #phone').val(store.phone);
                    $('#viewModal #address').val(store.address);

                    // Show the modal first
                    $('#viewModal').modal('show');
                    store.storage_methods.forEach(method => {
                        console.log(method.type.name_ar)
                        console.log(method.unit.name_en)
                        const methodElement = $(
                            `#viewModal input[name="storage_method"][value="${method.type.name_en}"]`
                        );
                        methodElement.prop('checked', true);

                        const selectElement = methodElement.closest('tr').find(
                            'select[name="storage_unit"]'
                        );
                        selectElement.val(method.unit.name_en)
                            .change();

                        const capacityElement = methodElement.closest('tr')
                            .find(
                                'input[name="capacity"]'
                            );
                        capacityElement.val(method.capacity);
                    });
                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }
        });

    })
    // edit store
    $(document).on('click', '#edit_store', function(params) {
        resetModal('#editModal');
        const id = $(this).data('id');
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'GET',
            url: '{{ url('stock/stores', '') }}' + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    const store = response.data;


                    // Set new values
                    $('#editModal #id').val(store.id);
                    $('#editModal #name').val(store.name);
                    $('#editModal #phone').val(store.phone);
                    $('#editModal #address').val(store.address);

                    // Show the modal first
                    $('#editModal').modal('show');
                    store.storage_methods.forEach(method => {
                        const methodElement = $(
                            `#editModal input[name="storage_method"][value="${method.type.name_en}"]`
                        );
                        methodElement.prop('checked', true);

                        const selectElement = methodElement.closest('tr').find(
                            'select[name="storage_unit"]'
                        );
                        selectElement.val(method.unit.name_en)
                            .change();

                        const capacityElement = methodElement.closest('tr')
                            .find(
                                'input[name="capacity"]'
                            );
                        capacityElement.val(method.capacity);
                    });
                    checkForm();
                    $('#editModal').find('.modal-footer #update_store').removeAttr('data-id').data(
                        'id', store.id)

                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }
        });
    })

    // update store 
    $(document).on('click', '#update_store', function(params) {
        const id = $(this).data('id');
        let name = $('#editModal').find('#name');
        let phone = $('#editModal').find('#phone');
        let address = $('#editModal').find('#address');
        let branch = 0;
        let methodChecks = $('#editModal input[name="storage_method"]:checked');
        let storages = [];
        let button = $(this);
        let originalHtml = button.html();


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

        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'PUT',
            url: '{{ url('stock/stores', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                storages: storages,
            },
            success: function(response) {
                if (response.status == 200) {
                    $('#sid' + response.data.id + ' td:nth-child(1)').text(response.data.id)
                    $('#sid' + response.data.id + ' td:nth-child(2)').text(response.data.name)
                    $('#sid' + response.data.id + ' td:nth-child(3)').text(response.data.phone)
                    $('#sid' + response.data.id + ' td:nth-child(4)').text(response.data.address)
                    successMsg(response.message);
                    $('#editModal').modal('hide');
                }
            },
            error: handleAjaxError,
            complete: function(response) {
                button.html(originalHtml).prop('disabled', false);
            }
        });
    })
</script>

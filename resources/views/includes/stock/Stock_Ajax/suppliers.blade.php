@include('includes.stock.Stock_Ajax.public_function')
<script>
    const tbody = $('.table-data tbody');
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );
    $(document).ready(function() {
        $('input').attr('autocomplete', 'off');
    });
    // Common function to handle AJAX errors
    function handleAjaxError(reject) {
        let response = $.parseJSON(reject.responseText);
        $.each(response.errors, function(key, val) {
            errorMsg(val[0]);
        });
    }

    // Common function to reset modal form
    function resetModalForm(modal) {
        modal.find('input').val('');
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


    // store section
    $(document).on('submit', '#storeSupplier', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');

        const originalHtml = button.html();

        button.html(spinner).prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.suppliers.store') }}",
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                name: $('#supplier_name').val(),
                phone: $('#supplier_phone').val(),
                address: $('#supplier_address').val(),
            },
            success: function(response) {

                if (response.status == 200) {
                    newSection = `<tr id=sid${response.data.id}>
                            <td>${response.data.id}</td>
                            <td>${response.data.name}</td>
                            <td>${response.data.phone || '-'}</td>
                            <td>${response.data.address || '-'}</td>
                                                    <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${response.data.id}" id="edit_supplier">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${response.data.id}" id="view_supplier"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_supplier"
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
                    tbody.append(newSection);
                    $('#supplier_id').val(response.data.id + 1);
                    $('#supplier_name').val('');
                    $('#supplier_phone').val('');
                    $('#supplier_address').val('');
                    successMsg(response.message);
                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // delete section
    $(document).on('click', '#delete_supplier', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        Swal.fire({
            title: 'حذف !',
            text: 'هل انت متأكد من حذف المورد',
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
                        url: '{{ url('stock/suppliers', '') }}' + '/' +
                            id,
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: id,
                        },
                        success: function(response) {
                            if (response.status == 200) {
                                handleResponseMessageError(response.message,
                                    'تم الحذف', 'success')

                                row.remove();
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
                                    '<tr><td colspan="5">لا يوجد موردين</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view section
    $(document).on('click', '#view_supplier', function() {
        const id = $(this).data('id');
        const viewModal = $('#viewModal');
        resetModalForm(viewModal);
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        // call Api

        $.ajax({
            type: 'GET',
            url: '{{ url('stock/suppliers', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    const supplier = response.data;
                    $('#viewModal').modal('show');
                    // Set new values
                    $('#viewModal #id').val(supplier.id);
                    $('#viewModal #name').val(supplier.name);
                    $('#viewModal #phone').val(supplier.phone);
                    $('#viewModal #address').val(supplier.address);

                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }

        });

    })
    // edit section
    $(document).on('click', '#edit_supplier', function(params) {
        const id = $(this).data('id');
        const editModal = $('#editModal');
        resetModalForm(editModal);
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        // Call API
        $.ajax({
            type: 'GET',
            url: '{{ url('stock/suppliers', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    const supplier = response.data;
                    $('#editModal').modal('show');
                    // Set new values
                    $('#editModal #id').val(supplier.id);
                    $('#editModal #name').val(supplier.name);
                    $('#editModal #phone').val(supplier.phone);
                    $('#editModal #address').val(supplier.address);

                    checkForm();
                    $('#editModal').find('.modal-footer #update_supplier').removeAttr('data-id')
                        .data(
                            'id', supplier.id)
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }

        });
    });


    // update section 
    $(document).on('click', '#update_supplier', function(params) {
        const id = $(this).data('id');
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'PUT',
            url: '{{ url('stock/suppliers', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                name: $('#editModal #name').val(),
                phone: $('#editModal #phone').val(),
                address: $('#editModal #address').val(),
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
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }
        });
    })

    // $('#supplier_name').on('keyup', function() {
    //     let query = $(this).val()
    //     searchDb('search_suppliers', query, $(this));
    // });

    // $(document).on('click', '.search-result li', function(e) {
    //     e.stopPropagation();
    //     getData('get_suppliers', $(this).attr('data-id'), function(data) {
    //         id.val(data.id);
    //         name.val(data.name);
    //         phone.val(data.phone);
    //         address.val(data.address);
    //         $('#save_supplier').addClass('d-none');
    //         $('#update_supplier').removeClass('d-none');
    //         $('.search-result').html('');
    //     });
    // });
</script>

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

    // store mainGroups
    $(document).on('submit', '#storeMainGroup', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        const originalHtml = button.html();

        button.html(spinner).prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.main.groups.store') }}",
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                name: $('#group_name').val(),
            },
            success: function(response) {

                if (response.status == 200) {
                    newMainGroup = `<tr id=sid${response.data.id}>
                            <td>${response.data.name}</td>
                            <td>${response.data.serial_Nr}</td>
                                                    <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${response.data.id}" id="edit_main_group">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${response.data.id}" id="view_main_group"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_main_group"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                            </td>
                            </tr>`;
                    $('tbody tr').each(function() {
                        if ($(this).find('td').attr('colspan') == '3') {
                            $(this).remove();
                        }
                    });
                    tbody.append(newMainGroup);
                    $('#group_id').val(response.data.id + 1);
                    $('#group_name').val('');
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

    // delete mainGroups
    $(document).on('click', '#delete_main_group', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        Swal.fire({
            title: 'حذف !',
            text: 'هل انت متأكد من حذف المجوعة الرئيسية',
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
                        url: '{{ url('stock/main/groups', '') }}' + '/' +
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
                            if (response.status == 422) {
                                handleResponseMessageError(response.message,
                                    'خطأ', 'error')
                                resolve();
                            }
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'Error!',
                                text: error.responseJSON
                                    .message,
                                icon: 'error',
                                timer: 5000
                            });
                            resolve();
                        },
                        complete: function() {
                            if (tbody.find('tr').length === 0) {
                                tbody.append(
                                    '<tr><td colspan="3">لا توجد مجموعات رئيسية</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view mainGroups
    $(document).on('click', '#view_main_group', function() {
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
            url: '{{ url('stock/main/groups', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    const mainGroup = response.data;
                    $('#viewModal').modal('show');
                    // Set new values
                    $('#viewModal #id').val(mainGroup.id);
                    $('#viewModal #name').val(mainGroup.name);
                    $('#viewModal #serial_nr').val(mainGroup.serial_Nr);

                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }

        });

    })
    // edit mainGroups
    $(document).on('click', '#edit_main_group', function(params) {
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
            url: '{{ url('stock/main/groups', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    const mainGroup = response.data;
                    $('#editModal').modal('show');
                    // Set new values
                    $('#editModal #id').val(mainGroup.id);
                    $('#editModal #name').val(mainGroup.name);
                    $('#editModal #serial_nr').val(mainGroup.serial_Nr);

                    checkForm();
                    $('#editModal').find('.modal-footer #update_main_group').removeAttr('data-id')
                        .data(
                            'id', mainGroup.id)
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }

        });
    });


    // update mainGroups 
    $(document).on('click', '#update_main_group', function(params) {
        const id = $(this).data('id');
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'PUT',
            url: '{{ url('stock/main/groups', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                name: $('#editModal #name').val(),
            },
            success: function(response) {
                if (response.status == 200) {
                    $('#sid' + response.data.id + ' td:nth-child(1)').text(response.data.name)
                    $('#sid' + response.data.id + ' td:nth-child(2)').text(response.data.serial_Nr)
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

    // $('#group_name').on('keyup', function () {
    //     let query = $(this).val()
    //     searchDb('search_main_groups' , query, $(this));
    // });

    // $(document).on('click', '.search-result li', function(e) {
    //     e.stopPropagation();
    //     getData('get_main_groups', $(this).attr('data-id'), function(data) {
    //         id.val(data.id);
    //         name.val(data.name);
    //         $('#save_group').addClass('d-none');
    //         $('#update_group').removeClass('d-none');
    //         $('.search-result').html('');
    //     });
    // });
</script>

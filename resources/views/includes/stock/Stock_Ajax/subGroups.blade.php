@include('includes.stock.Stock_Ajax.public_function')
<script>
    const tbody = $('table.table-data tbody');
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );
    $(document).ready(function() {
        $('input').attr('autocomplete', 'off');
    });
    // handle csrf request header

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    })
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

    // Common function to reset modal form
    function resetModalForm(modal) {
        modal.find('input, select').val('');
    }

    // store section

    $(document).on('submit', '#storeSubGroup', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        const originalHtml = button.html();

        button.html(spinner).prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.sub.groups.store') }}",
            dataType: 'json',
            data: {
                name: $('#storeSubGroup #name').val(),
                parent_id: $('#storeSubGroup #parent_group_id').val(),
            },
            success: function(response) {

                if (response.status == 200) {
                    newSubGroup = `<tr id=sid${response.data.id}>
                            <td>${response.data.parent_name}</td>
                            <td>${response.data.name}</td>
                            <td>${response.data.serial_Nr}</td>
                                                    <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${response.data.id}" id="edit_sub_group">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${response.data.id}" id="view_sub_group"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_sub_group"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                            </td>
                            </tr>`;
                    $('tbody tr').each(function() {
                        if ($(this).find('td').attr('colspan') == '4') {
                            $(this).remove();
                        }
                    });
                    tbody.append(newSubGroup);
                    $('#storeSubGroup #id').val(response.data.id + 1);
                    $('#storeSubGroup #name').val('');
                    // Reset select elements to their first option
                    const defaultOptionValue = $('#storeSubGroup select[name="parent_group_id"]')
                        .find(
                            'option:first').val();
                    $('#storeSubGroup select[name="parent_group_id"]').val(defaultOptionValue)
                        .change();
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
    $(document).on('click', '#delete_sub_group', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        Swal.fire({
            title: 'حذف !',
            text: 'هل انت متأكد من حذف المجموعة الفرعية',
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
                        url: '{{ url('stock/sub/groups', '') }}' + '/' + id,
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
                                    '<tr><td colspan="4">لا توجد مجموعات فرعية</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view section
    $(document).on('click', '#view_sub_group', function() {
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
            url: '{{ url('stock/sub/groups', '') }}' + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    const subGroup = response.data;
                    $('#viewModal').modal('show');
                    // Set new values
                    $('#viewModal #id').val(subGroup.id);
                    $('#viewModal #name').val(subGroup.name);
                    $('#viewModal #serialNr').val(subGroup.serial_Nr);
                    $('#viewModal select[name="parent_group_id"]').append(
                        `<option class="form-select" value="${subGroup.parent_id}" selected>${subGroup.parent_name}</option>`
                    );
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
    $(document).on('click', '#edit_sub_group', function() {
        const id = $(this).data('id');
        const editModal = $('#editModal');
        resetModalForm(editModal);
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);

        // Reset select elements to their first option
        const firstMainGroupOptionValue = $('#editModal select[name="parent_group_id"]').find('option:first')
            .val();
        $('#editModal select[name="parent_group_id"]').val(firstMainGroupOptionValue).change();

        // Call API
        $.ajax({
            type: 'GET',
            url: '{{ url('stock/sub/groups', '') }}' + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    const subGroup = response.data;

                    $('#editModal').modal('show');

                    // Set new values
                    $('#editModal #id').val(subGroup.id);
                    $('#editModal #name').val(subGroup.name);
                    $('#editModal #serialNr').val(subGroup.serial_Nr);

                    // Set the store value
                    let mainGroupOption = $('#editModal select[name="parent_group_id"]').find(
                        `option:contains(${subGroup.parent_name})`).val();
                    if (mainGroupOption) {
                        $('#editModal select[name="parent_group_id"]').val(mainGroupOption)
                            .change();
                    }

                    $('#editModal').find('.modal-footer #update_sub_group').removeAttr('data-id')
                        .data(
                            'id', subGroup.id)
                    // Perform any additional form checks
                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }

        });
    });


    // update section 
    $(document).on('click', '#update_sub_group', function() {
        const id = $(this).data('id');
        let button = $(this);
        let originalHtml = button.html();

        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'PUT',
            url: '{{ url('stock/sub/groups', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                name: $('#editModal #name').val(),
                parent_id: $('#editModal #parent_group_id').val(),
            },
            success: function(response) {
                if (response.status == 200) {
                    console.log(response)
                    $('#sid' + response.data.id + ' td:nth-child(1)').text(response.data
                        .parent_name)
                    $('#sid' + response.data.id + ' td:nth-child(2)').text(response.data.name)
                    $('#sid' + response.data.id + ' td:nth-child(3)').text(response.data.serial_Nr)
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
    // ## TODO
    // handle filter subgroups
    // $(document).on('change', '#storeSubGroup select[name="parent_group_id"]', function() {
    //     let button = $(this);
    //     let selectedValue = button.val()
    //     const url = '{{ url('stock/sub/groups') }}/' + selectedValue + '/filter';
    //     const initialParams = {
    //         "_token": "{{ csrf_token() }}",
    //         "parent_id": selectedValue,
    //         "page": 1 // Start with page 1
    //     };
    //     const queryString = $.param(initialParams);


    //     let selectedText = button.find('option:selected').text()
    //     if (!selectedValue) return;
    //     $.ajax({
    //         type: 'GET',
    //         url: `${url}?${queryString}`,
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.status == 200) {
    //                 const groups = response.data.data; // Access the paginated data
    //                 const pagination = response.data; // Contains pagination info
    //                 const $pagination = $('.pagination');

    //                 // Clear existing table rows
    //                 tbody.empty();

    //                 if (!groups.length) {
    //                     tbody.append(
    //                         '<tr><td colspan="4">لا توجد مجموعات فرعية</td></tr>'
    //                     );
    //                 }
    //                 // Update table rows
    //                 groups.forEach(group => {
    //                     const newRowContent = `
    //                 <tr id="sid${group.id}">
    //                     <td>${selectedText}</td>
    //                     <td>${group.name}</td>
    //                     <td>${group.serial_nr}</td>
    //                     <td>
    //                         <button title="تعديل" class="btn btn-success" data-id="${group.id}" id="edit_sub_group">
    //                             <i class="far fa-edit"></i>
    //                         </button>
    //                         <button title="عرض" data-id="${group.id}" id="view_sub_group" class="btn btn-primary">
    //                             <i class="fa fa-eye" aria-hidden="true"></i>
    //                         </button>
    //                         <button class="btn btn-danger" id="delete_sub_group" data-id="${group.id}">
    //                             <i class="fa fa-trash"></i>
    //                         </button>
    //                     </td>
    //                 </tr>`;

    //                     tbody.append(newRowContent);
    //                 });

    //                 // Clear existing pagination links
    //                 $pagination.empty();

    //                 // Update pagination links

    //                 updatePaginationLinks(pagination.links);




    //             }
    //             if (response.status == 422) {
    //                 // Handle validation errors
    //                 return false;
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('AJAX Error: ' + status + error);
    //         },
    //     });

    // })
    // Handle pagination click event
    // $(document).on('click', '.pagination a', function(e) {
    //     e.preventDefault();
    //     let selectedOption = $('#storeSubGroup select[name="parent_group_id"]')
    //     let selectedValue = selectedOption.val()
    //     let selectedText = selectedOption.find('option:selected').text()
    //     const pageUrl = $(this).attr('href');
    //     $.ajax({
    //         type: 'GET',
    //         url: pageUrl,
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.status == 200) {
    //                 const groups = response.data.data; // Access the paginated data
    //                 const pagination = response.data; // Contains pagination info
    //                 const tbody = $('table.table-data tbody');
    //                 const $pagination = $('.pagination');

    //                 // Clear existing table rows
    //                 tbody.empty();
    //                 if (!groups.length) {
    //                     tbody.append(
    //                         '<tr><td colspan="4">لا توجد مجموعات فرعية</td></tr>'
    //                     );
    //                 }
    //                 // Update table rows
    //                 groups.forEach(group => {
    //                     const newRowContent = `
    //                 <tr id="sid${group.id}">
    //                     <td>${selectedText}</td>
    //                     <td>${group.name}</td>
    //                     <td>${group.serial_nr}</td>
    //                     <td>
    //                         <button title="تعديل" class="btn btn-success" data-id="${group.id}" id="edit_sub_group">
    //                             <i class="far fa-edit"></i>
    //                         </button>
    //                         <button title="عرض" data-id="${group.id}" id="view_sub_group" class="btn btn-primary">
    //                             <i class="fa fa-eye" aria-hidden="true"></i>
    //                         </button>
    //                         <button class="btn btn-danger" id="delete_sub_group" data-id="${group.id}">
    //                             <i class="fa fa-trash"></i>
    //                         </button>
    //                     </td>
    //                 </tr>`
    //                     tbody.append(newRowContent);
    //                 });




    //                 updatePaginationLinks(pagination.links);

    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             console.error('AJAX Error: ' + status + error);
    //         }
    //     });
    // });

    // function updatePaginationLinks(links) {
    //     const $pagination = $('.pagination');
    //     $pagination.empty();

    //     const parent_id = $('#storeSubGroup select[name="parent_group_id"]').val();
    //     const _token = "{{ csrf_token() }}";
    //     const currentPage = getCurrentPageNumber();

    //     links.forEach(link => {


    //         console.log(link.label)
    //         if (link.label === '&laquo; Previous' || link.label === 'Next &raquo;') {
    //             return; // Skip this iteration
    //         }

    //         const params = {
    //             "parent_id": parent_id,
    //             "_token": _token,
    //         };

    //         // Only include "page" parameter if it's not the current page
    //         if (link.page && link.page !== currentPage) {
    //             params["page"] = link.page;
    //         }

    //         const queryString = $.param(params);

    //         // Ensure link.url is defined and not null
    //         let pageUrl = link.url || '';

    //         // Construct pageUrl without adding ? if link.url already contains query parameters
    //         if (pageUrl.includes('?')) {
    //             pageUrl += `&${queryString}`;
    //         } else {
    //             pageUrl += `?${queryString}`;
    //         }

    //         $pagination.append(
    //             `<li class="page-item ${link.active ? 'active' : ''}" aria-current="page"><a class="page-link" href="${pageUrl}">${link.label}</a></li>`
    //         );
    //     });
    // }

    // function getCurrentPageNumber() {
    //     // Extract current page number from URL or default to 1
    //     const urlParams = new URLSearchParams(window.location.search);
    //     return parseInt(urlParams.get('page')) || 1;
    // }
</script>

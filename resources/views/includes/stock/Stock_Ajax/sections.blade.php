@include('includes.stock.Stock_Ajax.public_function')
<script>
    $(document).ready(function() {
        bindBranchChangeEvent(); // Bind change event when the document is ready
    });

    let preventChangeEvent = false; // Flag to control change event execution
    const tbody = $('.table-data tbody');
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );

    // Common function to handle AJAX errors
    function handleAjaxError(reject) {
        let response = $.parseJSON(reject.responseText);
        $.each(response.errors, function(key, val) {
            errorMsg(val[0]);
        });
    }

    // Common function to reset modal form
    function resetModalForm(modal) {
        modal.find('input, select').val('');
        modal.find('.groups').html('');
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

    // get sections groups 

    $(document).on('change', '#branch', function() {
        if (preventChangeEvent) return;
        let id = $('#branch').val()
        let groupsDiv = $('.groups');
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.sections.groups') }}",
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                branch_id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    let groupsContent = '';
                    groupsDiv.html('');
                    response.data.forEach(group => {
                        groupsContent += `
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="${group.name}" id="group-${group.id}" name="groups">
                            <label class="form-check-label" for="group-${group.id}">
                                ${group.name}
                            </label>
                        </div>`;
                    })
                    groupsDiv.html(groupsContent)
                }
            },
            error: handleAjaxError
        });
    })

    // store section

    $(document).on('submit', '#storeSection', function(e) {
        e.preventDefault();
        const form = $(this);
        const groupsChecked = form.find('input[name="groups"]:checked');
        const groups = groupsChecked.map((_, el) => ({
            id: $(el).attr('id').replace('group-', '')
        })).get();
        const button = form.find('button[type="submit"]');
        const originalHtml = button.html();

        button.html(spinner).prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: "{{ route('stock.sections.store') }}",
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                name: $('#section_name').val(),
                store_id: $('#store').val(),
                branch_id: $('#branch').val(),
                groupIds: groups
            },
            success: function(response) {

                if (response.status == 200) {
                    // Set the flag to prevent the change event
                    preventChangeEvent = true;
                    // Reset select elements to their first option
                    const firstStoreOptionValue = $('#storeSection select[name="store_id"]').find(
                        'option:first').val();
                    $('#storeSection select[name="store_id"]').val(firstStoreOptionValue).change();

                    const firstBranchOptionValue = $('#storeSection select[name="branch_id"]').find(
                        'option:first').val();
                    $('#storeSection select[name="branch_id"]').val(firstBranchOptionValue)
                        .change();

                    newSection = `<tr id=sid${response.data.id}>
                            <td>${response.data.id}</td>
                            <td>${response.data.name}</td>
                            <td>${response.data.branch.name}</td>
                            <td>${response.data.store.name}</td>
                                                    <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${response.data.id}" id="edit_section">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${response.data.id}" id="view_section"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_section"
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
                    let storeModel = $('#storeSection');
                    resetModalForm(storeModel)
                    $('#section_id').val(response.data.id + 1);
                    $('.groups input[type="checkbox"]').prop('checked', false);
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
    $(document).on('click', '#delete_section', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        Swal.fire({
            title: 'حذف !',
            text: 'هل انت متأكد من حذف القسم',
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
                        url: '{{ url('stock/sections', '') }}' + '/' +
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
                                    '<tr><td colspan="5">لا توجد أقسام</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view section
    $(document).on('click', '#view_section', function() {
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
            url: '{{ url('stock/sections', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    const section = response.data;
                    $('#viewModal').modal('show');
                    // Set new values
                    $('#viewModal #id').val(section.id);
                    $('#viewModal #name').val(section.name);
                    $('#viewModal select[name="store_id"]').append(
                        `<option class="form-select" value="${section.store.id}" selected>${section.store.name}</option>`
                    );
                    $('#viewModal select[name="branch_id"]').append(
                        `<option class="form-select" value="${section.branch.id}" selected>${section.branch.name}</option>`
                    );
                    let groupsContent = '';

                    // Append groups
                    section.groups.forEach(group => {
                        groupsContent += `
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="${group.name}" id="edit-model-group-${group.id}" name="groups" checked>
                                    <label class="form-check-label" for="edit-model-group-${group.id}">
                                        ${group.name}
                                    </label>
                                </div>`;
                    });

                    $('#viewModal .groups').html(groupsContent);

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
    $(document).on('click', '#edit_section', function(params) {
        const id = $(this).data('id');
        const editModal = $('#editModal');
        resetModalForm(editModal);
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);
        // Set the flag to prevent the change event
        preventChangeEvent = true;
        // Reset select elements to their first option
        const firstStoreOptionValue = $('#editModal select[name="store_id"]').find('option:first').val();
        $('#editModal select[name="store_id"]').val(firstStoreOptionValue).change();

        const firstBranchOptionValue = $('#editModal select[name="branch_id"]').find('option:first').val();
        $('#editModal select[name="branch_id"]').val(firstBranchOptionValue).change();

        // Call API
        $.ajax({
            type: 'GET',
            url: '{{ url('stock/sections', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.status == 200) {
                    const section = response.data;
                    $('#editModal').modal('show');

                    // Set new values
                    $('#editModal #id').val(section.id);
                    $('#editModal #name').val(section.name);

                    // Set the store value
                    let storeOption = $('#editModal select[name="store_id"]').find(
                        `option:contains(${section.store.name})`).val();
                    if (storeOption) {
                        $('#editModal select[name="store_id"]').val(storeOption).change();
                    }

                    let branchOption = $('#editModal select[name="branch_id"]').find(
                        `option:contains(${section.branch.name})`).val();
                    if (branchOption) {
                        $('#editModal select[name="branch_id"]').val(branchOption).change()
                    }
                    let groupsContent = '';
                    let arr = []
                    section.groups.forEach(function(group) {
                        arr.push(group.name)
                    });
                    response.groups.forEach(group => {
                        const isChecked = arr.includes(group.name) ? 'checked' : '';
                        groupsContent += `
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="${group.name}" id="edit-model-group-${group.id}" name="groups" ${isChecked}>
                            <label class="form-check-label" for="edit-model-group-${group.id}">
                                ${group.name}
                            </label>
                        </div>`;
                    });
                    $('#editModal .groups').html(groupsContent);


                    $('#editModal').find('.modal-footer #update_section').removeAttr('data-id')
                        .data(
                            'id', section.id)
                    // Perform any additional form checks
                    checkForm();
                }
                // Unset the flag after setting new values
                preventChangeEvent = false;
            },
            error: handleAjaxError,
            complete: function() {
                // Unset the flag after setting new values
                preventChangeEvent = false;
                button.html(originalHtml).prop('disabled', false);
            }

        });
    });


    // update section 
    $(document).on('click', '#update_section', function(params) {
        const id = $(this).data('id');
        let name = $('#editModal #name');
        let store = $('#editModal select[name="store_id"]');
        let branch = $('#editModal select[name="branch_id"]');
        let groupsDiv = $('#editModal .groups');
        let groupsChecked = $('#editModal input[name="groups"]:checked');
        let groups = [];
        let button = $(this);
        let originalHtml = button.html();

        groupsChecked.each(function() {
            let groupName = $(this).val()
            let groupId = $(this).attr('id').replace('edit-model-group-', '')
            groups.push({
                id: groupId
            });
        });
        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'PUT',
            url: '{{ url('stock/sections', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                "_token": "{{ csrf_token() }}",
                name: name.val(),
                store_id: store.val(),
                branch_id: branch.val(),
                groupIds: groups
            },
            success: function(response) {
                if (response.status == 200) {
                    $('#sid' + response.data.id + ' td:nth-child(1)').text(response.data.id)
                    $('#sid' + response.data.id + ' td:nth-child(2)').text(response.data.name)
                    $('#sid' + response.data.id + ' td:nth-child(3)').text(response.data.branch
                        .name)
                    $('#sid' + response.data.id + ' td:nth-child(4)').text(response.data.store
                        .name)
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


    function bindBranchChangeEvent() {
        $(document).on('change', '#branch_id', function() {
            if (preventChangeEvent) return;
            let branch = $('#editModal select[name="branch_id"]');
            let id = branch.val();
            let groupsDiv = $('#editModal .groups');
            $.ajax({
                type: 'POST',
                url: "{{ route('stock.sections.groups') }}",
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                    branch_id: id
                },
                success: function(response) {
                    if (response.status == 200) {
                        let groupsContent = '';
                        groupsDiv.html('');
                        for (const group in response.data) {
                            groupsContent += `
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="${response.data[group].name}" id="edit-model-group-${response.data[group].id}" name="groups">
                                <label class="form-check-label" for="edit-model-group-${response.data[group].id}">
                                    ${response.data[group].name}
                                </label>
                            </div>`;
                        }
                        groupsDiv.html(groupsContent);
                    }
                },
                error: function(reject) {
                    let response = $.parseJSON(reject.responseText);
                    $.each(response.errors, function(key, val) {
                        errorMsg(val[0]);
                    });
                },
            });
        });
    }

    // $('#section_name').on('keyup', function() {
    //     let query = $(this).val()
    //     searchDb('search_section', query, $(this), branch.val());
    // });

    // $(document).on('click', '.search-result li', function(e) {
    //     e.stopPropagation();
    //     getData('get_section', $(this).attr('data-id'), function(data) {
    //         id.val(data.id);
    //         name.val(data.name);
    //         $('input[type="checkbox"]').each(function() {
    //             $(this).prop('checked', false)
    //         })
    //         $('#store').find(`option[value='${data.sectionstore.store_id}']`).prop('selected', true)
    //         data.sectiongroup.forEach(group => {
    //             $(`input#group-${group.group_id}`).prop('checked', true);
    //         });
    //         $('#save_section').addClass('d-none');
    //         $('#update_section').removeClass('d-none');
    //         $('.search-result').html('');
    //     });

    // });
</script>

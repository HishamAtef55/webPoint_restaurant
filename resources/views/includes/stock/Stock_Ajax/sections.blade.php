@include('includes.stock.Stock_Ajax.public_function')
<script>
    let id = $('#section_id');
    let store = $('#store');
    let branch = $('#branch');
    let name = $('#section_name');
    let groupsDiv = $('.groups');
    let tbody = $('.table-data tbody');
    // get sections groups 

    $(document).on('change', '#branch', function() {
        let id = $('#branch').val()
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
                        groupsContent += ` <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="${response.data[group].name}" id="group-${response.data[group].id}" name="groups">
                        <label class="form-check-label" for="group-${response.data[group].id}">
                            ${response.data[group].name}
                        </label>
                    </div>`
                    }
                    groupsDiv.html(groupsContent)
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0])
                });
            },
        });
    })

    // store section

    $(document).on('submit', '#storeSection', function(e) {
        e.preventDefault();
        let groupsChecked = $('input[name="groups"]:checked');
        let groups = [];
        let button = $(this).find('button[type="submit"]');
        let spinner = $(
            '<div class="spinner-border text-light" role="status"><span class="sr-only">Loading...</span></div>'
        );
        let originalHtml = button.html();

        groupsChecked.each(function() {
            let groupName = $(this).val()
            let groupId = $(this).attr('id').replace('group-', '')
            groups.push({
                id: groupId
            });
        });
        button.html(spinner);
        button.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.sections.store') }}",
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
                    newSection = `<tr>
                            <th>${response.data.id}</th>
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
                    id.val(response.data.id += 1);
                    name.val('');
                    groupsDiv.find('input[type="checkbox"]').each(function() {
                        $(this).prop('checked', false)
                    });
                    successMsg(response.message);
                    checkForm();
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0]);
                });
                button.html(originalHtml);
                button.prop('disabled', false);
            },
            complete: function(response) {
                button.html(originalHtml);
                button.prop('disabled', false);
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
                                    '<tr><td colspan="5">لا توجد أقسام</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view store
    $(document).on('click', '#view_section', function() {
        resetSectionsModel('#viewModal');
        const id = $(this).data('id');

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
                    // Set new values
                    $('#viewModal #id').val(section.id);
                    $('#viewModal #name').val(section.name);
                    // Append the new option for store
                    $('#viewModal select[name="store_id"]').append(
                        `<option class="form-select" value="${section.store.id}" selected>${section.store.name}</option>`
                    );
                    // Append the new option for branch
                    $('#viewModal select[name="branch_id"]').append(
                        `<option class="form-select" value="${section.branch.id}" selected>${section.branch.name}</option>`
                    );

                    // Append groups
                    let groupsContent = '';
                    section.groups.forEach(group => {
                        groupsContent += `
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="${group.name}" id="group-${group.id}" name="groups" checked>
                                    <label class="form-check-label" for="group-${group.id}">
                                        ${group.name}
                                    </label>
                                </div>`;
                    });
                    $('#viewModal .groups').html(groupsContent);

                    // Show the modal after setting the values
                    $('#viewModal').modal('show');

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
    // edit store
    $(document).on('click', '#edit_section', function(params) {
        resetSectionsModel('#editModal');
        const id = $(this).data('id');

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
                    // Set new values
                    $('#editModal #id').val(section.id);
                    $('#editModal #name').val(section.name);
                    // Append the new option for store
                    $('#editModal select[name="store_id"]').append(
                        `<option class="form-select" value="${section.store.id}" selected>${section.store.name}</option>`
                    );
                    // Append the new option for branch
                    $('#editModal select[name="branch_id"]').append(
                        `<option class="form-select" value="${section.branch.id}" selected>${section.branch.name}</option>`
                    );

                    // Append groups
                    let groupsContent = '';
                    section.groups.forEach(group => {
                        groupsContent += `
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="${group.name}" id="group-${group.id}" name="groups" checked>
                                    <label class="form-check-label" for="group-${group.id}">
                                        ${group.name}
                                    </label>
                                </div>`;
                    });
                    $('#editModal .groups').html(groupsContent);

                    // Show the modal after setting the values
                    $('#editModal').modal('show');

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
        let spinner = $(
            '<div class="spinner-border text-light" role="status"><span class="sr-only">Loading...</span></div>'
        );
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
                "_token": "{{ csrf_token() }}",
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
                storages: storages,
            },
            success: function(response) {
                if (response.status == 200) {
                    $('#sid' + response.data.id + ' td:nth-child(1)').text(response.data
                        .id)
                    $('#sid' + response.data.id + ' td:nth-child(2)').text(response.data
                        .name)
                    $('#sid' + response.data.id + ' td:nth-child(3)').text(response.data
                        .phone)
                    $('#sid' + response.data.id + ' td:nth-child(4)').text(response.data
                        .address)
                    button.html(originalHtml);
                    button.prop('disabled', false);
                    successMsg(response.message);
                    $('#editModal').modal('hide');
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0])
                });
                button.html(originalHtml);
                button.prop('disabled', false);
            }
        });
    })





    // $('#section_name').on('keyup', function() {
    //     let query = $(this).val()
    //     searchDb('search_section', query, $(this), branch.val());
    // });

    $(document).on('click', '.search-result li', function(e) {
        e.stopPropagation();
        getData('get_section', $(this).attr('data-id'), function(data) {
            id.val(data.id);
            name.val(data.name);
            $('input[type="checkbox"]').each(function() {
                $(this).prop('checked', false)
            })
            $('#store').find(`option[value='${data.sectionstore.store_id}']`).prop('selected', true)
            data.sectiongroup.forEach(group => {
                $(`input#group-${group.group_id}`).prop('checked', true);
            });
            $('#save_section').addClass('d-none');
            $('#update_section').removeClass('d-none');
            $('.search-result').html('');
        });

    });

    $('#update_section').on('click', function() {
        let groupsChecked = $('input[name="groups"]:checked');
        let groups = [];
        groupsChecked.each(function() {
            let groupName = $(this).val()
            let groupId = $(this).attr('id').replace('group-', '')
            groups.push({
                name: groupName,
                id: groupId
            });
        });

        $.ajax({
            url: "{{ route('stock.sections.store') }}",
            method: 'post',
            data: {
                _token,
                id: id.val(),
                store: store.val(),
                branch: branch.val(),
                name: name.val(),
                groups
            },
            success: function(data) {
                if (data.status == 'true') {
                    tbody.empty();
                    let html = '';
                    data.sections.forEach(section => {
                        html += `<tr>
                            <th>${section.id}</th>
                            <td>${section.sections_branch.name}</td>
                            <td>${section.name}</td>
                        </tr>`;
                    });
                    tbody.html(html);

                    id.val(data.new_section)
                    name.val('');
                    groupsDiv.find('input[type="checkbox"]').each(function() {
                        $(this).prop('checked', false)
                    });
                    successMsg(data.msg);
                    checkForm();
                    $('#update_section').addClass('d-none')
                    $('#save_section').removeClass('d-none')
                }
            }
        });
    });
</script>

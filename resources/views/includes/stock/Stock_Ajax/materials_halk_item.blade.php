@include('includes.stock.Stock_Ajax.public_function')
<script>
    let serial_nr = $('#serial_nr');
    let date = $('#halk_date');
    let branchs = $('#branch_id');
    let sections = $('#section_id')
    let items = $('#item_id');
    let quantity = $('#quantity')
    let notes = $('#notes');
    let tableBody = $('.table-purchases tbody');
    let updateBtn = $('#update_material_halk');
    let saveBtn = $('#save_material_halk');
    let deleteBtn = $('#delete_material_halk');
    let now = new Date();
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );


    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);

    let today = now.getFullYear() + "-" + (month) + "-" + (day);

    let preventChangeEvent = false; // Flag to control change event execution

    $(document).ready(function() {

        getItemsSections();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        })

        // Common function to handle AJAX errors
        function handleAjaxError(reject) {
            let response = $.parseJSON(reject.responseText);
            Toast.fire({
                icon: 'error',
                title: response.message
            });
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

        branchs.on("change", getItemsSections);

        sections.on("change", removeTableData)


        /*
         * getSections
         */
        function getItemsSections() {
            const branchSelectId = branchs.val();
            if (!branchSelectId) {
                return;
            }

            resetPage();
            fetch(`/stock/material/halks/item/${branchSelectId}/filter`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.status == 200) {
                        displayItems(data.items)
                        if (preventChangeEvent) return;
                        displaySections(data.sections);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message
                        });
                        return false
                    }
                })
                .catch((error) => errorMsg(error));
        }

        /*
         * displaySections
         */
        function displaySections(sections) {
            let section_container = $("#section_id");
            let section_html = '';
            if (!sections.length) {
                section_html += `<option value="">لاتوجد اقسام</option>`;
            } else {
                section_html = '<option selected disabled>اختر القسم</option>';
                sections.forEach((section) => {
                    section_html += `<option value="${section.id}">${section.name}</option>`;
                });
            }

            section_container.html(section_html);
        }

        /*
         * displayItems
         */
        function displayItems(items) {
            let container = $("#item_id");
            let html = '';
            if (!items.length) {
                html += `<option value="">لاتوجد اصناف</option>`;
            } else {
                html = '<option selected disabled>اختر الصنف</option>';
                items.forEach((item) => {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });
            }

            container.html(html);
        }


        function resetPage() {
            sections.html(`
                         <select class="form-select" name="section_id" id="section_id">
                            <option value="" selected disabled>اختر القسم</option>
                        </select>`)
            items.html(`
                         <select class="form-select" name="item_id" id="item_id">
                            <option value="" selected disabled>اختر الصنف</option>
                        </select>`)
            quantity.val('')
            tableBody.html('<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>');
            checkForm();
        }

        function removeTableData() {
            tableBody.html('<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>');

        }
        items.on('change', function(e) {
            setTimeout(() => {
                quantity.focus();
            }, 100);
        });

        quantity.on('keyup', function(e) {
            if (e.keyCode === 13) {
                addDataToTable()
            }
        });


        function addDataToTable() {
            let selectedBranch = branchs.find("option:selected");
            let selectedSection = sections.find("option:selected");
            let selectedItem = items.find("option:selected");
            let code = items.val();
            let selectedBranchName = selectedBranch.html();
            let selectedSectionName = selectedSection.html();
            let selectedItemName = selectedItem.html();


            if (!code) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب اختبار فرع'
                });
                return false
            }
            if (!sections.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب اختبار قسم'
                });
                return false
            }
            if (!items.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب اختبار صنف'
                });
                return false
            }
            if (!quantity.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال كمية'
                });
                return false
            }

            if (+quantity.val() < 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'لايمكن إضافة كمية'
                });
                return false
            }


            let html = `<tr rowId="${code}" class="new">

                <td>${code}</td>
                <td>${selectedBranchName}</td>
                <td>${selectedSectionName}</td>
                <td>${selectedItemName}</td>
                <td>
                    <input type="number" class="material_quantity" value="${quantity.val()}"/>
                    <span>${quantity.val()}</span>
                </td>
                <td>
                    <div class="del-edit">
                        <button class="btn btn-danger delete_material"><i class="fa-regular fa-trash-can"></i></button>
                        <button class="btn btn-warning edit_material"><i class="fa-regular fa-pen-to-square"></i></button>
                    </div>
                    <button class="btn btn-primary update_material update">تعديل</button>
                </td>
                </tr>`

            tableBody.find('tr.not-found').length ? $('tr.not-found').remove() : '';
            let existingRow = tableBody.find(`tr[rowId="${code}"]`);
            if (existingRow.length > 0) {
                existingRow.replaceWith(html);
            } else {
                tableBody.append($(html));
            }

            quantity.val('');

            items.select2('open');

            checkForm();
        }

        $(document).on('click', '.delete_material', function() {
            let rowParent = $(this).closest('tr');
            let rowId = rowParent.attr('data-id');

            Swal.fire({
                title: 'حذف !',
                text: 'هل انت متأكد من حذف الهالك',
                icon: 'warning',
                showCancelButton: true,
                showLoaderOnConfirm: true,
                confirmButtonColor: '#5cb85c',
                cancelButtonColor: '#d33',
                cancelButtonText: 'لا',
                confirmButtonText: 'نعم',
                preConfirm: () => {
                    return new Promise((resolve) => {
                        if (rowParent.hasClass('new')) {
                            rowParent.remove();
                            if (tableBody.find('tr').length === 0) {
                                tableBody.append(
                                    `<tr class="not-found">
                                        <td colspan="7">لا يوجد بيانات</td>
                                    </tr>`
                                );
                            }
                            resolve();
                        } else {
                            let id = $('#halk_id').val();
                            if (!id) return;
                            $.ajax({
                                type: 'DELETE',
                                url: `{{ url('stock/material/halks/item') }}/${id}`,
                                dataType: 'json',
                                data: {
                                    "details_id": rowId,
                                },
                                success: function(response) {
                                    if (response.status === 200) {
                                        handleResponseMessageError(
                                            response.message,
                                            'تم الحذف', 'success')
                                        rowParent.remove();
                                        if (tableBody.find('tr')
                                            .length === 0) {
                                            tableBody.append(
                                                `<tr class="not-found">
                                                    <td colspan="7">لا يوجد بيانات</td>
                                                </tr>`
                                            );
                                        }
                                        resolve();
                                    }
                                },
                                error: function(error) {
                                    handleResponseMessageError(error
                                        .responseJSON
                                        .message, 'خطأ', 'error')
                                    resolve();
                                },
                            });
                        }
                    })
                }

            });



        });



        $(document).on('click', '.edit_material', function() {
            let rowParent = $(this).parents('tr');
            rowParent.addClass('edit');
            rowParent.find('input').eq(0).focus().select()
        });


        $(document).on('click', '.update_material', function() {

            let rowParent = $(this).closest('tr'); // Get the row being updated

            let qty = parseFloat(rowParent.find('input.material_quantity').val()) || 0;

            // Update the table cells
            rowParent.find('td').eq(4).find('span').text(qty); // Update quantity

            // Remove 'edit' class to return to base state
            rowParent.removeClass('edit');


        });

        /**
         * Collects all form data including item_id and quantity into an array
         * @returns {object} Data to send in the AJAX request
         */
        function collectFormData() {

            // Collect item and quantity as an array
            let arr = [];
            tableBody.find('tr').each(function(row) {

                let rowId = $(this).attr('rowId');
                let quantity = $(this).find('td').eq(4).text();

                arr.push({
                    item_id: rowId,
                    qty: quantity.trim()
                });
            });

            return {
                serial_nr: serial_nr.val(),
                halk_item_date: date.val(),
                notes: notes.val(),
                branch_id: branchs.val(),
                section_id: sections.val(),
                items: arr,
            };
        }


        $(document).on('click', '#save_material_halk', function() {
            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('stock.material.halks.item.store') }}",
                dataType: 'json',
                data: collectFormData(),
                success: function(response) {
                    if (response.status == 201) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    }
                    if (response.status == 422) {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                    button.html(originalHtml).prop('disabled', false);
                    setTimeout(() => {
                        window.location.reload();
                    }, 300)

                },
                error: handleAjaxError,
                complete: function() {
                    button.html(originalHtml).prop('disabled', false);
                }
            });
        });

        $(document).on('change', '#halk_item_nr', function(params) {
            let halk_item = $(this).val();
            if (!halk_item) return;
            fetch(`/stock/material/halks/item/${halk_item}`)
                .then((response) => response.json())
                .then((data) => {
                    resetPage();
                    displayInvoices(data.data);
                })
                .catch((error) => errorMsg(error));
        })

        function displayInvoices(invoice) {
            $('#halk_id').val(invoice.id).attr('disabled', true)
            serial_nr.val(invoice.serial_nr)
            serial_nr.attr('disabled', true);
            date.val(invoice.halk_item_date)
            notes.val(invoice.notes)
            updateInvoiceMethod(invoice)
            updateTableData(invoice)
            saveBtn[0].classList.add("d-none");
            updateBtn[0].classList.remove("d-none");
            updateBtn.attr('data-id', invoice.id)
            checkForm()
        }

        function updateInvoiceMethod(invoice) {
            preventChangeEvent = true;
            sections.empty()
                .val("<option selected disabled>اختر القسم </option>")
                .trigger("change");
            document
                .querySelectorAll(".stores")
                .forEach((el) => el.classList.add("d-none"));
            document
                .querySelectorAll(".branch-sec")
                .forEach((el) => el.classList.remove("d-none"));
            branchs.val(invoice.section.branch.id).trigger('change').attr("disabled", true)
            sections.append(
                `<option  value="${invoice.section.id}" selected>${invoice.section.name}</option>`
            ).trigger('change').attr("disabled", true);

        }

        function updateTableData(invoice) {
            if (!invoice.details.length) return;

            let html = ''; // Initialize html as an empty string

            invoice.details.forEach((item) => {
                html += `<tr rowId="${item.item.id}" data-id="${item.id}" class="old">
            <td>${invoice.id}</td>
            <td>${invoice.section.branch.name}</td>
            <td>${invoice.section.name}</td>
            <td>${item.item.name}</td>

            <td>
                <input type="number" class="material_quantity" value="${item.qty}"/>
                <span>${item.qty}</span>
            </td>
            <td>
                <div class="del-edit">
                    <button class="btn btn-danger delete_material"><i class="fa-regular fa-trash-can"></i></button>
                    <button class="btn btn-warning edit_material"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>
                <button class="btn btn-primary update_material update">تعديل</button>
            </td>
        </tr>`;
            });

            // Remove the 'not-found' row if it exists
            tableBody.find('tr.not-found').remove();

            // Update the table body with new rows
            tableBody.html(html); // Use the updated html string directly
        }

        $('#update_material_halk').on('click', function() {
            let button = $(this);
            let id = button.attr('data-id');
            if (!id) return;
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            let url = '{{ url('stock/material/halks/item') }}/' + id;
            $.ajax({
                type: 'PUT',
                url: url,
                dataType: 'json',
                data: collectFormData(),
                success: function(response) {
                    if (response.status == 200) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                    }
                    if (response.status == 422) {
                        Toast.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                    button.html(originalHtml).prop('disabled', false);
                    setTimeout(() => {
                        window.location.reload();
                    }, 300)
                },
                error: handleAjaxError,
                complete: function() {
                    button.html(originalHtml).prop('disabled', false);
                }
            });
        });
    });
</script>

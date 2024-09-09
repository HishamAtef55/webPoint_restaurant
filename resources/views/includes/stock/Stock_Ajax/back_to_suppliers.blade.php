@include('includes.stock.Stock_Ajax.public_function')
<script>
    let serial_number = $('#serial_number');
    let supplier = $('#supplier_id');
    let date = $('#refund_date');
    let branchs = $('#branch_id');
    let sections = $('#section_id')
    let stores = $('#store_id');
    let materials = $('#material_refund').find('select[name="material_id"]')
    let material_expire_date = $('#material_refund').find('input[name="expire_date"]')
    let material_quantity = $('#material_refund').find('input[name="quantity"]')
    let material_unit = $('#material_refund').find('input[name="unit"]')
    let material_price = $('#material_refund').find('input[name="price"]')
    let material_total_price = $('#material_refund').find('input[name="total_price"]')
    let material_last_price = $('#material_refund').find('input[name="last_price"]')
    let material_current_Balance = $('#material_refund').find('input[name="current_balance"]')
    let notes = $('#notes');
    let tableBody = $('.table-refund tbody');
    let tableFoot = $('.table-refund tfoot');
    let updateBtn = $('#update_supplier_refund');
    let saveBtn = $('#save_supplier_refund');
    let deleteBtn = $('#delete_purchases');
    let refund_image;
    let now = new Date();
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );


    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);

    let today = now.getFullYear() + "-" + (month) + "-" + (day);

    let preventChangeEvent = false; // Flag to control change event execution

    $(document).ready(function() {
        getSections();


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

        $('#refund_image').on('change', function(e) {
            refund_image = e.target.files[0]
        })

        /*
         * trigger method event
         */
        document
            .querySelectorAll('input[name="purchases_method"]')
            .forEach((radio) => {
                radio.addEventListener("change", changePurchasesMethod);
            });

        /*
         * changePurchasesMethod
         */
        function changePurchasesMethod() {
            const firstBranchOptionValue = branchs.find("option:first").val();
            const firstStoreOptionValue = stores.find("option:first").val();
            const purchasesMethod = document.querySelector(
                'input[name="purchases_method"]:checked'
            )?.value;

            if (purchasesMethod === "sections") {
                sections.empty()
                    .val("<option selected disabled>اختر القسم </option>")
                    .trigger("change");
                branchs.val(firstBranchOptionValue).change();
                supplier.val(supplier.find("option:first").val()).change();
                document
                    .querySelectorAll(".stores")
                    .forEach((el) => el.classList.add("d-none"));
                document
                    .querySelectorAll(".branch-sec")
                    .forEach((el) => el.classList.remove("d-none"));
                resetPage()
            } else if (purchasesMethod === "stores") {
                stores.val(firstStoreOptionValue).change();
                document
                    .querySelectorAll(".branch-sec")
                    .forEach((el) => el.classList.add("d-none"));
                document
                    .querySelectorAll(".stores")
                    .forEach((el) => el.classList.remove("d-none"));
                resetPage()
            }
        }

        branchs.on("change", getSections);

        /*
         * getSections
         */
        function getSections() {
            const branchSelectId = branchs.val();
            console.log(branchSelectId)
            if (!branchSelectId) {
                return;
            }
            if (preventChangeEvent) return;
            resetPage()
            fetch(`/stock/purchases/sections/filter/${branchSelectId}`)
                .then((response) => response.json())
                .then((data) => {
                    displaySections(data.data);
                })
                .catch((error) => errorMsg(error));
        }

        /*
         * displaySections
         */
        function displaySections(sections) {
            let container = $("#section_id");
            let html = '';
            if (!sections.length) {
                html += `<option value="">لاتوجد اقسام</option>`;
            } else {
                html = '<option selected disabled>اختر القسم</option>';
                sections.forEach((section) => {
                    html += `<option value="${section.id}">${section.name}</option>`;
                });
            }

            container.html(html);
            // container.select2({
            //     dir: "rtl",
            // });

            // container.select2("open");
        }

        materials.on('change', function(params) {
            let materialSelectVal = materials.val();
            if (!materialSelectVal) {
                return;
            }
            let material = $(this).find("option:selected");
            let lastPriceAttr = material.attr('data-last-price');
            let lastPrice = parseFloat(lastPriceAttr) / 100;

            // Check if lastPrice is a valid number, otherwise default to 0
            let formattedLastPrice = isNaN(lastPrice) ? '0.00' : lastPrice.toFixed(2);

            let method = document.querySelector(
                'input[name="purchases_method"]:checked'
            )?.value;
            let initialParams = {};
            if (method === "sections") {
                if (!sections.val()) {
                    Toast.fire({
                        icon: 'error',
                        title: 'يجب اختبار قسم'
                    });
                    return false
                }
                initialParams = {
                    "section_id": sections.val(),
                    "type": "sections"
                };
            } else if (method === "stores") {
                if (!stores.val()) {
                    Toast.fire({
                        icon: 'error',
                        title: 'يجب اختبار مخزن'
                    });
                    return false
                }
                initialParams = {
                    "store_id": stores.val(),
                    "type": "stores"
                };
            }

            let url = '{{ url('stock/purchases') }}/' + materialSelectVal + '/filter';
            let queryString = $.param(initialParams);


            $.ajax({
                type: "GET",
                url: `${url}?${queryString}`,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        material_unit.val(material.attr('data-unit'))
                        material_current_Balance.val(response.qty)
                        material_last_price.val(formattedLastPrice)
                        checkForm()
                        setTimeout(() => {
                            material_price.focus();
                        }, 100);
                    }

                },
                error: handleAjaxError,
            })

        })


        material_quantity.on('change', function() {
            if (material_price.val()) {
                material_total_price.val((+material_price.val() * +$(this).val()).toFixed(2))
            }
            if (material_total_price.val()) {
                material_price.val((+material_total_price.val() / +$(this).val()).toFixed(2))
            }
            checkForm();
        });

        material_price.on('change', function() {
            if (material_quantity.val()) {
                material_total_price.val((+material_quantity.val() * +$(this).val()).toFixed(2))
            }
            if (material_total_price.val()) {
                material_quantity.val((+material_total_price.val() / +$(this).val()).toFixed(2))
            }
            checkForm();
        });

        material_total_price.on('change', function() {
            if (material_quantity.val()) {
                material_price.val((+$(this).val() / +material_quantity.val()).toFixed(2))
            }
            if (material_price.val()) {
                material_quantity.val((+$(this).val() / +material_price.val()).toFixed(2))
            }
            checkForm();
        });

        function addDataToTable() {
            let material = materials.find("option:selected");
            let code = materials.val();
            let name = material.html();
            let unit = material.attr('data-unit')

            if (!code) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب اختبار خامة'
                });
                return false
            }
            if (!material_price.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال سعر'
                });
                return false
            }
            if (!material_quantity.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال كمية'
                });
                return false
            }
            if (!material_total_price.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال اجمالى'
                });
                return false
            }


            if (+material_quantity.val() < 0) {
                Toast.fire({
                    icon: 'error',
                    title: 'لايمكن إضافة كمية'
                });
                return false
            }


            let html = `<tr rowId="${code}" class="new">

                <td>${code}</td>
                <td>${name}</td>
                <td>${material_expire_date.val() || '-'}</td>
                <td>${unit}</td>
                <td>
                    <input type="number" value="${material_price.val()}" class="material_price"/>
                    <span>${parseFloat(material_price.val()).toFixed(2)}</span>
                </td>
                <td>
                    <input type="number" class="material_quantity" value="${material_quantity.val()}"/>
                    <span>${material_quantity.val()}</span>
                </td>
                <td class="totalPrice">${material_total_price.val()}</td>
                
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
            material_total_price.val('');
            material_quantity.val('');
            material_price.val('');
            material_unit.val('');

            materials.select2('open');
            calcTotal();
            checkForm();
        }

        material_quantity.on('keyup', function(e) {
            if (e.keyCode === 13) {
                material_total_price.focus();
            }
        });

        material_price.on('keyup', function(e) {
            if (e.keyCode === 13) {
                material_quantity.focus();
            }
        });

        material_total_price.on('keyup', function(e) {
            if (e.keyCode === 13) {
                addDataToTable();
            }
        });




        function calcTotal() {
            let totalPrice = 0;
            let finalTotal = 0;

            // Sum totalPrice from elements with class 'totalPrice'
            $('.totalPrice').each(function() {
                let value = parseFloat($(this).text()) || 0;
                totalPrice += value;
            });

            $('.sumTotal').text(totalPrice.toFixed(2));
        }

        $(document).on('click', '.delete_material', function() {
            let rowParent = $(this).closest('tr');
            let rowId = rowParent.attr('data-id');

            Swal.fire({
                title: 'حذف !',
                text: 'هل انت متأكد من حذف الخامة',
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
                                        <td colspan="8">لا يوجد بيانات</td>
                                    </tr>`
                                );
                            }
                            calcTotal();
                            resolve();
                        } else {
                            let id = $('#refund_id').val();
                            if (!id) return;
                            $.ajax({
                                type: 'DELETE',
                                url: `{{ url('stock/material/supplier/refund') }}/${id}`,
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
                                        <td colspan="8">لا يوجد بيانات</td>
                                    </tr>`
                                            );
                                        }
                                        calcTotal();
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

            let price = parseFloat(rowParent.find('input.material_price').val()) || 0;
            let qty = parseFloat(rowParent.find('input.material_quantity').val()) || 0;
            let total = price * qty;

            // Update the table cells
            rowParent.find('td').eq(4).find('span').text(price.toFixed(2)); // Update price
            rowParent.find('td').eq(5).find('span').text(qty); // Update quantity
            rowParent.find('td').eq(6).text(total.toFixed(2)); // Update total price

            // Remove 'edit' class to return to base state
            rowParent.removeClass('edit');

            // Recalculate totals for the table
            calcTotal();
        });

        function setData(method = null) {
            let refund_method = $('input[name="purchases_method"]:checked').val();
            let materialArray = [];
            tableBody.find('tr').each(function() {
                let material_id = $(this).attr('rowId').trim();
                let expire_date = $(this).find('td').eq(2).text().trim();
                let price = $(this).find('td').eq(4).text().trim();
                let qty = $(this).find('td').eq(5).text().trim();
                let total = $(this).find('td').eq(6).text().trim();


                materialArray.push({
                    material_id: material_id,
                    expire_date: expire_date,
                    price: price,
                    qty: qty,
                    total: total

                });
            });

            let formData = new FormData();
            if (method) {
                formData.append('_method', method);

            }
            formData.append("refund_method", refund_method);
            formData.append("serial_nr", serial_number.val());
            formData.append("supplier_id", supplier.val());
            formData.append("section_id", sections.val());
            formData.append("store_id", stores.val());

            formData.append("refund_date", date.val());

            formData.append("materialArray", JSON.stringify(materialArray));

            formData.append("total", $('.sumTotal').text());

            formData.append("notes", notes.val());
            formData.append("refund_image", refund_image);
            return formData;
        }

        $(document).on('click', '#save_supplier_refund', function() {
            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('stock.material.supplier.refund.store') }}",
                dataType: 'json',
                enctype: "multipart/form-data",
                processData: false,
                cache: false,
                contentType: false,
                data: setData(),
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

        $(document).on('change', '#refund_id', function(params) {
            let refund = $(this).val();
            if (!refund) return;
            fetch(`/stock/material/supplier/refund/${refund}`)
                .then((response) => response.json())
                .then((data) => {
                    displayInvoices(data.data);
                })
                .catch((error) => errorMsg(error));
        })

        function displayInvoices(invoice) {
            $('#refund_nr').val(invoice.id).attr('disabled', true)
            serial_number.val(invoice.serial_nr)
            serial_number.attr('disabled', true);
            supplier.val(invoice.supplier.id).trigger('change')
            date.val(invoice.refund_date)
            notes.val(invoice.notes)
            updateInvoiceMethod(invoice, invoice.refund_method)
            updateTableData(invoice.details)
            saveBtn[0].classList.add("d-none");
            updateBtn[0].classList.remove("d-none");
            updateBtn.attr('data-id', invoice.id)
            checkForm()
        }

        function updateInvoiceMethod(invoice, method) {

            if (method === "sections") {
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
                ).attr("disabled", true)

            } else if (method === "stores") {
                document
                    .querySelectorAll(".branch-sec")
                    .forEach((el) => el.classList.add("d-none"));
                document
                    .querySelectorAll(".stores")
                    .forEach((el) => el.classList.remove("d-none"));
                stores.val(invoice.store.id).trigger('change').attr("disabled", true)

            }
            let existingMethodCheckboxContainer = $('.method-checkbox');

            // Define the new HTML content
            let newMethodCheckboxContainer = `
                <div class='form-check'>
                    <input class="form-check-input purchases-method" type="radio" value="${method}"
                        id="${method}_method" name="purchases_method" checked>
                    <label class="form-check-label" for="${method}_method">
                        ${method === 'sections' ? 'اقسام' : 'مخازن'}
                    </label>
                </div>`;

            // Update the HTML content using jQuery
            existingMethodCheckboxContainer.html(newMethodCheckboxContainer);
            preventChangeEvent = false;
        }

        function updateTableData(details) {
            if (!details.length) return;

            let html = ''; // Initialize html as an empty string

            details.forEach((item) => {
                html += `
                <tr rowId="${item.material.id}" data-id="${item.id}" class="old">
                    <td>${item.material.id}</td>
                    <td>${item.material.name}</td>
                    <td>${item.expire_date}</td>
                    <td>${item.material.unit.name_ar}</td>
                    <td>
                        <input type="number" value="${item.price}" class="material_price"/>
                        <span>${parseFloat(item.price).toFixed(2)}</span>
                    </td>
                    <td>
                        <input type="number" class="material_quantity" value="${item.qty}"/>
                        <span>${item.qty}</span>
                    </td>
                    <td class="totalPrice">${parseFloat(item.total).toFixed(2)}</td>
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
            calcTotal();
        }

        $('#update_supplier_refund').on('click', function() {
            let button = $(this);
            let id = button.attr('data-id');
            if (!id) return;
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            let url = '{{ url('stock/material/supplier/refund') }}/' + id;
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                enctype: "multipart/form-data",
                processData: false,
                cache: false,
                contentType: false,
                data: setData(),
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

        function resetPage() {
            materials.val(materials.find("option:first").val()).change();
            material_quantity.val('')
            material_unit.val('')
            material_price.val('')
            material_total_price.val('')
            material_last_price.val('')
            material_current_Balance.val('')
            tableBody.html('<tr class="not-found"> <td colspan="8">لا يوجد بيانات</td></tr>');
            calcTotal();
            checkForm();
        }

    })
</script>

@include('includes.stock.Stock_Ajax.public_function')
<script>
    let serial_nr = $('#serial_nr');
    let date = $('#halk_date');
    let branchs = $('#branch_id');
    let sections = $('#section_id')
    let stores = $('#store_id');
    let materials = $('#transfer_materials').find('select[name="material_id"]')
    let material_quantity = $('#transfer_materials').find('input[name="quantity"]')
    let material_unit = $('#transfer_materials').find('input[name="unit"]')
    let material_price = $('#transfer_materials').find('input[name="price"]')
    let material_total_price = $('#transfer_materials').find('input[name="total_price"]')
    let material_current_Balance = $('#transfer_materials').find('input[name="current_balance"]')
    let notes = $('#notes');
    let tableBody = $('.table-purchases tbody');
    let tableFoot = $('.table-purchases tfoot');
    let updateBtn = $('#update_material_halk');
    let saveBtn = $('#save_material_halk');
    let deleteBtn = $('#delete_material_halk');
    let image;
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

        getMaterials();

        getSectionsMaterials()

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

        $('#image').on('change', function(e) {
            image = e.target.files[0]
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

        stores.on("change", getMaterials)


        branchs.on("change", getSections);

        sections.on("change", getSectionsMaterials)

        /*
         * getMaterials
         */
        function getSectionsMaterials() {
            const sectionSelectId = sections.val();
            if (!sectionSelectId) return;

            if (preventChangeEvent) return;
            fetch(`/stock/material/transfer/filter/${sectionSelectId}`)
                .then((response) => response.json())
                .then((data) => {
                    displaySectionsMaterials(data.data);
                })
                .catch((error) => errorMsg(error));
        }

        /*
         * getMaterials
         */
        function getMaterials() {
            const storeSelectId = stores.val();
            if (!storeSelectId) return;

            if (preventChangeEvent) return;
            resetPage();
            fetch(`/stock/exchange/materials/filter/${storeSelectId}`)
                .then((response) => response.json())
                .then((data) => {
                    displayMaterials(data.data);
                })
                .catch((error) => errorMsg(error));
        }

        /*
         * displayMaterials
         */
        function displayMaterials(data) {
            let container = $("#material_id");
            let html = '';
            if (!data.balance.length) {
                html += `<option value="">لاتوجد خامات</option>`;
            } else {
                html = `<option value="" disabled selected>اختر الخامة</option>`;
                data.balance.forEach((balance) => {
                    html += `<option value="${balance.material.id}"
                                     data-unit=${balance.material.unit.sub_unit.name_ar}
                                     data-current-balance=${balance.qty}
                                     data-price=${balance.avg_price}
                                  >
                                  ${balance.material.name}
                                  </option>`;
                });
            }
            container.html(html);
        }

        /*
         * getSections
         */
        function getSections() {
            const branchSelectId = branchs.val();
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

        /*
         * displayMaterials
         */
        function displaySectionsMaterials(data) {
            let container = $("#material_id");
            let html = '';
            if (!data.balance.length) {
                html += `<option value="">لاتوجد خامات</option>`;
            } else {
                html = `<option value="" disabled selected>اختر الخامة</option>`;
                data.balance.forEach((balance) => {
                    html += `<option value="${balance.material.id}"
                                     data-unit=${balance.material.unit.sub_unit.name_ar}
                                     data-current-balance=${balance.qty}
                                     data-price=${balance.avg_price}
                                  >
                                  ${balance.material.name}
                                  </option>`;
                });
            }
            container.html(html);
        }


        function resetPage() {
            materials.html(`
                         <select class="form-select" name="material_id" id="material_id">
                            <option value="" selected disabled>اختر الخامة</option>
                        </select>`)
            material_quantity.val('')
            material_unit.val('')
            material_price.val('')
            material_total_price.val('')
            material_current_Balance.val('')
            tableBody.html('<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>');
            calcTotal();
            checkForm();
        }

        materials.on('change', function(params) {
            let material = $(this).find("option:selected");

            material_unit.val(material.attr('data-unit'))
            material_current_Balance.val(material.attr('data-current-balance'))
            material_price.val(material.attr('data-price'))
            checkForm()
            setTimeout(() => {
                material_quantity.focus();
            }, 100);



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

            if (+material_quantity.val() > +material_current_Balance.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال كمية اقل او تساوى من الرصيد الحالى'
                });
                return false
            }

            let finalTotal = material_total_price.val();

            let html = `<tr rowId="${code}" class="new">

                <td>${code}</td>
                <td>${name}</td>
                <td>${unit}</td>
                <td class="material_price">${parseFloat(material_price.val()).toFixed(2)}</td>
                <td>
                    <input type="number" class="material_quantity" value="${material_quantity.val()}"/>
                    <span>${material_quantity.val()}</span>
                </td>
                
                <td class="totalPrice">${parseFloat(finalTotal).toFixed(2)}</td>
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
            material_current_Balance.val('');

            materials.select2('open');
            calcTotal();
            checkForm();
        }


        material_quantity.on('keyup', function(e) {
            if (e.keyCode === 13) {
                material_total_price.focus();
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
            let rowId = rowParent.attr('rowId');

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
                                        <td colspan="7">لا يوجد بيانات</td>
                                    </tr>`
                                );
                            }
                            calcTotal();
                            resolve();
                        } else {
                            let id = $('#halk_id').val();
                            if (!id) return;
                            $.ajax({
                                type: 'DELETE',
                                url: `{{ url('stock/material/halk') }}/${id}`,
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

            let price = parseFloat(rowParent.find('.material_price').text()) || 0;
            let qty = parseFloat(rowParent.find('input.material_quantity').val()) || 0;
            let total = price * qty;

            // Update the table cells
            rowParent.find('td').eq(3).find('span').text(price.toFixed(2)); // Update price
            rowParent.find('td').eq(4).find('span').text(qty); // Update quantity
            rowParent.find('td').eq(5).text(total.toFixed(2)); // Update total price

            // Remove 'edit' class to return to base state
            rowParent.removeClass('edit');

            // Recalculate totals for the table
            calcTotal();
        });

        function setData(method = null) {
            let materialArray = [];
            let halk_type = $('input[name="purchases_method"]:checked').val();
            tableBody.find('tr').each(function() {
                materialArray.push({
                    material_id: $(this).find('td').eq(0).text(),
                    price: $(this).find('td').eq(3).text(),
                    qty: $(this).find('td').eq(4).text(),
                    total: $(this).find('td').eq(5).text(),
                });
            });

            let formData = new FormData();
            if (method) {
                formData.append('_method', method);

            }
            formData.append("halk_type", halk_type);
            if (halk_type == "sections") {
                formData.append("section_id", sections.val());
            } else if (halk_type === "stores") {
                formData.append("store_id", stores.val());
            }
            formData.append("serial_nr", serial_nr.val());
            formData.append("halk_type", halk_type);
            formData.append("halk_date", date.val());
            formData.append("image", image);
            formData.append("notes", notes.val());
            formData.append("total", $('.sumTotal').text());

            formData.append("materialArray", JSON.stringify(materialArray));

            return formData;
        }

        $(document).on('click', '#save_material_halk', function() {
            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('stock.material.halk.store') }}",
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

        $(document).on('change', '#halk_nr', function(params) {
            let transfer = $(this).val();
            if (!transfer) return;
            fetch(`/stock/material/halk/${transfer}`)
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
            date.val(invoice.halk_date)
            notes.val(invoice.notes)
            updateInvoiceMethod(invoice, invoice.halk_type)
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
                html += `<tr rowId="${item.id}" class="old">
            <td>${item.material.id}</td>
            <td>${item.material.name}</td>
            <td>${item.material.unit.name_ar}</td>
            <td class="material_price">${parseFloat(item.price).toFixed(2)}</td>
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

        $('#update_material_halk').on('click', function() {
            let button = $(this);
            let id = button.attr('data-id');
            if (!id) return;
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            let url = '{{ url('stock/material/halk') }}/' + id;
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
    });
</script>

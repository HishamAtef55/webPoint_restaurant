@include('includes.stock.Stock_Ajax.public_function')
<script>
    let serial_number = $('#serial_number');
    let supplier = $('#supplier_id');
    let tax = $('#tax');
    let date = $('#purchases_date');
    let branchs = $('#branch_id');
    let sections = $('#section_id')
    let stores = $('#store_id');
    let materials = $('#material_purchases').find('select[name="material_id"]')
    let material_expire_date = $('#material_purchases').find('input[name="expire_date"]')
    let material_quantity = $('#material_purchases').find('input[name="quantity"]')
    let material_unit = $('#material_purchases').find('input[name="unit"]')
    let material_price = $('#material_purchases').find('input[name="price"]')
    let material_discount = $('#material_purchases').find('input[name="discount"]')
    let material_total_price = $('#material_purchases').find('input[name="total_price"]')
    let notes = $('#notes');
    let tableBody = $('.table-purchases tbody');
    let tableFoot = $('.table-purchases tfoot');
    let permissionId = $('#permissionId');
    let updateBtn = $('#update_purchases');
    let saveBtn = $('#save_purchases');
    let deleteBtn = $('#delete_purchases');
    let addToTableBtn = $('#material_purchases').find('button[id="arrow-down"]')
    let purchases_image;
    let now = new Date();
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );


    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);

    let today = now.getFullYear() + "-" + (month) + "-" + (day);

    $(document).ready(function() {



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


        $('#invoice_image').on('change', function(e) {
            purchases_image = e.target.files[0]
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
            } else if (purchasesMethod === "stores") {
                stores.val(firstStoreOptionValue).change();
                document
                    .querySelectorAll(".branch-sec")
                    .forEach((el) => el.classList.add("d-none"));
                document
                    .querySelectorAll(".stores")
                    .forEach((el) => el.classList.remove("d-none"));
            }
        }

        branchs.on("change", getSections);

        addToTableBtn.on("click", addDataToTable)

        /*
         * getSections
         */
        function getSections(event) {
            const branchSelectId = event.target.value;
            if (!branchSelectId) {
                return;
            }

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
            let html = '<option selected disabled>اختر القسم</option>';
            if (!sections.length) {
                html += `<option value="">لاتوجد اقسام</option>`;
            } else {
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

        // apply material unit

        materials.on('change', function(params) {
            let material = $(this).find("option:selected");
            material_unit.val(material.attr('data-unit'))
            checkForm()
            setTimeout(() => {
                material_price.focus();
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
            if (!tax.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال الضريبة'
                });
                return false
            }

            if (!material_discount.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب ادخال الخصم'
                });
                return false
            }
            let finalTotal = material_total_price.val() - material_discount.val();

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
                <td class="material_discount">${parseFloat(material_discount.val()).toFixed(2)}</td>
                
                <td class="finalTotal">${parseFloat(finalTotal).toFixed(2)}</td>
                <td>
                    <div class="del-edit">
                        <button class="btn btn-danger delete_material"><i class="fa-regular fa-trash-can"></i></button>
                        <button class="btn btn-warning edit_material"><i class="fa-regular fa-pen-to-square"></i></button>
                    </div>
                    <button class="btn btn-primary update_material update">Update</button>
                </td>
                </tr>`

            tableBody.find('tr.not-found').length ? $('tr.not-found').remove() : '';
            tableBody.append($(html));
            material_total_price.val('');
            material_quantity.val('');
            material_price.val('');
            material_unit.val('');
            material_discount.val('');

            materials.select2('open');
            calcTotal();
            checkForm();
        }

        material_discount.on('keyup', function(e) {
            if (e.keyCode === 13) {
                addDataToTable()
            }
        });

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
                material_discount.focus();
            }
        });

        material_discount.on('keyup', function(e) {
            if (e.keyCode === 13) {
                addDataToTable();
            }
        })


        function calcTotal() {
            let totalPrice = 0;
            let totalDiscount = 0;
            let finalTotal = 0;
            let taxValue = 0;

            // Sum totalPrice from elements with class 'totalPrice'
            $('.totalPrice').each(function() {
                let value = parseFloat($(this).text()) || 0;
                totalPrice += value;
            });

            // Sum totalDiscount from elements with class 'material_discount'
            $('.material_discount').each(function() {
                let value = parseFloat($(this).text()) || 0;
                totalDiscount += value;
            });

            // Sum finalTotal from elements with class 'finalTotal'
            $('.finalTotal').each(function() {
                let value = parseFloat($(this).text()) || 0;
                finalTotal += value;
            });

            $('.sumTotal').text(totalPrice.toFixed(2));
            $('.sumDiscount').text(totalDiscount.toFixed(2));
            $('.sumFinal').text(finalTotal.toFixed(2));
            taxValue = finalTotal.toFixed(2) * tax.val() / 100
            $('.sumTax').text(taxValue.toFixed(2))
            $('.netTotalPrice').text((finalTotal + taxValue).toFixed(2))
        }


        $(document).on('click', '.delete_material', function() {
            let rowParent = $(this).parents('tr');
            Swal.fire({
                title: 'هل أنت متأكد؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'rgb(21, 157, 113)',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا'
            }).then((result) => {
                if (result.isConfirmed) {
                    rowParent.remove();
                    calcTotal();
                    if (tableBody.find('tr')
                        .length === 0) {
                        tableBody.append(
                            `<tr class="not-found">
                            <td colspan="10">لا يوجد بيانات</td>
                                                </tr>`);
                    }
                }
            })

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
            let discount = parseFloat(rowParent.find('.material_discount').text()) || 0;
            let total = price * qty;
            let finalTotal = (total - discount);

            // Update the table cells
            rowParent.find('td').eq(4).find('span').text(price.toFixed(2)); // Update price
            rowParent.find('td').eq(5).find('span').text(qty); // Update quantity
            rowParent.find('td').eq(6).text(total.toFixed(2)); // Update total price
            rowParent.find('td').eq(8).text(finalTotal.toFixed(2)); // Update final total

            // Remove 'edit' class to return to base state
            rowParent.removeClass('edit');

            // Recalculate totals for the table
            calcTotal();
        });

        function setData(method = null) {
            let materialArray = [];
            let purchases_method = $('input[name="purchases_method"]:checked').val();
            let payType = $('input[name="pay_method"]:checked').val();
            tableBody.find('tr.new').each(function() {
                materialArray.push({
                    material_id: $(this).find('td').eq(0).text(),
                    expire_date: $(this).find('td').eq(2).text(),
                    price: $(this).find('td').eq(4).text(),
                    qty: $(this).find('td').eq(5).text(),
                    discount: $(this).find('td').eq(7).text(),
                    total: $(this).find('td').eq(8).text(),
                });
            });

            let formData = new FormData();
            if (method) {
                formData.append('_method', method);

            }
            formData.append("purchases_method", purchases_method);
            formData.append("serial_nr", serial_number.val());
            formData.append("supplier_id", supplier.val());
            formData.append("section_id", sections.val());
            formData.append("store_id", stores.val());

            // formData.append("tax", tax.val());
            formData.append("purchases_date", date.val());

            formData.append("materialArray", JSON.stringify(materialArray));

            formData.append("sumTotal", $('.netTotalPrice').text());

            formData.append("tax", tax.val());


            formData.append("payment_type", payType);
            formData.append("notes", notes.val());
            formData.append("purchases_image", purchases_image);
            return formData;
        }

        $(document).on('click', '#save_purchases', function() {
            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('stock.purchases.store') }}",
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

                        button.html(originalHtml).prop('disabled', false);

                        setTimeout(() => {
                            window.location.reload();
                        }, 300)
                    }
                },
                error: handleAjaxError,
                complete: function() {
                    button.html(originalHtml).prop('disabled', false);
                }
            });
        });

        $(document).on('change', '#purchases_id', function(params) {
            let purchase = $(this).val();
            if (!purchase) return;
            fetch(`/stock/purchases/${purchase}`)
                .then((response) => response.json())
                .then((data) => {
                    displayInvoices(data.data);
                })
                .catch((error) => errorMsg(error));
        })

        function displayInvoices(invoice) {
            $('#invoice_id').val(invoice.id)
            serial_number.val(invoice.serial_nr)
            supplier.val(invoice.supplier.id).trigger('change')
            date.val(invoice.purchases_date)
            tax.val(invoice.tax)
            notes.val(invoice.note)
            document.querySelector(`input[name="pay_method"][value="${invoice.payment_type}"]`).checked = true;
            updateInvoiceMethod(invoice, invoice.purchases_method)
            updateTableData(invoice.details)
            saveBtn[0].classList.add("d-none");
            updateBtn[0].classList.remove("d-none");
            updateBtn.attr('data-id', invoice.id)
            checkForm()
        }

        function updateInvoiceMethod(invoice, method) {
            const purchasesMethod = document.querySelector(
                `input[name="purchases_method"][value="${method}"]`).checked = true;

            if (method === "sections") {
                sections.empty()
                    .val("<option selected disabled>اختر القسم </option>")
                    .trigger("change");
                document
                    .querySelectorAll(".stores")
                    .forEach((el) => el.classList.add("d-none"));
                document
                    .querySelectorAll(".branch-sec")
                    .forEach((el) => el.classList.remove("d-none"));
                branchs.val(invoice.section.branch.id).trigger('change')

                sections.val(invoice.section.id).trigger('change')

            } else if (method === "stores") {
                document
                    .querySelectorAll(".branch-sec")
                    .forEach((el) => el.classList.add("d-none"));
                document
                    .querySelectorAll(".stores")
                    .forEach((el) => el.classList.remove("d-none"));
                stores.val(invoice.store.id).trigger('change')

            }
        }

        function updateTableData(details) {
            if (!details.length) return;

            let html = ''; // Initialize html as an empty string

            details.forEach((item) => {
                html += `<tr rowId="${item.id}" class="new">
            <td>${item.id}</td>
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
            <td class="totalPrice">${item.total}</td>
            <td class="material_discount">${parseFloat(item.discount).toFixed(2)}</td>
            <td class="finalTotal">${parseFloat(item.total).toFixed(2)}</td>
            <td>
                <div class="del-edit">
                    <button class="btn btn-danger delete_material"><i class="fa-regular fa-trash-can"></i></button>
                    <button class="btn btn-warning edit_material"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>
                <button class="btn btn-primary update_material update">Update</button>
            </td>
        </tr>`;
            });

            // Remove the 'not-found' row if it exists
            tableBody.find('tr.not-found').remove();

            // Update the table body with new rows
            tableBody.html(html); // Use the updated html string directly
            calcTotal();
        }

        $('#update_purchases').on('click', function() {
            let button = $(this);
            let id = button.attr('data-id');
            if (!id) return;
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);
            let url = '{{ url("stock/purchases") }}/' + id;
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

                        button.html(originalHtml).prop('disabled', false);
                    }
                },
                error: handleAjaxError,
                complete: function() {
                    button.html(originalHtml).prop('disabled', false);
                }
            });
        });


    })
</script>

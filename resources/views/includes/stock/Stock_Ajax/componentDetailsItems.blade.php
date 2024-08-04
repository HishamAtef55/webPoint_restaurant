@include('includes.stock.Stock_Ajax.public_function')
<script>
    // Common function to handle AJAX errors
    function handleAjaxError(reject) {
        let response = $.parseJSON(reject.responseText);
        $.each(response.errors, function(key, val) {
            errorMsg(val[0]);
        });
    }

    const unitToArabic = {
        'ml': 'مللى',
        'gm': 'جرام',
        'number': 'عدد'
        // Add other units as needed
    };

    // handle csrf request header

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    })

    $(document).ready(function() {
        let branch = $('#branch');
        let items = $('#items');
        let details = $('#detailsItems');
        let itemPrice = $('#item_price');
        let productQty = $('#product_qty');
        let mainGroup = $('#main_group');
        let materials = $('#materials');
        let unitLabel = $('#unit_label');
        let unitInput = $('#unit');
        let unitPriceInput = $('#unit_price');
        let materialArray = [];
        let materialTable = $('.table-materials');
        let totalPriceInput = materialTable.find('tfoot .total-price');
        let percentageInput = materialTable.find('tfoot .percentage');
        let saveComponent = $('#save_component');
        let DetailsWithoutMaterials = $('#DetailsWithoutMaterials');
        let printComponents = $('#print_components');
        let printDetails = $('#printDetails');
        let printComponent = $('#print_component');
        let reportModal = $('#reportModal');
        let tableBody = $('.table-materials tbody');

        let spinner = $(
            '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
        );


        /*  ======================== Start All Functions ============================== */
        function getItems(branchVal, itemsDiv, materialDiv) {
            $.ajax({
                url: "{{ route('getItemDetails') }}",
                method: 'post',
                data: {
                    branch: branchVal,
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '<option value="" disabled selected></option>';
                        let materialHtml = '<option value="" disabled selected></option>';
                        data.items.forEach((item) => {
                            html +=
                                `<option value="${item.id}" data-price="${item.price}" >${item.name}</option>`
                        });
                        data.materials.forEach((material) => {
                            materialHtml +=
                                `<option value="${material.id}"
                                 data-cost="${material.cost}"
                                 data-unit="${material.unit.sub_unit.name_ar}"
                                 data-unit-name-en="${material.unit.sub_unit.name_en}"
                                 data-unit-value="${material.unit.sub_unit.value}"
                                 
                                 >${material.name}</option>`
                        });

                        materialDiv.html(materialHtml)

                        itemsDiv.html(html)
                        itemsDiv.select2({
                            dir: "rtl"
                        });

                        itemsDiv.select2('open');
                    }
                },
            });
        }

        function customMatcher(params, data) {
            // Always return the object if there is nothing to compare
            if ($.trim(params.term) === '') {
                return data;
            }

            // Check if the text contains the term
            if (data.text.indexOf(params.term) > -1) {
                return data;
            }

            // Check if the data occurs
            if ($(data.element).attr('value').toString().indexOf(params.term) > -1) {
                return data;
            }

            // If it doesn't contain the term, don't return anything
            return null;
        }
        /*  ======================== End All Functions ============================== */


        /*  ======================== Start Get Items ============================== */
        branch.select2({
            selectOnClose: true,
            dir: "rtl"
        });
        mainGroup.select2({
            selectOnClose: true,
            dir: "rtl"
        });

        branch.on('change', function() {
            getItems($(this).val(), items, materials)
        });
        /*  ======================== End Get Items ============================== */
        /* ============================ Start Get Details Items =======================*/
        items.on('change', function() {
            let price = $(this).find('option:selected').attr('data-price');
            materialArray = [];
            $.ajax({
                url: "{{ route('getDetails') }}",
                method: 'post',
                data: {
                    _token,
                    item: items.val(),
                    branch: branch.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '<option value="" disabled selected></option>';
                        data.data.forEach((details) => {
                            html +=
                                `<option value="${details.id}" data-price="${details.price}" >${details.name}</option>`
                        });
                        details.html(html)
                        details.select2({
                            dir: "rtl",
                            allowClear: true,
                            placeholder: "Select a value"
                        });
                        details.select2('open');
                    }
                },
            });
        });
        /* ============================ End Get Details Items =======================*/
        /*  ======================== Start Get Material ============================== */
        details.on('change', function() {
            let price = $(this).find('option:selected').attr('data-price');
            materialArray = [];


            $.ajax({
                url: "{{ route('getMaterialsInDetails') }}",
                method: 'post',
                data: {
                    _token,
                    item: items.val(),
                    branch: branch.val(),
                    details: details.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        itemPrice.val(price)
                        productQty.focus().select()
                        let html = '';
                        let count = 1;
                        tableBody.html('');
                        if (!data.materials || !data.materials.materials.length) {
                            tableBody.html(
                                '<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>'
                            );
                            $('.percentage').val(0)
                            $('.total-price').val(0)
                            return;
                        }
                        data.materials.materials.forEach((material) => {
                            html += `<tr id="${material.material_id}">
                        <td>${count}</td>
                        <td>${material.material_id}</td>
                        <td>${material.material_name}</td>
                        <td class="tr-qty">${material.quantity}</td>
                        <td>${unitToArabic[material.unit]}</td>
                        <td class="tr-price">${material.cost}</td>
                        <td> <button class="btn btn-danger delete_Component"><i class="fa-regular fa-trash-can"></i></button> </td>
                    </tr>`;
                            materialArray.push({
                                code: material.material_id,
                                name: material.material_name,
                                quantity: material.quantity,
                                price: material.cost,
                                unit: material.unit
                            });
                            count++
                        });
                        $('.percentage').val(data.materials.percentage)
                        $('.total-price').val(data.materials.cost)
                        $('#product_qty').val(data.materials.quantity)
                        tableBody.append($(html));
                        getRowsNumber()

                    }
                },

            });
        });
        /*  ======================== End Get Material ============================== */
        productQty.on('keyup', function(e) {
            if (e.keyCode === 13) {
                materials.select2('open');
            }
        });
        /*  ======================== Start Get Material ============================== */
        mainGroup.on('change', function() {
            $.ajax({
                url: "{{ route('components_items_get_material') }}",
                method: 'post',
                data: {
                    _token,
                    group: mainGroup.val(),
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '<option value="" disabled selected></option>';
                        data.materials.forEach(material => {
                            let unitName = material.sub_unit.sub_unit.name;
                            let unitSize = material.sub_unit.size;
                            html +=
                                `<option value="${material.code}" data-cost="${material.cost}" data-unit-name="${unitName}" data-unit-size="${unitSize}">${material.name}</option>`
                        });
                        materials.html(html);
                        materials.select2({
                            dir: "rtl",
                            matcher: customMatcher
                        });
                        materials.select2('open');
                    }
                },
            });
        });
        /*  ======================== End Get Material ============================== */
        /*  ======================== Start Material Details ============================== */
        materials.on('change', function() {
            let cost = $(this).find('option:selected').attr('data-cost');
            let unitName = $(this).find('option:selected').attr('data-unit');

            unitLabel.text(unitName);
            setTimeout(() => {
                unitInput.focus();
            }, 100);
            unitPriceInput.val('');
        });
        /*  ======================== End Material Details ============================== */

        /*  ======================== Function Calculate Total Price & Percentage ============================== */
        function calcPricePercent() {
            let totalPrice = 0;
            materialTable.find('td.tr-price').each(function() {
                totalPrice += parseFloat($(this).text());
            });
            totalPriceInput.val(totalPrice.toFixed(2));

            let percentage = (totalPrice / itemPrice.val()) * 100;

            percentageInput.val(percentage.toFixed(2));
        }

        function getRowsNumber() {
            let rowNumbers = $('.table-materials tbody tr').length;
            $('.table-materials tfoot tr td:eq(0)').text(rowNumbers)
        }

        /*  ======================== Function Calculate Total Price & Percentage ============================== */
        /*  ======================== Start Add Material In Table ============================== */
        unitInput.on('keyup', function(e) {
            let cost = materials.find('option:selected').attr('data-cost');
            let unitName = materials.find('option:selected').attr('data-unit');
            let unit = materials.find('option:selected').attr('data-unit-name-en');
            let unitSize = materials.find('option:selected').attr('data-unit-value');
            let materialCode = materials.find('option:selected').attr('value');
            let materialName = materials.find('option:selected').text();
            let unitPrice = cost / unitSize;
            let qty = $(this).val();
            let tableBody = $('.table-materials tbody');
            let counter = tableBody.find('tr').length;


            if (materials.val()) {
                unitPriceInput.val((qty * unitPrice).toFixed(2))
                if (e.keyCode === 13) {
                    if (!items.val()) {
                        Toast.fire({
                            icon: 'error',
                            title: 'يجب اختيار صنف'
                        });
                        return false
                    }
                    if (!$(this).val()) {
                        Toast.fire({
                            icon: 'error',
                            title: 'يجب ادخال كمية'
                        });
                        return false
                    }



                    if (tableBody.find(`tr#${materialCode}`).length > 0) {
                        let tableRow = tableBody.find(`tr#${materialCode}`);
                        tableRow.find('.tr-qty').text(qty);
                        tableRow.find('.tr-price').text(unitPriceInput.val());
                        materialArray.forEach(material => {
                            if (material.code == materialCode) {
                                material.quantity = qty,
                                    material.price = unitPriceInput.val()
                            }
                        });
                    } else {
                        materialArray.push({
                            code: materialCode,
                            name: materialName,
                            quantity: qty,
                            price: unitPriceInput.val(),
                            unit: unit
                        });
                        let html = `<tr id="${materialCode}">
                            <td>${counter + 1}</td>
                            <td>${materialCode}</td>
                            <td>${materialName}</td>
                            <td class="tr-qty">${qty}</td>
                            <td>${unitName}</td>
                            <td class="tr-price">${unitPriceInput.val()}</td>
                            <td> <button class="btn btn-danger delete_Component"><i class="fa-regular fa-trash-can"></i></button> </td>
                        </tr>`;
                        tableBody.find('tr.not-found').length ? $('tr.not-found').remove() : '';
                        tableBody.append($(html));
                    }
                    unitPriceInput.val('')
                    unitInput.val('')
                    calcPricePercent();
                    getRowsNumber()
                    materials.val(null).trigger("change");
                    $(this).blur();
                    setTimeout(() => {
                        materials.select2('open');
                    }, 100);
                }
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب اختيار خامة'
                });
                $(this).val('')
                return false
            }
        });
        /*  ======================== End Add Material In Table ============================== */
        /*  ======================== Start Delete Materials In Table ============================== */
        $(document).on('click', '.delete_Component', function() {
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
                    calcPricePercent();
                    $.ajax({
                        url: "{{ route('deleteDetailsRecipe') }}",
                        method: 'post',
                        data: {
                            _token,
                            code: rowParent.attr('id'),
                            branch: branch.val(),
                            items: items.val(),
                            totalPrice: totalPriceInput.val(),
                            percentage: percentageInput.val(),
                            details: details.val()
                        },
                        success: function(data) {
                            if (data.status == true) {
                                getRowsNumber();
                                materialArray = materialArray.filter(material =>
                                    material.code != rowParent.attr('id'))
                                Toast.fire({
                                    icon: 'success',
                                    title: 'تم حذف المكون بنجاح'
                                });

                            }
                        },
                        complete: function() {
                            if (tableBody.find('tr')
                                .length === 0) {
                                tableBody.append(
                                    `<tr class="not-found">
                                                    <td colspan="7">لا يوجد بيانات</td>
                                                </tr>`);
                            }
                        }

                    });
                }
            })


        });
        /*  ======================== End Delete Materials In Table ============================== */
        /*  ======================== Start Save Components Items ============================== */
        saveComponent.on('click', function() {
            if (!materials.val()) {
                Toast.fire({
                    icon: 'error',
                    title: 'يجب اختيار خامة'
                });
                return;
            }
            $.ajax({
                url: "{{ route('saveDetailsComponent') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                    items: items.val(),
                    itemPrice: itemPrice.val() || 0,
                    productQty: productQty.val(),
                    materialArray,
                    totalPrice: totalPriceInput.val(),
                    percentage: percentageInput.val() || 0,
                    details: details.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        materials.val(null);
                        mainGroup.val(null);
                        // $('.table-materials tbody').html('');
                        calcPricePercent();
                        getRowsNumber()
                        Toast.fire({
                            icon: 'success',
                            title: data.data
                        });
                        $('#product_qty').val(1)
                        materialArray = []
                        setTimeout(() => {
                            items.val(items.val()).select2('open');
                        }, 300);
                    }
                },
            });
        });
        /*  ======================== End Save Components Items ============================== */
        /*  ======================== Start itemsWithOutMaterials ============================== */
        DetailsWithoutMaterials.on('click', function() {
            let title = $(this).text();
            let branchVal = branch.val();
            if (!branchVal) {
                Toast.fire({
                    icon: 'error',
                    title: "برجاء اختيار الفرع",
                });
            }
            $.ajax({
                type: "GET",
                url: '{{ url('stock/items/details/component') }}/' + branchVal + '/filter',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {

                        let html = `<table class="report_table table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>السعر</th> 
                                    <th>الفرع</th> 
                                </tr>
                            </thead>
                            <tbody>`;
                        response.data.forEach(item => {
                            html += `<tr>
                                <td>${item.id}</td>
                                <td>${item.name}</td>
                                <td>${item.price}</td>
                                <td>${item.branch}</td>
                            </tr>`;
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
                                {
                                    extend: "pdfHtml5",
                                    orientation: 'landscape',
                                    download: "open",
                                    customize: function(doc) {
                                        // Define custom styles
                                        doc.defaultStyle.font = "Cairo";
                                        doc.styles = {
                                            tableHeader: {
                                                bold: true,
                                                fontSize: 12,
                                                fillColor: '#f0f0f0',
                                                alignment: 'center',
                                                margin: [0, 5, 0, 5],
                                                color: 'black',
                                                border: [false, false,
                                                    false, true
                                                ], // Bottom border
                                            },
                                            tableBodyEven: {
                                                fontSize: 10,
                                                fillColor: '#ffffff',
                                                alignment: 'center',
                                                margin: [0, 5, 0, 5],
                                                lineHeight: "1.5",
                                                border: [false, false,
                                                    false, true
                                                ], // Bottom border
                                            },
                                            tableBodyOdd: {
                                                fontSize: 10,
                                                fillColor: '#f9f9f9',
                                                alignment: 'center',
                                                margin: [0, 5, 0, 5],
                                                lineHeight: "1.5",
                                                border: [false, false,
                                                    false, true
                                                ], // Bottom border
                                            },
                                            tableFooter: {
                                                fontSize: 10,
                                                alignment: 'center',
                                                margin: [0, 10, 0, 10],
                                                direction: 'rtl' // Ensure RTL direction
                                            }
                                        };

                                        // Add a header
                                        doc.header = {
                                            text: 'مكونات على لاتحتوى اصناف تفاصيل ', // Arabic text
                                            fontSize: 16,
                                            bold: true,
                                            alignment: 'center',
                                            margin: [0, 20, 0,
                                                20
                                            ], // Top, right, bottom, left
                                        };

                                        // Add a footer with page numbers
                                        doc.footer = function(currentPage,
                                            pageCount) {
                                            return {
                                                text: 'Page ' +
                                                    currentPage +
                                                    ' of ' + pageCount,
                                                alignment: 'center',
                                                fontSize: 10,
                                                margin: [0, 10, 0,
                                                    10
                                                ], // Top, right, bottom, left
                                            };
                                        };

                                        // Adjust page margins for more width
                                        doc.pageMargins = [40, 60, 40,
                                            60
                                        ]; // left, top, right, bottom

                                        // Adjust table styles
                                        const table = doc.content[1].table;

                                        // Reverse column order in table body
                                        table.body.forEach(row => {
                                            if (row.length) {
                                                row.reverse();
                                            }
                                        });

                                        // Reverse column widths if they are defined
                                        if (table.widths) {
                                            table.widths.reverse();
                                        }

                                        // Apply general row styles
                                        table.body.forEach(row => {
                                            if (row.length) {
                                                row.forEach(
                                                    cell => {
                                                        if (cell
                                                            .text
                                                        ) {
                                                            cell.fontSize =
                                                                10;
                                                            cell.alignment =
                                                                'right';
                                                            cell.border = [
                                                                false,
                                                                false,
                                                                false,
                                                                true
                                                            ]; // Bottom border for each cell

                                                        }
                                                    });
                                            }
                                        });

                                        // Adjust column widths
                                        table.widths = [50, '*', '*',
                                            '*'
                                        ]; // Define widths for each column
                                    }
                                },

                                "print",
                            ],
                        });
                        reportModal.modal('show')
                        reportModal.find('#labelModel').text(title)
                    }
                },
                error: handleAjaxError,
            });
        });
        /*  ======================== End itemsWithOutMaterials ============================== */
        /*  ======================== Start printDetails ============================== */
        printDetails.on('click', function() {
            let title = $(this).text();

            if (!details.val()) {
                Toast.fire({
                    icon: 'error',
                    title: "برجاء اختيار تفاصيل الصنف",
                });
            }
            $.ajax({
                url: "{{ route('printDetails') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                    items: items.val(),
                    details: details.val(),
                },
                success: function(data) {
                    // console.log(data);
                    if (data.status == true) {
                        let html = `<table class="report_table table-striped table w-100">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الكمية</th>
                            <th>التكلفة</th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data.details_components.forEach(component => {
                            html += `<tr class="table-primary fw-bold text-center">
                        <td>${component.details.id}</td>
                        <td>${component.details.name}</td>
                        <td>-</td>
                        <td>${component.cost}</td>
                    </tr>`
                            if (component.materials.length > 0) {
                                component.materials.forEach(material => {
                                    html += `<tr class="">
                                <td>${material.material_id}</td>
                                <td>${material.material_name}</td>
                                <td>${material.quantity}</td>
                                <td>${material.cost}</td>
                            </tr>`
                                });
                            }
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
                                {
                                    extend: "pdfHtml5",
                                    orientation: 'landscape',
                                    download: "open",
                                    customize: function(doc) {
                                        // Define custom styles
                                        doc.defaultStyle.font = "Cairo";
                                        doc.styles = {
                                            tableHeader: {
                                                bold: true,
                                                fontSize: 12,
                                                fillColor: '#f0f0f0',
                                                alignment: 'center',
                                                margin: [0, 5, 0, 5],
                                                color: 'black',
                                                border: [false, false,
                                                    false, true
                                                ], // Bottom border
                                            },
                                            tableBodyEven: {
                                                fontSize: 10,
                                                fillColor: '#ffffff',
                                                alignment: 'center',
                                                margin: [0, 5, 0, 5],
                                                lineHeight: "1.5",
                                                border: [false, false,
                                                    false, true
                                                ], // Bottom border
                                            },
                                            tableBodyOdd: {
                                                fontSize: 10,
                                                fillColor: '#f9f9f9',
                                                alignment: 'center',
                                                margin: [0, 5, 0, 5],
                                                lineHeight: "1.5",
                                                border: [false, false,
                                                    false, true
                                                ], // Bottom border
                                            },
                                            tableFooter: {
                                                fontSize: 10,
                                                alignment: 'center',
                                                margin: [0, 10, 0, 10],
                                                direction: 'rtl' // Ensure RTL direction
                                            }
                                        };

                                        // Add a header
                                        doc.header = {
                                            text: 'صنف مكونات',
                                            fontSize: 16,
                                            bold: true,
                                            alignment: 'center',
                                            margin: [0, 20, 0,
                                                20
                                            ], // Top, right, bottom, left
                                        };

                                        // Add a footer with page numbers
                                        doc.footer = function(currentPage,
                                            pageCount) {
                                            return {
                                                text: 'Page ' +
                                                    currentPage +
                                                    ' of ' + pageCount,
                                                alignment: 'center',
                                                fontSize: 10,
                                                margin: [0, 10, 0,
                                                    10
                                                ], // Top, right, bottom, left
                                            };
                                        };

                                        // Adjust page margins for more width
                                        doc.pageMargins = [40, 60, 40,
                                            60
                                        ]; // left, top, right, bottom

                                        // Adjust table styles
                                        const table = doc.content[1].table;

                                        // Reverse column order in table body
                                        table.body.forEach(row => {
                                            if (row.length) {
                                                row.reverse();
                                            }
                                        });

                                        // Reverse column widths if they are defined
                                        if (table.widths) {
                                            table.widths.reverse();
                                        }

                                        // Apply general row styles
                                        table.body.forEach(row => {
                                            if (row.length) {
                                                row.forEach(
                                                    cell => {
                                                        if (cell
                                                            .text
                                                        ) {
                                                            cell.fontSize =
                                                                10;
                                                            cell.alignment =
                                                                'right';
                                                            cell.border = [
                                                                false,
                                                                false,
                                                                false,
                                                                true
                                                            ]; // Bottom border for each cell

                                                        }
                                                    });
                                            }
                                        });

                                        // Adjust column widths
                                        table.widths = [50, '*', '*',
                                            '*'
                                        ]; // Define widths for each column
                                    }
                                },

                                "print",
                            ],
                        });
                        reportModal.modal('show')
                        reportModal.find('#labelModel').text(title)
                    }
                },
            });
        });
        /*  ======================== End printDetails ============================== */

        /*  ======================== Start Change Component ============================== */
        $(document).on('dblclick', '.table-materials tbody tr', function() {
            let code = $(this).attr('id');
            mainGroup.val('all').trigger('change')
            setTimeout(() => {
                materials.val(code).trigger('change');
                materials.select2('close');
                setTimeout(() => {
                    unitInput.focus();
                }, 0)
            }, 300);

        });
        /*  ======================== End Change Component ============================== */


        /*
        ===============================================
        ============ Start Component Modal ============
        ===============================================
        */
        let fromBranch = $('#fromBranch');
        let fromItems = $('#fromItems');
        let toBranch = $('#toBranch');
        let toItems = $('#toItems');
        let fromList = $('.fromComponents');
        let toList = $('.toComponents');
        let fromDetailsItems = $('#fromDetailsItems');
        let toDetailsItems = $('#toDetailsItems');


        function getComponents(itemsVal, branchVal, list, to) {
            $.ajax({
                url: "{{ route('getMaterialsInDetails') }}",
                method: 'post',
                data: {
                    _token,
                    item: itemsVal,
                    branch: branchVal
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '';
                        if (data.materials) {
                            data.materials.materials.forEach((material) => {
                                html += `<li class="material_${material.material_id}">
                <span>${material.material_id}</span>
                <span>${material.material_name}</span>
                            <span style="margin-left:5px;">
                <input class="unit" data-unit=${material.unit} value="${unitToArabic[material.unit]}" />
                </span>
                <span><input type="number" value="${material.quantity}" class="qty" ${to ? '' : 'readonly'} /></span>
                <span class="${to ? 'cost' : 'cost d-none'}">${material.cost}</span>
                <input type="hidden" value="${material.cost / material.quantity}"/>`
                                if (to) {
                                    html +=
                                        `<button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>`
                                }
                                html += `</li>`;
                            });
                        }
                        list.html(html)
                    }
                },
            });
        }
        /*  ============= Start Get Items From ============= */
        fromBranch.on('change', function() {
            getItems($(this).val(), fromItems, materials)
            setTimeout(() => {
                fromItems.select2({
                    dir: 'rtl',
                    dropdownParent: $('#transferModal')
                })
                fromItems.select2('open')
            }, 500);
        });
        /*  ============== End Get Items From ============== */
        /*  ============== Start Get Material ============== */
        fromItems.on('change', function() {
            $.ajax({
                url: "{{ route('getDetails') }}",
                method: 'post',
                data: {
                    _token,
                    branch: fromBranch.val(),
                    item: fromItems.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '<option value="" disabled selected></option>';
                        data.data.forEach((item) => {
                            html +=
                                `<option value="${item.id}" data-price="${item.price}" >${item.name}</option>`
                        });
                        fromDetailsItems.html(html)
                        fromDetailsItems.select2({
                            dir: "rtl",
                            dropdownParent: $('#transferModal')
                        });
                        fromDetailsItems.select2('open');
                    }
                },
            });

            // let price = $(this).find('option:selected').attr('data-price');
            // getComponents($(this).val(), fromBranch.val(), fromList, false)
        });
        /*  ============== End Get Material ============== */
        /*  ============== Start Get Material Details ============== */
        fromDetailsItems.on('change', function() {
            $.ajax({
                url: "{{ route('getMaterialsInDetails') }}",
                method: 'post',
                data: {
                    _token,
                    branch: fromBranch.val(),
                    item: fromItems.val(),
                    details: fromDetailsItems.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '';
                        if (data.materials) {
                            data.materials.materials.forEach((material) => {
                                html += `<li class="material_${material.material_id}">
                            <span style="margin-left:15px;">${material.material_id}</span>
                            <span >${material.material_name}</span>
                             <span style="margin-left:5px;">
                                <input class="unit" data-unit=${material.unit} value="${unitToArabic[material.unit]}" />
                               </span>
                            <span><input type="number" value="${material.quantity}" class="qty" readonly /></span>
                            <span class="cost d-none">${material.cost}</span>
                            <input type="hidden" value="${material.cost / material.quantity}"/>`
                                html += `</li>`;
                            });
                        }
                        fromList.html(html)
                    }
                },
            });

            // let price = $(this).find('option:selected').attr('data-price');
            // getComponents($(this).val(), fromBranch.val(), fromList, false)
        });
        /*  ============== End Get Material Details ============== */
        /*  ============= Start Get Items To ============= */
        toBranch.on('change', function() {
            getItems($(this).val(), toItems, materials)
            setTimeout(() => {
                toItems.select2({
                    dir: 'rtl',
                    dropdownParent: $('#transferModal')
                })
                toItems.select2('open')
            }, 500);
        });
        /*  ============== End Get Items To ============== */
        /*  ============== Start Get Material ============== */
        toItems.on('change', function() {
            $.ajax({
                url: "{{ route('getDetails') }}",
                method: 'post',
                data: {
                    _token,
                    branch: toBranch.val(),
                    item: toItems.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '<option value="" disabled selected></option>';
                        data.data.forEach((item) => {
                            html +=
                                `<option value="${item.id}" data-price="${item.price}" >${item.name}</option>`
                        });
                        toDetailsItems.html(html)
                        toDetailsItems.select2({
                            dir: "rtl",
                            dropdownParent: $('#transferModal')
                        });
                        toDetailsItems.select2('open');
                    }
                },
            });


            // let price = $(this).find('option:selected').attr('data-price');
            // getComponents($(this).val(), toBranch.val(), toList, true)
            // fromList.find('li').each(function () {
            //     $(this).removeAttr("disabled");
            // });
        });
        /*  ============== End Get Material ============== */
        /*  ============== Start Get Material Details ============== */
        toDetailsItems.on('change', function() {
            $.ajax({
                url: "{{ route('getMaterialsInDetails') }}",
                method: 'post',
                data: {
                    _token,
                    branch: toBranch.val(),
                    item: toItems.val(),
                    details: toDetailsItems.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = '';
                        if (data.materials) {
                            data.materials.materials.forEach((material) => {
                                html +=
                                    `<li class="material_${material.material_id}">
                            <span>${material.material_id}</span>
                            <span>${material.material_name}</span>
                                                        <span style="margin-left:5px;">
                                <input class="unit" data-unit=${material.unit} value="${unitToArabic[material.unit]}" />
                               </span>
                            <span><input type="number" value="${material.quantity}" class="qty" /></span>
                            <span class="cost">${material.cost}</span>
                            <input type="hidden" value="${material.cost / material.quantity}"/>
                            <button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>`
                                html += `</li>`;
                            });
                        }
                        toList.html(html)
                    }
                },
            });
            fromList.find('li').each(function() {
                $(this).removeAttr("disabled");
            });
        });

        /*  ============== End Get Material ============== */
        /*  ============== Start Move Components ============== */
        $(document).on('click', '.fromComponents li', function() {
            let component = $(this).clone(true, true);
            component.append($(
                '<button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>'
            ));
            component.find('span.cost').removeClass('d-none')
            component.find('.qty').removeAttr('readonly')
            if (toItems.val()) {
                if (toList.find(`.${component.attr('class')}`).length == 0) {
                    toList.append(component)
                    $(this).attr('disabled', 'disabled')
                } else {
                    $(this).attr('disabled', 'disabled')
                }
            }
        });

        $('.transAll').on('click', function() {
            if (toItems.val() && fromItems.val()) {
                fromList.find('li').each(function() {
                    if (toList.find(`.${$(this).attr('class')}`).length == 0) {
                        let component = $(this).clone(true, true);
                        component.append($(
                            '<button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>'
                        ));
                        component.find('span.cost').removeClass('d-none');
                        component.find('.qty').removeAttr('readonly')
                        toList.append(component);
                        $(this).attr('disabled', 'disabled')
                    }
                });
            }
        });
        /*  ============== End Move Components ============== */
        /*  ============== Start Delete Components ============== */
        $(document).on('click', '.delete_to_Component', function() {
            let component = $(this).parents('li');
            fromList.find(`.${component.attr('class')}`).removeAttr("disabled");
            component.remove()
        });
        /*  ============== End Delete Components ============== */
        /*  ============== Start Change Qty Components ============== */
        $(document).on('input', '.toComponents .qty', function() {
            let parent = $(this).parents('li')
            let qtyUnit = parent.find('input[type="hidden"]').val();
            let cost = parent.find('span.cost')

            cost.text((+qtyUnit * +$(this).val()).toFixed(2))
        });
        /*  ============== End Change Qty Components ============== */
        /*  ============== Start Save Transfer ============== */
        $('#save_transfer').on('click', function() {

            let item_id = toItems.find('option:selected').attr('value');
            let branch = toBranch.find('option:selected').attr('value');
            let details = toDetailsItems.val();
            let componentsArray = [];
            toList.find('li').each(function() {
                let material_id = $(this).find('span').first().text()
                let material_name = $(this).find('span').eq(1).text()
                let quantity = $(this).find('.qty').val()
                let cost = $(this).find('.cost').text()
                let unit = $(this).find('.unit').attr('data-unit')
                componentsArray.push({
                    material_id,
                    material_name,
                    quantity,
                    branch,
                    item_id,
                    details,
                    cost,
                    unit
                })
            });

            let dataToSend = {
                details: details,
                materials: componentsArray
            };
            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);


            $.ajax({
                type: 'POST',
                url: "{{ route('transfierMaterialDetails') }}",
                dataType: 'json',
                data: dataToSend,
                success: function(data) {
                    console.log(data);
                    if (data.status == true) {
                        $('#transferModal').modal('hide')
                        fromBranch.val(null).trigger("change");
                        fromItems.val(null).trigger("change");
                        toBranch.val(null).trigger("change");
                        toItems.val(null).trigger("change");
                        Toast.fire({
                            icon: 'success',
                            title: data.data
                        });
                    }
                },
                error: handleAjaxError,
                complete: function() {
                    button.html(originalHtml).prop('disabled', false);
                }
            });

        });
        /*  ============== End Save Transfer ============== */
        /*
        ===============================================
        ============ Start Component Modal ============
        ===============================================
        */

        $('#transferModal').on('shown.bs.modal', function() {
            // Reset the "From" branch and items
            $('#fromBranch').val(null).trigger('change');
            $('#fromItems').empty(); // Clear the options
            $('#fromDetailsItems').empty(); // Clear the options

            // Clear the "From" components list
            $('.fromComponents').empty();

            // Reset the "To" branch and items
            $('#toBranch').val(null).trigger('change');
            $('#toItems').empty(); // Clear the options
            $('#toDetailsItems').empty(); // Clear the options

            // Clear the "To" components list
            $('.toComponents').empty();

            fromItems.select2('open')
        });

        let isSelect2Open = false;

        $('#toDetailsItems').on('select2:opening', function() {
            isSelect2Open = true;
        });

        $('#toDetailsItems').on('select2:closing', function() {
            isSelect2Open = false;
        });

        // Prevent the modal from closing if Select2 is open
        $('#transferModal').on('hide.bs.modal', function(e) {
            if (isSelect2Open) {
                e.preventDefault(); // Prevent the modal from closing
            }
        });
    });
</script>

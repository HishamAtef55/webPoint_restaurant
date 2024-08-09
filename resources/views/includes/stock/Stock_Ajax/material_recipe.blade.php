@include('includes.stock.Stock_Ajax.public_function')
<script>
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
    $(document).ready(function() {
        let spinner = $(
            '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
        );
        let productQty = $('#storeMaterialRecipe').find('input[name="product_qty"]');
        let materialPriceEle = $('#storeMaterialRecipe').find('input[name="material_price"]');
        let branchEle = $('#storeMaterialRecipe').find('select[name="branch_id"]');
        let manfacturedMaterialSelectEle = $('#storeMaterialRecipe').find(
            'select[name="manufactured_material_id"]');
        const unitToArabic = {
            'ml': 'مللى',
            'gm': 'جرام',
            'number': 'عدد'
            // Add other units as needed
        };
        let materials = $('#storeMaterialRecipe').find('select[name="material_id"]');
        let unitInput = $('#storeMaterialRecipe').find('input[name="unit"]');
        let unitLabel = $('#storeMaterialRecipe').find('span[id="unit_label"]');
        let unitPriceInput = $('#storeMaterialRecipe').find('input[name="unit_price"]');
        let materialArray = [];
        let materialTable = $('.table-materials');
        let totalPriceInput = materialTable.find('tfoot .total-price');
        let percentageInput = materialTable.find('tfoot .percentage');
        let saveComponent = $('#storeMaterialRecipe').find('button[id="save_material_recipe"]');
        let printComponentsModal = $('#print_components_model');
        let reportModal = $('#reportModal');
        let printSingleComponentModel = $('#print_component');
        // get materials
        branchEle.on('change', function() {
            let selectedValue = $(this).val();
            if (!selectedValue) return;
            let slectedMatrialRecipeEle = $(
                '#storeMaterialRecipe select[name="manufactured_material_id"]')

            const url = '{{ url('stock/material/recipe') }}/' + selectedValue + '/filter';
            let tableBody = $('.table-materials tbody')
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        tableBody.html(
                            '<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>'
                        );
                        $('.percentage').val(0)
                        $('.total-price').val(0)
                        productQty.val(1)
                        materialPriceEle.val(0)
                        let html = '<option value="" disabled selected></option>';

                        slectedMatrialRecipeEle.html('');
                        const materials = response.data;

                        if (!materials.length) {
                            slectedMatrialRecipeEle.append(
                                '<option value="">لاتوجد خامات</option>');
                            materialPriceEle.val(0)
                            return;
                        }
                        materials.forEach((item) => {
                            html +=
                                `<option value="${item.id}" data-price="${item.price}" >${item.name}</option>`
                        });
                        manfacturedMaterialSelectEle.html(html)
                        manfacturedMaterialSelectEle.select2({
                            dir: "rtl"
                        });

                        manfacturedMaterialSelectEle.select2('open');
                    }

                },
                error: handleAjaxError,
            })
        });

        // get materials details
        manfacturedMaterialSelectEle.on('change', function() {
            let selectedValue = $(this).val();
            if (!selectedValue) return;
            let price = $(this).find('option:selected').attr('data-price');
            let tableBody = $('.table-materials tbody');
            materialArray = [];

            $.ajax({
                type: "GET",
                url: '{{ url('stock/material/recipe/filter') }}/' + selectedValue,
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    if (response.status === 200) {
                        productQty.focus().select();
                        let html = '';
                        let count = 1;
                        tableBody.html('');
                        if (!response.data.length) {
                            tableBody.html(
                                '<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>'
                            );
                            $('.percentage').val(0)
                            $('.total-price').val(0)
                            return;
                        }
                        response.data.forEach((material) => {
                            html += `<tr id="${material.material_recipe_id}">
                                <td>${material.material_recipe_id}</td>
                                <td>${material.material_recipe_name}</td>
                                <td class="tr-qty">${material.quantity}</td>
                                <td>${unitToArabic[material.unit]}</td>
                                <td class="tr-price">${material.price}</td>
                                <td> <button class="btn btn-danger delete_Component" data-id="${material.id}"><i class="fa-regular fa-trash-can"></i></button> </td>
                            </tr>`;
                            materialArray.push({
                                code: material.material_recipe_id,
                                quantity: material.quantity,
                                price: material.price,
                                unit: material.unit
                            });
                        });
                        // $('.percentage').val(data.materials.percentage)
                        productQty.val(response.component_qty)
                        totalPriceInput.val(response.total_price)
                        tableBody.append($(html));
                        calcPricePercent()

                    }
                }
            });
        });

        productQty.on('keyup', function(e) {
            calcPricePercent()
            if (e.keyCode === 13) {
                materials.select2('open');
            }
        });

        materials.on('change', function() {
            let cost = $(this).find('option:selected').attr('data-cost');
            let unitName = $(this).find('option:selected').attr('data-unit');

            unitLabel.text(unitName);
            unitInput.val('');
            unitPriceInput.val('');
            setTimeout(() => {
                unitInput.focus();
            }, 100);

        });

        function calcPricePercent() {
            let totalPrice = 0;
            materialTable.find('td.tr-price').each(function() {
                totalPrice += parseFloat($(this).text());
            });
            totalPriceInput.val(totalPrice.toFixed(2));
            materialPrice = totalPriceInput.val() / productQty.val()
            materialPriceEle.val(materialPrice.toFixed(2))
            // let percentage = (totalPrice / itemPrice.val()) * 100;
            // percentageInput.val(percentage.toFixed(2));
        }
        unitInput.on('keyup', function(e) {
            let cost = materials.find('option:selected').attr('data-price') / 100;
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
                    if (!manfacturedMaterialSelectEle.val()) {
                        Toast.fire({
                            icon: 'error',
                            title: 'يجب اختيار خامة'
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
                            quantity: qty,
                            price: unitPriceInput.val(),
                            unit: unit
                        });
                        let html = `<tr id="${materialCode}">
  
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

        // store new component

        saveComponent.on('click', function() {
            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner);
            button.prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: "{{ route('stock.material.recipe.store') }}",
                dataType: 'json',
                data: {
                    material_id: manfacturedMaterialSelectEle.val(),
                    components: materialArray,
                    component_qty: productQty.val()
                },
                success: function(response) {

                    if (response.status == 201) {
                        materials.val(null);
                        calcPricePercent();
                        successMsg(response.message);

                        $('#product_qty').val(1)
                        materialArray = []
                        setTimeout(() => {
                            manfacturedMaterialSelectEle.val(
                                manfacturedMaterialSelectEle).select2('open');
                        }, 300);
                    }
                },
                error: handleAjaxError,
                complete: function(response) {
                    button.html(originalHtml).prop('disabled', false);

                }
            });
        });

        // delete material component
        $(document).on('click', '.delete_Component', function() {
            const id = $(this).data('id');
            let rowParent = $(this).parents('tr');
            let tableBody = $('.table-materials tbody');
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
                    if (tableBody.find('tr').length === 0) {
                        tableBody.html(
                            '<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>'
                        );
                    }
                    calcPricePercent();
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url('stock/material/recipe', '') }}' + '/' + id,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 200) {
                                materialArray = materialArray.filter(material =>
                                    material.code != rowParent.attr('id'))
                                successMsg(response.message);
                            }
                        },
                    });
                }
            })
        });

        /*  ======================== Start Change Component ============================== */
        $(document).on('dblclick', '.table-materials tbody tr', function() {
            let code = $(this).attr('id');
            // mainGroup.val('all').trigger('change')
            setTimeout(() => {
                materials.val(code).trigger('change');
                materials.select2('close');
                setTimeout(() => {
                    unitInput.focus();
                }, 0)
            }, 300);

        });
        /*  ======================== End Change Component ============================== */

        printComponentsModal.on('click', function() {
            let title = $(this).text();
            $.ajax({
                type: "POST",
                url: "{{ route('stock.material.recipe.filter') }}",
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {

                        let html = `<table class="report_table table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الكمية</th> 
                                    <th>الوحدة</th> 
                                    <th>السعر</th> 
                                </tr>
                            </thead>
                            <tbody>`;
                        response.data.forEach(item => {
                            html += `<tr>
                                <td>${item.id}</td>
                                <td>${item.material_recipe_name}</td>
                                <td>${item.quantity}</td>
                                <td>${unitToArabic[item.unit]}</td>
                                <td>${item.display_price}</td>
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
                                            text: 'المكونات طباعة ', // Arabic text
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
                                            '*', '*'
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

        printSingleComponentModel.on('click', function() {
            let title = $(this).text();
            let selectedValue = manfacturedMaterialSelectEle.val();
            if (!selectedValue) {
                Toast.fire({
                    icon: 'error',
                    title: "برجاء اختيار الخامة",
                });
                return;
            }
            const url = '{{ url('stock/material/recipe/filter') }}';
            const initialParams = {
                "material_id": selectedValue,
            };
            const queryString = $.param(initialParams);


            $.ajax({
                type: "POST",
                url: `${url}?${queryString}`,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {

                        let html = `<table class="report_table table table-striped w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الكمية</th> 
                                    <th>الوحدة</th> 
                                    <th>السعر</th> 
                                </tr>
                            </thead>
                            <tbody>`;
                        response.data.forEach(item => {
                            html += `<tr>
                                <td>${item.id}</td>
                                <td>${item.material_recipe_name}</td>
                                <td>${item.quantity}</td>
                                <td>${unitToArabic[item.unit]}</td>
                                <td>${item.display_price}</td>
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
                                            text: 'المكونات طباعة ', // Arabic text
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
                                            '*', '*'
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
        /*
        ===============================================
        ============ Start Component Modal ============
        ===============================================
        */
        let fromBranch = $('#from_branch_id');
        let fromMaterial = $('#from_material_id');
        let toBranch = $('#to_branch_id');
        let toMaterial = $('#to_material_id');
        let fromList = $('.fromComponents');
        let toList = $('.toComponents');

        /*  ============= Start Get Items From ============= */
        fromBranch.on('change', function() {
            let selectedValue = $(this).val();
            if (!selectedValue) return;

            const url = '{{ url('stock/material/filter') }}/' + selectedValue;

            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        console.log(response)
                        let html = '<option value="" disabled selected></option>';

                        fromMaterial.html('');
                        $('.fromComponents').empty();
                        const materials = response.data;

                        if (!materials.length) {
                            fromMaterial.append(
                                '<option value="">لاتوجد خامات</option>');
                            return;
                        }
                        materials.forEach((item) => {
                            html +=
                                `<option value="${item.id}">${item.name}</option>`
                        });
                        fromMaterial.html(html)
                        fromMaterial.select2({
                            dir: "rtl"
                        });

                        fromMaterial.select2('open');
                    }

                },
                error: handleAjaxError,
            })


        });
        /*  ============== End Get Material ============== */
        /*  ============== Start Get Material Details ============== */
        fromMaterial.on('change', function() {

            let selectedValue = fromMaterial.val();
            if (!selectedValue) return;

            const url = '{{ url('stock/material/recipe/filter') }}';
            const initialParams = {
                "material_id": selectedValue,
            };
            const queryString = $.param(initialParams);


            $.ajax({
                type: "POST",
                url: `${url}?${queryString}`,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        let html = '';
                        if (response.data) {
                            response.data.forEach((material) => {
                                html += `<li class="material_${material.material_recipe_id}">
                                    <span style="margin-left:15px;">${material.material_recipe_id}</span>
                                    <span>${material.material_recipe_name}</span>
                                    <span style="margin-left:5px;">
                                        <input class="unit" data-unit=${material.unit} value="${unitToArabic[material.unit]}" />
                                    </span>
                                    <span><input type="number" value="${material.quantity}" class="qty" readonly /></span>
                                    <span class="price d-none">${material.price}</span>
                                </li>`;
                            });
                            html += `<input type="hidden" class="component_qty" value="${response.material_component.qty}" />`;
                        }
                        fromList.html(html)
                    }
                },
                error: handleAjaxError,
            });

        });
        /*  ============== End Get Material Details ============== */
        /*  ============= Start Get Items To ============= */
        toBranch.on('change', function() {
            let selectedValue = $(this).val();
            if (!selectedValue) return;

            const url = '{{ url('stock/material') }}/' + selectedValue + '/filter';

            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        console.log(response)
                        let html = '<option value="" disabled selected></option>';

                        toMaterial.html('');
                        $('.toComponents').empty();
                        const materials = response.data;

                        if (!materials.length) {
                            toMaterial.append(
                                '<option value="">لاتوجد خامات</option>');
                            return;
                        }
                        materials.forEach((item) => {
                            html +=
                                `<option value="${item.id}">${item.name}</option>`
                        });
                        toMaterial.html(html)
                        toMaterial.select2({
                            dir: "rtl"
                        });

                        toMaterial.select2('open');
                    }

                },
                error: handleAjaxError,
            })

        });

        // toMaterial.on('change', function() {
        //     $.ajax({
        //         url: "{{ route('getMaterialsInDetails') }}",
        //         method: 'post',
        //         data: {
        //             _token,
        //             branch: toBranch.val(),
        //             item: toItems.val(),
        //             details: toDetailsItems.val()
        //         },
        //         success: function(data) {
        //             if (data.status == true) {
        //                 let html = '';
        //                 if (data.materials) {
        //                     data.materials.materials.forEach((material) => {
        //                         html +=
        //                             `<li class="material_${material.material_id}">
        //                     <span>${material.material_id}</span>

        //                     <span>${material.material_name}</span>
        //                                                 <span style="margin-left:5px;">
        //                         <input class="unit" data-unit=${material.unit} value="${unitToArabic[material.unit]}" />
        //                        </span>
        //                     <span><input type="number" value="${material.quantity}" class="qty" /></span>
        //                     <span class="cost">${material.cost}</span>
        //                     <input type="hidden" value="${material.cost / material.quantity}"/>
        //                     <button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>`
        //                         html += `</li>`;
        //                     });
        //                 }
        //                 toList.html(html)
        //             }
        //         },
        //     });
        //     fromList.find('li').each(function() {
        //         $(this).removeAttr("disabled");
        //     });
        // });

        /*  ============== End Get Material ============== */
        /*  ============== Start Move Components ============== */
        $(document).on('click', '.fromComponents li', function() {
            let component = $(this).clone(true, true);
            component.append($(
                '<button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>'
            ));
            component.find('span.cost').removeClass('d-none')
            component.find('.qty').removeAttr('readonly')
            if (toMaterial.val()) {
                if (toList.find(`.${component.attr('class')}`).length == 0) {
                    toList.append(component)
                    $(this).attr('disabled', 'disabled')
                } else {
                    $(this).attr('disabled', 'disabled')
                }
            }
        });

        $('.transAll').on('click', function() {
            if (fromMaterial.val() && toMaterial.val()) {
                fromList.find('li').each(function() {
                    if (toList.find(`.${$(this).attr('class')}`).length == 0) {
                        let component = $(this).clone(true, true);
                        component.append($(
                            '<button class="btn btn-danger delete_to_Component"><i class="fa-regular fa-trash-can"></i></button>'
                        ));
                        component.find('span.price').removeClass('d-none');
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
            let price = parent.find('span.price')

            price.text((+qtyUnit * +$(this).val()).toFixed(2))
        });
        /*  ============== End Change Qty Components ============== */
        /*  ============== Start Save Transfer ============== */
        $('#save_transfer').on('click', function() {

            let branch = toBranch.find('option:selected').attr('value');
            let to_manfuactured_material_id = toMaterial.val();
            let componentsArray = [];
            toList.find('li').each(function() {
                let code = $(this).find('span').first().text()
                let quantity = $(this).find('.qty').val()
                let price = $(this).find('.price').text()
                let unit = $(this).find('.unit').attr('data-unit')
                componentsArray.push({
                    code,
                    quantity,
                    price,
                    unit
                })
            });

            let dataToSend = {
                material_id: to_manfuactured_material_id,
                components: componentsArray,
                component_qty:$('.component_qty').val()
            };

            let button = $(this);
            let originalHtml = button.html();
            button.html(spinner).prop('disabled', true);


            $.ajax({
                type: 'POST',
                url: "{{ route('stock.material.recipe.repeat') }}",
                dataType: 'json',
                data: dataToSend,
                success: function(data) {
                    if (data.status == 200) {
                        $('#transferModal').modal('hide')
                        fromBranch.val(null).trigger("change");
                        fromMaterial.val(null).trigger("change");
                        toBranch.val(null).trigger("change");
                        toMaterial.val(null).trigger("change");
                        successMsg(data.message)
                    }
                },
                error: handleAjaxError,
                complete: function() {
                    button.html(originalHtml).prop('disabled', false);
                }
            });

        });


        /*  ============== ============== */

        $('#transferModal').on('shown.bs.modal', function() {
            // Reset the "From" branch and items
            fromBranch.val(null).trigger('change');
            fromMaterial.empty(); // Clear the options

            // Clear the "From" components list
            $('.fromComponents').empty();

            // Reset the "To" branch and items
            toBranch.val(null).trigger('change');
            toMaterial.empty(); // Clear the options

            // Clear the "To" components list
            $('.toComponents').empty();

        });

        let isSelect2Open = false;

        toMaterial.on('select2:opening', function() {
            isSelect2Open = true;
        });

        toMaterial.on('select2:closing', function() {
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

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
        console.log(printComponentsModal)
        let reportModal = $('#reportModal');
        // get materials
        branchEle.on('change', function() {
            let selectedValue = $(this).val();
            if (!selectedValue) return;
            let slectedMatrialRecipeEle = $(
                '#storeMaterialRecipe select[name="manufactured_material_id"]')

            const url = '{{ url('stock/material/recipe') }}/' + selectedValue + '/filter';

            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
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
                    if (response.status === 200) {
                        materialPriceEle.val(price)
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
                        totalPriceInput.val(response.total_price)
                        tableBody.append($(html));

                    }
                }
            });
        });

        productQty.on('keyup', function(e) {
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
            console.log(code)
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
                    console.log('response staus id', response.status)
                    if (response.status == 200) {
                        console.log(response.data)
                        console.log(response.status)

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
    });
</script>

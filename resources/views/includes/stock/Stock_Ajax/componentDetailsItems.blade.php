@include('includes.stock.Stock_Ajax.public_function')
<script>
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




        /*  ======================== Start All Functions ============================== */
        function getItems(branchVal, itemsDiv, materialDiv) {
            $.ajax({
                url: "{{ route('getItemDetails') }}",
                method: 'post',
                data: {
                    _token,
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
                            console.log(material)
                            materialHtml +=
                                `<option value="${material.id}"
                                 data-serial="${material.serial_nr}"
                                 data-cost="${material.cost}"
                                 data-unit="${material.unit.sub_unit.name_ar}"
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
            let tableBody = $('.table-materials tbody');
            materialArray = [];
            console.log($(this).val())
            if ($(this).val()) {
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
                            itemPrice.val(price);
                            productQty.focus().select();
                            let html = '';
                            let count = 1;
                            tableBody.html(
                                '<tr class="not-found"> <td colspan="6">لا يوجد بيانات</td></tr>'
                                );
                            $('.percentage').val(0);
                            $('.total-price').val(0);
                            getRowsNumber();
                            materialArray = [];
                            if (data.materials) {
                                data.materials.materials.forEach((material) => {
                                    html += `<tr id="${material.material_id}">
                                <td>${count}</td>
                                <td>${material.material_id}</td>
                                <td>${material.material_name}</td>
                                <td class="tr-qty">${material.quantity}</td>
                                <td class="tr-price">${material.cost}</td>
                                <td> <button class="btn btn-danger delete_Component"><i class="fa-regular fa-trash-can"></i></button> </td>
                            </tr>`;
                                    materialArray.push({
                                        code: material.material_id,
                                        name: material.material_name,
                                        quantity: material.quantity,
                                        price: material.cost
                                    });
                                    count++
                                });
                                $('.percentage').val(data.materials.percentage)
                                $('.total-price').val(data.materials.cost)
                                $('#product_qty').val(data.materials.quantity)
                                tableBody.append($(html));
                                getRowsNumber()
                            }
                        }
                    },
                });
            } else {
                itemPrice.val('');
                productQty.focus().select();
                tableBody.empty();
                $('.percentage').val(0);
                $('.total-price').val(0);
                getRowsNumber();
                materialArray = [];
            }
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
            let unitName = $(this).find('option:selected').attr('data-unit-name');
            let unitSize = $(this).find('option:selected').attr('data-unit-size');

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
            let unitName = materials.find('option:selected').attr('data-unit-name');
            let unitSize = materials.find('option:selected').attr('data-unit-size');
            let materialCode = materials.find('option:selected').attr('value');
            let materialName = materials.find('option:selected').text();
            let unitPrice = cost / unitSize;
            let qty = $(this).val();
            let tableBody = $('.table-materials tbody');
            let counter = tableBody.find('tr').length;


            if (materials.val()) {
                unitPriceInput.val((qty * unitPrice).toFixed(2))
                if (e.keyCode === 13 && items.val() && $(this).val()) {
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
                            price: unitPriceInput.val()
                        });
                        let html = `<tr id="${materialCode}">
        <td>${counter + 1}</td>
        <td>${materialCode}</td>
        <td>${materialName}</td>
        <td class="tr-qty">${qty}</td>
        <td class="tr-price">${unitPriceInput.val()}</td>
        <td> <button class="btn btn-danger delete_Component"><i class="fa-regular fa-trash-can"></i></button> </td>
    </tr>`
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
                        url: "{{ route('deleteComponent') }}",
                        method: 'post',
                        data: {
                            _token,
                            code: rowParent.attr('id'),
                            branch: branch.val(),
                            items: items.val(),
                            totalPrice: totalPriceInput.val(),
                            percentage: percentageInput.val()
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
                    });
                }
            })


        });
        /*  ======================== End Delete Materials In Table ============================== */
        /*  ======================== Start Save Components Items ============================== */
        saveComponent.on('click', function() {
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
                        items.val(null);
                        $('.table-materials tbody').html('');
                        calcPricePercent();
                        getRowsNumber()
                        $('#product_qty').val(1)

                        Toast.fire({
                            icon: 'success',
                            title: data.data
                        });
                        setTimeout(() => {
                            items.select2('open');
                        }, 300);
                        materialArray = []
                    }
                },
            });
        });
        /*  ======================== End Save Components Items ============================== */
        /*  ======================== Start itemsWithOutMaterials ============================== */
        DetailsWithoutMaterials.on('click', function() {
            let title = $(this).text();
            $.ajax({
                url: "{{ route('DetailsWithoutMaterials') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = `<table class="report_table table-striped table w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data.forEach(item => {
                            html += `<tr class="table-primary fw-bold text-center">
                        <td>${item.id}</td>
                        <td>${item.name}</td>
                        <td>${item.price}</td>
                        <td>color</td>
                    </tr>`
                            if (item.details.length > 0) {
                                item.details.forEach(detail => {
                                    html += `<tr class="">
                                <td>${detail.id}</td>
                                <td>${detail.name}</td>
                                <td>${detail.price}</td>
                                <td>no</td>
                            </tr>`
                                });
                            }
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            ordering: false,
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                {
                                    extend: 'excelHtml5',
                                    title: '',
                                    autoFilter: true,
                                    customize: function(xlsx) {
                                        var sheet = xlsx.xl.worksheets[
                                            'sheet1.xml'];
                                        // Loop over the cells in column `C`
                                        $('row c[r^="D"]', sheet).each(
                                            function() {
                                                // Get the value
                                                if ($('is t', this)
                                                    .text() == 'color'
                                                    ) {
                                                    $(this).attr('s',
                                                            '20')
                                                        .siblings()
                                                        .attr('s',
                                                        '20');
                                                }
                                                $(this).remove()
                                            });
                                    }
                                },
                                {
                                    extend: "pdfHtml5",
                                    download: "open",
                                    autoFilter: true,
                                    title: title.split(' ').reverse().join(
                                        '  '),
                                    exportOptions: {
                                        orthogonal: "PDF",
                                    },
                                    customize: function(doc, s) {
                                        doc.content[1].table.body.forEach(
                                            row => {
                                                if (row[row.length - 1]
                                                    .text === 'color') {
                                                    row.forEach(col =>
                                                        col
                                                        .fillColor =
                                                        '#c5d7f2')
                                                }
                                                row.pop()
                                            })
                                        doc.pageMargins = [10, 5];
                                        doc.content[0].margin = [0, 0]
                                        doc.defaultStyle.font = "Cairo";
                                        doc.defaultStyle.alignment =
                                            "center";
                                        doc.styles.tableHeader.fillColor =
                                            '#159d71';
                                        doc.styles.tableHeader.color =
                                            'white';
                                        doc.styles.tableHeader.fontSize =
                                        12;
                                        doc.defaultStyle.fontSize = 12;
                                        doc.content[1].table.widths = Array(
                                                doc.content[1].table.body[0]
                                                .length + 1).join('*')
                                            .split('');
                                    },
                                },
                                "print",
                            ],
                            columnDefs: [{
                                targets: [-1],
                                visible: false,
                            }, {
                                targets: [1],
                                render: function(data, type, row) {
                                    if (type === 'PDF') {
                                        if (!/[a-z]/.test(data)) {
                                            return data.split(' ')
                                                .reverse().join('  ');
                                        }
                                    }
                                    return data;
                                }
                            }]
                        });
                        reportModal.modal('show')
                        reportModal.find('#labelModel').text(title)
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.data
                        });
                    }
                },
            });
        });
        /*  ======================== End itemsWithOutMaterials ============================== */
        /*  ======================== Start printDetails ============================== */
        printDetails.on('click', function() {
            let title = $(this).text();
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
                    if (data.status == true) {
                        let html = `<table class="report_table table-striped table w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>quantity</th>
                            <th>Cost</th>
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
                            ordering: false,
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                {
                                    extend: 'excelHtml5',
                                    title: '',
                                    autoFilter: true,
                                    customize: function(xlsx) {
                                        var sheet = xlsx.xl.worksheets[
                                            'sheet1.xml'];
                                        // Loop over the cells in column `C`
                                        $('row c[r^="C"]', sheet).each(
                                            function() {
                                                // Get the value
                                                if ($('is t', this)
                                                    .text() == '-') {
                                                    $(this).attr('s',
                                                            '20')
                                                        .siblings()
                                                        .attr('s',
                                                        '20');
                                                }
                                            });
                                        // $(`row`, sheet).children().attr('s', '20');
                                    }
                                },
                                {
                                    extend: "pdfHtml5",
                                    download: "open",
                                    customize: function(doc) {
                                        doc.defaultStyle.font = "Cairo";
                                        doc.styles.tableBodyEven.alignment =
                                            "center";
                                        doc.styles.tableBodyOdd.alignment =
                                            "center";
                                        doc.styles.tableBodyEven
                                            .lineHeight = "1.5";
                                        doc.styles.tableBodyOdd.lineHeight =
                                            "1.5";
                                        doc.styles.tableFooter.alignment =
                                            "center";
                                        doc.styles.tableHeader.alignment =
                                            "center";
                                    },
                                },
                                "print",
                            ],
                        });
                        reportModal.modal('show')
                        reportModal.find('#labelModel').text(title)
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.data
                        });
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
            getItems($(this).val(), fromItems);
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
                <span>${material.material_id}</span>
                <span>${material.material_name}</span>
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
            getItems($(this).val(), toItems);
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
            let details = toDetailsItems.find('option:selected').attr('value');
            let componentsArray = [];
            toList.find('li').each(function() {
                let material_id = $(this).find('span').first().text()
                let material_name = $(this).find('span').eq(1).text()
                let quantity = $(this).find('.qty').val()
                let cost = $(this).find('.cost').text()
                componentsArray.push({
                    branch,
                    item_id,
                    details,
                    material_id,
                    material_name,
                    quantity,
                    cost,
                })
            });

            $.ajax({
                url: "{{ route('transfierMaterialDetails') }}",
                method: 'post',
                data: {
                    _token,
                    materials: componentsArray
                },
                success: function(data) {
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
            });

        });
        /*  ============== End Save Transfer ============== */
        /*
        ===============================================
        ============ Start Component Modal ============
        ===============================================
        */
    });
</script>

@include('includes.stock.Stock_Ajax.public_function')
<script>
    // Common function to handle AJAX errors
    function handleAjaxError(reject) {
        let response = $.parseJSON(reject.responseText);
        $.each(response.errors, function(key, val) {
            errorMsg(val[0]);
        });
    }

    // handle csrf request header

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    })

    $(document).ready(function() {
        let branch = $('#branch');
        let items = $('#items');
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
        let itemWithOutMaterials = $('#itemWithOutMaterials');
        let printComponents = $('#print_components');
        let printItem = $('#print_item');
        let printComponent = $('#print_component');
        let componentWithoutItems = $('#componentWithoutItems');
        let reportModal = $('#reportModal');



        /*  ======================== Start All Functions ============================== */
        function getItems(branchVal, itemsDiv, materialDiv) {
            $.ajax({
                type: "GET",
                url: '{{ url('stock/items') }}/' + branchVal + '/filter',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        let html = '<option value="" disabled selected></option>';
                        let materialHtml = '<option value="" disabled selected></option>';
                        response.data.forEach((item) => {
                            html +=
                                `<option value="${item.id}" data-price="${item.price}" >${item.name}</option>`
                        });
                        response.materials.forEach((material) => {
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
                error: handleAjaxError,
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
        /*  ======================== Start Get Material ============================== */
        items.on('change', function() {
            let price = $(this).find('option:selected').attr('data-price');
            let tableBody = $('.table-materials tbody');
            materialArray = [];
            $.ajax({
                url: "{{ route('components_items_get_material_in_item') }}",
                method: 'post',
                data: {
                    item: items.val(),
                    branch: branch.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        itemPrice.val(price)
                        productQty.focus().select()
                        let html = '';
                        let count = 1;
                        tableBody.html(
                            '<tr class="not-found"> <td colspan="6">لا يوجد بيانات</td></tr>'
                        );
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
            console.log(e);
            let cost = materials.find('option:selected').attr('data-cost');
            let unitName = materials.find('option:selected').attr('data-unit');
            let unitSize = materials.find('option:selected').attr('data-unit-value');
            let materialCode = materials.find('option:selected').attr('data-serial');
            let material_id = materials.find('option:selected').attr('value');
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
                            code: material_id,
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
            console.log(materialArray)
            $.ajax({
                url: "{{ route('saveComponent') }}",
                method: 'post',
                data: {
                    branch: branch.val(),
                    items: items.val(),
                    itemPrice: itemPrice.val(),
                    productQty: productQty.val(),
                    materialArray,
                    totalPrice: totalPriceInput.val(),
                    percentage: percentageInput.val()
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
        itemWithOutMaterials.on('click', function() {
            let title = $(this).text();
            $.ajax({
                url: "{{ route('itemsWithOutMaterials') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = `<table class="report_table table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Cost Price</th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data.forEach(item => {
                            html += `<tr>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td>${item.price}</td>
                            <td>${item.cost_price || 0}</td>
                        </tr>`
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            // scrollCollapse: true,
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
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
        /*  ======================== End itemsWithOutMaterials ============================== */
        /*  ======================== Start componentWithoutItems ============================== */
        componentWithoutItems.on('click', function() {
            let title = $(this).text();
            $.ajax({
                url: "{{ route('componentWithoutItems') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = `<table class="report_table table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data.forEach(component => {
                            html += `<tr>
                            <td>${component.code}</td>
                            <td>${component.material}</td>
                        </tr>`
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            // scrollCollapse: true,
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
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
        /*  ======================== End componentWithoutItems ============================== */
        /*  ======================== Start printComponent ============================== */
        printComponent.on('click', function() {
            let title = $(this).text();
            $.ajax({
                url: "{{ route('printComponent') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                    mainGroup: mainGroup.val(),
                    materials: materials.val(),
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = `<table class="report_table table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>quantity</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data.components.forEach(component => {
                            html += `<tr>
                            <td>${component.item_id}</td>
                            <td>${component.item.name}</td>
                            <td>${component.quantity}</td>
                            <td>${component.cost || 0}</td>
                        </tr>`
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            // scrollCollapse: true,
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 text-center fs-3 material-title-table'><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
                                {
                                    extend: "pdfHtml5",
                                    download: "open",
                                    messageTop: function() {
                                        return `${data.data.name} ( ${data.data.code} ) `
                                    },
                                    customize: function(doc) {
                                        doc.defaultStyle.font = 'Cairo';
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
                        $('.material-title-table').html(
                            `${data.data.name} ( ${data.data.code} ) `)
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
        /*  ======================== End printComponent ============================== */
        /*  ======================== Start printItem ============================== */
        printItem.on('click', function() {
            let title = $(this).text();
            $.ajax({
                url: "{{ route('printItems') }}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                    items: items.val(),
                },
                success: function(data) {
                    if (data.status == true) {
                        console.log(data.data)
                        let html = `<table class="report_table table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>quantity</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data[0].material_components.forEach(component => {
                            html += `<tr>
                            <td>${component.material_id}</td>
                            <td>${component.material_name}</td>
                            <td>${component.quantity}</td>
                            <td>${component.cost || 0}</td>
                        </tr>`
                        });
                        html += `</tbody></table>`;
                        reportModal.find('.report_content').html(html)
                        $(".report_table").DataTable({
                            scrollY: '405px',
                            // scrollCollapse: true,
                            paging: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 text-center fs-3 material-title-table'><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
                                {
                                    extend: "pdfHtml5",
                                    download: "open",
                                    messageTop: function() {
                                        return `${data.data[0].name} ( ${data.data[0].id} ) `
                                    },
                                    customize: function(doc) {
                                        doc.defaultStyle.font = 'Cairo';
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
                        $('.material-title-table').html(
                            `${data.data[0].name} ( ${data.data[0].id} ) `)
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
        /*  ======================== End printItem ============================== */

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

        function getComponents(itemsVal, branchVal, list, to) {
            $.ajax({
                url: "{{ route('components_items_get_material_in_item') }}",
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
            getItems($(this).val(), fromItems, materials);
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
            let price = $(this).find('option:selected').attr('data-price');
            getComponents($(this).val(), fromBranch.val(), fromList, false)
        });
        /*  ============== End Get Material ============== */
        /*  ============= Start Get Items To ============= */
        toBranch.on('change', function() {
            getItems($(this).val(), toItems, materials);
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
            let price = $(this).find('option:selected').attr('data-price');
            getComponents($(this).val(), toBranch.val(), toList, true)
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
            let componentsArray = [];
            toList.find('li').each(function() {
                let material_id = $(this).find('span').first().text()
                let material_name = $(this).find('span').eq(1).text()
                let quantity = $(this).find('.qty').val()
                let cost = $(this).find('.cost').text()
                componentsArray.push({
                    branch,
                    item_id,
                    material_id,
                    material_name,
                    quantity,
                    cost,
                })
            });

            $.ajax({
                url: "{{ route('transferMaterial') }}",
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
        reportModal.on('hidden.bs.modal', event => {
            $(this).find('.report_content').html('');
        })
        /*  ============== Start Close Model ============== */

        /*  ============== End Close Model ============== */


        /*
        ===============================================
        ============ Start Component Modal ============
        ===============================================
        */

        let componentsBranch = $('#components_branch');

        componentsBranch.select2({
            selectOnClose: true,
            dir: "rtl"
        });

        componentsBranch.on('change', function() {
            $.ajax({
                url: "{{ route('stock.sections.groups') }}",
                method: 'post',
                data: {
                    _token,
                    branch: componentsBranch.val(),
                },
                success: function(data) {
                    if (data.status == 'true') {
                        let html = ``
                        data.groups.forEach(group => {
                            html += `<div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${group.id}" id="group_${group.id}"checked>
                        <label class="form-check-label" for="group_${group.id}">${group.name}</label>
                    </div>`;
                        });
                        $('.group-content').html(html)
                    }
                },
            });
        });

        $('#search_component_details').on('click', function() {
            let details = $('#component_details').is(':checked') ? 1 : 0;
            let groups = [];
            let title = 'المكونات'

            $('.group-content').find('input:checked').each(function() {
                groups.push(+$(this).val());
            });

            $.ajax({
                url: "{{ route('printComponents') }}",
                method: 'post',
                data: {
                    _token,
                    branch: componentsBranch.val(),
                    groups,
                    details,
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = `<table class="report_table table w-100">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>quantity</th>
                            <th>Price</th>
                            <th>percentage</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>`;
                        data.data.forEach(item => {
                            if (groups.indexOf(item.group_id) != -1) {
                                html += `<tr>
                            <td>${item.id}</td>
                            <td>${item.name}</td>
                            <td></td>
                            <td>${item.cost_price || 0}</td>
                            <td></td>
                            <td>item</td>
                        </tr>`
                                if (item.custom_materials != null) {
                                    html += `<tr class="table-warning">
                                <td></td>
                                <td>الخامات</td>
                                <td></td>
                                <td>${item.custom_materials.cost}</td>
                                <td>${item.custom_materials.percentage}</td>
                                <td>details components</td>
                            </tr>`
                                    item.custom_materials.materials.forEach(
                                        material => {
                                            html += `<tr class="table-success">
                                    <td>${material.material_id}</td>
                                    <td>${material.material_name}</td>
                                    <td>${material.quantity}</td>
                                    <td>${material.cost}</td>
                                    <td></td>
                                    <td>material</td>
                                </tr>`
                                        });
                                }
                                if (item.details) {
                                    if (item.details.length > 0) {
                                        item.details.forEach(detail => {
                                            html += `<tr class="table-info">
                                        <td>${detail.detail_id}</td>
                                        <td>${detail.details.name}</td>
                                        <td></td>
                                        <td>${detail.price}</td>
                                        <td></td>
                                        <td>detail</td>
                                    </tr>`
                                            if (detail.materials.length ==
                                                1) {
                                                html += `<tr class="table-warning">
                                            <td></td>
                                            <td>الخامات</td>
                                            <td></td>
                                            <td>${detail.materials[0].cost}</td>
                                            <td>${detail.materials[0].percentage}</td>
                                            <td>details components</td>
                                        </tr>`
                                                detail.materials[0]
                                                    .materials.forEach(
                                                        material => {
                                                            html += `<tr class="table-success">
                                                <td>${material.material_id}</td>
                                                <td>${material.material_name}</td>
                                                <td>${material.quantity}</td>
                                                <td>${material.cost}</td>
                                                <td></td>
                                                <td>material</td>
                                            </tr>`
                                                        });
                                            }
                                        });
                                    }
                                }
                            }
                        });
                        html += `</tbody></table>`;
                        $('.details-report').html(html)
                        $(".report_table").DataTable({
                            scrollY: '400px',
                            // scrollCollapse: true,
                            paging: false,
                            ordering: false,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
                            buttons: [
                                "copy",
                                {
                                    extend: 'excelHtml5',
                                    title: title,
                                    autoFilter: true,
                                    customize: function(xlsx) {
                                        var sheet = xlsx.xl.worksheets[
                                            'sheet1.xml'];
                                        // Loop over the cells in column `C`
                                        $('row c[r^="F"]', sheet).each(
                                            function() {
                                                // Get the value
                                                if ($('is t', this)
                                                    .text() ==
                                                    'details components'
                                                ) {
                                                    $(this).attr('s',
                                                            '20')
                                                        .siblings()
                                                        .attr('s', '5');
                                                } else if ($('is t',
                                                        this).text() ==
                                                    'material') {
                                                    $(this).attr('s',
                                                            '20')
                                                        .siblings()
                                                        .attr('s',
                                                            '20');
                                                } else if ($('is t',
                                                        this).text() ==
                                                    'details title') {
                                                    $(this).attr('s',
                                                            '20')
                                                        .siblings()
                                                        .attr('s',
                                                            '10');
                                                } else if ($('is t',
                                                        this).text() ==
                                                    'detail') {
                                                    $(this).attr('s',
                                                            '20')
                                                        .siblings()
                                                        .attr('s',
                                                            '15');
                                                }
                                                $(this).remove()
                                            });
                                    }
                                }, ,
                                {
                                    extend: "pdfHtml5",
                                    // download: "open",
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
                                                    .text ===
                                                    'details components'
                                                ) {
                                                    row.forEach(col =>
                                                        col
                                                        .fillColor =
                                                        '#fff3cd')
                                                } else if (row[row
                                                        .length - 1]
                                                    .text === 'material'
                                                ) {
                                                    row.forEach(col =>
                                                        col
                                                        .fillColor =
                                                        '#d1e7dd')
                                                } else if (row[row
                                                        .length - 1]
                                                    .text ===
                                                    'details title') {
                                                    row.forEach(col => {
                                                        col.fillColor =
                                                            '#212529'
                                                        col.color =
                                                            'white'
                                                    });

                                                } else if (row[row
                                                        .length - 1]
                                                    .text === 'detail'
                                                ) {
                                                    row.forEach(col =>
                                                        col
                                                        .fillColor =
                                                        '#cff4fc')
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
                    }
                },
            });
        });

        /*  ============== End Save Transfer ============== */
        $('#printComponentsModal').on('hidden.bs.modal', event => {
            $(this).find('.details-report').html('')
        })
        /*  ============== Start Close Model ============== */


    });
</script>

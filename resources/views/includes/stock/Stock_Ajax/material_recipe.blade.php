@include('includes.stock.Stock_Ajax.public_function')
<script>
    // handle csrf request header

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    })
    $(document).ready(function() {
        let branch = $('#branch');
        let mainMaterial = $('#main_material');
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
        let fromItems = $('#fromItems');
        let toItems = $('#toItems');



        /*  ======================== Start All Functions ============================== */
        function getItems(branchVal, itemsDiv) {
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
                        itemsDiv.html(html)
                        itemsDiv.select2({
                            dir: "rtl"
                        });
                        itemsDiv.select2('open');
                    }
                },
            });
        }
        /*  ======================== End All Functions ============================== */


        /*  ======================== Start Get Items ============================== */
        mainMaterial.select2({
            selectOnClose: true,
            dir: "rtl"
        });
        mainGroup.select2({
            selectOnClose: true,
            dir: "rtl"
        });
        materials.select2({
            selectOnClose: true,
            dir: "rtl"
        })
        fromItems.select2({
            selectOnClose: true,
            dir: "rtl"
        })
        toItems.select2({
            selectOnClose: true,
            dir: "rtl"
        })
        branch.on('change', function() {
            getItems($(this).val(), items)
        });
        /*  ======================== End Get Items ============================== */
        /*  ======================== Start Get Material ============================== */
        mainMaterial.on('change', function() {
            let price = $(this).find('option:selected').attr('data-price');
            let tableBody = $('.table-materials tbody');
            materialArray = [];
            $.ajax({
                url: "{{ route('getRecipeMaterialInMaterials') }}",
                method: 'post',
                data: {
                    _token,
                    material: mainMaterial.val(),
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
                mainGroup.select2('open');
            }
        });
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
                unitPriceInput.val(parseFloat(qty * unitPrice).toFixed(2))
                if (e.keyCode === 13 && mainMaterial.val() && $(this).val()) {
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
                        url: "{{ route('deleteMaterialRecipe') }}",
                        method: 'post',
                        data: {
                            _token,
                            material: rowParent.attr('id'),
                            code: mainMaterial.val(),
                            totalPrice: totalPriceInput.val(),
                            percentage: percentageInput.val() || 0
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
                url: "{{ route('saveMaterialRecipe') }}",
                method: 'post',
                data: {
                    _token,
                    material: mainMaterial.val(),
                    materialPrice: itemPrice.val(),
                    productQty: productQty.val(),
                    materialArray,
                    totalPrice: totalPriceInput.val(),
                    percentage: percentageInput.val() || 0,
                },
                success: function(data) {
                    if (data.status == true) {
                        materials.val(null);
                        mainGroup.val(null);
                        mainMaterial.val(null);
                        // $('.table-materials tbody').html('');
                        calcPricePercent();
                        getRowsNumber()
                        Toast.fire({
                            icon: 'success',
                            title: data.data
                        });
                        $('#product_qty').val(1)
                        setTimeout(() => {
                            mainMaterial.select2('open');
                        }, 300);
                        materialArray = []
                    }
                },
            });
        });
        /*  ======================== End Save Components Items ============================== */

        /*  ======================== Start printComponents ============================== */
        printComponents.on('click', function() {
            $.ajax({
                url: "{{ route('getMaterialsReports') }}",
                method: 'post',
                data: {
                    _token,
                },
                success: function(data) {
                    if (data.status == true) {
                        reportModal.modal('show')
                    }
                },
            });
        });
        /*  ======================== End printComponents ============================== */
        /*  ======================== Start printComponent ============================== */
        printComponent.on('click', function() {
            let title = $(this).text();
            $.ajax({
                url: "{{ route('getMaterialReports') }}",
                method: 'post',
                data: {
                    _token,
                    material: mainMaterial.val(),
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
        let toBranch = $('#toBranch');
        let fromList = $('.fromComponents');
        let toList = $('.toComponents');


        function getComponents(itemsVal, list, to) {
            $.ajax({
                url: "{{ route('getRecipeMaterialInMaterials') }}",
                method: 'post',
                data: {
                    _token,
                    material: itemsVal,
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
            getItems($(this).val(), fromItems)
        });
        /*  ============== End Get Items From ============== */
        /*  ============== Start Get Material ============== */
        fromItems.on('change', function() {
            let price = $(this).find('option:selected').attr('data-price');
            getComponents($(this).val(), fromList, false)
        });
        /*  ============== End Get Material ============== */
        /*  ============= Start Get Items To ============= */
        toBranch.on('change', function() {
            getItems($(this).val(), toItems);
        });
        /*  ============== End Get Items To ============== */
        /*  ============== Start Get Material ============== */
        toItems.on('change', function() {
            let price = $(this).find('option:selected').attr('data-price');
            getComponents($(this).val(), toList, true)
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
                        // materials.select2({
                        //     dir: "rtl",
                        //     matcher: customMatcher
                        // });
                        materials.select2('open');
                    }
                },
            });
        });
        /*  ======================== End Get Material ============================== */

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
            let componentsArray = [];
            toList.find('li').each(function() {
                let material_id = $(this).find('span').first().text()
                let material_name = $(this).find('span').eq(1).text()
                let quantity = $(this).find('.qty').val()
                let cost = $(this).find('.cost').text()
                componentsArray.push({
                    item_id,
                    material_id,
                    material_name,
                    quantity,
                    cost,
                })
            });

            $.ajax({
                url: "{{ route('transferMaterialRecipe') }}",
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

@include('includes.stock.Stock_Ajax.public_function')
<script>
const operation = $('#operation');
const date = $('#date');
const branch = $('#branch');
const sections = $('#sections');
const components = $('#components');
const priceComp = $('#price_comp');
const quantityComp = $('#quantity_comp');
const newOperation = $('#new_operation');
const mainGroup = $('#main_group');
const material = $('#material');
const unitLabel = $('#unit_label');
const priceMaterial = $('#price_material');
const quantityMaterial = $('#quantity_material');
const tableBody = $('.table-materials tbody');
const saveOperation = $('#save_operation');
const deleteOperation = $('#delete_operation');
const statusCheck = 'static';


$(document).ready(function() {
    $('select').select2({
        selectOnClose: true,
        dir: "rtl"
    });
    /*  ======================== Start Change Branch ============================== */
    branch.on('change',function() {
            $.ajax({
                url: "{{route('changePurchasesBranch')}}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function(data) {
                    let html = '<option value="" disabled selected></option>';
                    data.sections.forEach((section) => {
                        html += `<option value="${section.id}">${section.name}</option>`
                    });
                    sections.html(html)
                    sections.select2('open');
                },
            });
    });
    /*  ======================== End Change Branch ============================== */
    /*  ======================== Start Change Sections ============================== */
    sections.on('change',function() {
        $.ajax({
            url: "{{route('getMaterialsComponents')}}",
            method: 'post',
            data: {
                _token,
                section: sections.val(),
            },
            success: function(data) {
                let html = '<option value="" disabled selected></option>';
                data.materials.forEach((material) => {
                    html += `<option value="${material.code}" code="${material.code}">${material.material}</option>`
                });
                components.html(html);
                components.select2('open');
            },
        });
    });
    /*  ======================== End Change Sections ============================== */

    /*  ======================== Start Change Sections ============================== */
    components.on('change',function (){
        let type = $('input[name="operations_method"]:checked').val()
        $.ajax({
            url: "{{route('getMaterialsOperations')}}",
            method: 'post',
            data: {
                _token,
                material: components.val(),
                branch: branch.val(),
                sections: sections.val(),
            },
            success: function(data) {
                let html = '';
                if (data.status == true && type == 'static') {
                    let sumTotal = 0
                    tableBody.empty()
                    priceComp.val(data.materials.cost)
                    quantityComp.val(data.materials.quantity)
                    data.materials.materials.forEach((material) => {
                        if(material.quantity > material.section_qty) {
                            html += `<tr class="table-danger" id="${material.material_id}">`
                        } else {
                            html += `<tr id="${material.material_id}">`
                        }
                            html += ` <td>${material.material_id}</td>
                            <td>${material.material_name}</td>
                            <td class="tr-price">${material.cost / material.quantity}</td>
                            <td class="tr-qty">${material.quantity}</td>
                            <td class="d-none one-qty">${parseFloat(material.one_qty).toFixed(2)}</td>
                            <td class="section-qty">${material.section_qty.toFixed(2)}</td>
                            <td class="tr-total" colspan='2'>${parseFloat(material.cost).toFixed(2)}</td>
                            <td class="d-none unit-size">${parseFloat(material.unit_size).toFixed(2)}</td>
                        </tr>`;
                        sumTotal += material.cost
                    });
                } else if (type == 'variable') {
                    tableBody.html('');
                } else if (data.status == false) {
                    let msg = `<tr><td colspan="10" class="fs-4">${data.msg}</td></tr>`
                    tableBody.html(msg);
                    priceComp.val(0);
                    quantityComp.val(0);
                }
                checkForm();
                tableBody.append($(html));
                calcPricePercent();
            },
        });
    });
    /*  ======================== End Change Sections ============================== */

    /*  ======================== Start Change Quantity Component ============================== */
    quantityComp.on('input', function() {
        let type = $('input[name="operations_method"]:checked').val()
        let compQty = $(this).val();
        let status = true;

        if(type == 'static') {
            tableBody.find('tr').each(function() {
                let sectionQty = $(this).find('.section-qty').text();
                let onePrice = $(this).find('.tr-price').text();
                let newQty = parseFloat(compQty * +$(this).find('.one-qty').text()).toFixed(2);

                $(this).find('.tr-qty').text(newQty)
                $(this).find('.tr-total').text(parseFloat(newQty * onePrice).toFixed(2));

                // sumTotal += +$(this).find('.tr-total').text()

                if (+newQty > +sectionQty) {
                    status = false
                    $(this).removeClass('table-danger').addClass('table-danger')
                } else {
                    $(this).removeClass('table-danger')
                }

                if(status) {
                    saveOperation.prop('disabled', false)
                } else {
                    saveOperation.prop('disabled', true)
                }
            });
        }
        calcPricePercent()
    });
    /*  ======================== End Change Quantity Component ============================== */

    /*  ======================== Start Change Purchases Method ============================== */
    $('input[name="operations_method"]').on('change', function() {
        let type = $(this).val();
        if (type === 'variable') {
            $('.variable-sec').removeClass('d-none');
            tableBody.html('');
            calcPricePercent();
            quantityComp.val('');
            checkForm();
        } else {
            $('.variable-sec').addClass('d-none')
        }
    });
    /*  ======================== End Change Purchases Method ============================== */

    /*  ======================== Start Get Material ============================== */
    mainGroup.on('change', function() {
        $.ajax({
            url: "{{route('components_items_get_material')}}",
            method: 'post',
            data: {
                _token,
                group:mainGroup.val(),
            },
            success: function(data) {
                if (data.status == true) {
                    let html ='<option value="" disabled selected></option>';
                    data.materials.forEach(material => {
                        let unitName = material.sub_unit.sub_unit.name;
                        let unitSize = material.sub_unit.size;
                        html +=`<option value="${material.code}" data-cost="${material.cost}" data-unit-name="${unitName}" data-unit-size="${unitSize}">${material.name}</option>`
                    });
                    material.html(html);
                    material.select2('open');
                }
            },
        });
    });
    /*  ======================== End Get Material ============================== */

    /*  ======================== Start Material Details ============================== */
    material.on('change', function() {
        let cost = $(this).find('option:selected').attr('data-cost');
        let unitName = $(this).find('option:selected').attr('data-unit-name');
        let unitSize = $(this).find('option:selected').attr('data-unit-size');
        unitLabel.text(unitName);
        setTimeout(() => {
            quantityMaterial.focus();
        }, 100);
        priceMaterial.val('');
    });
    /*  ======================== End Material Details ============================== */

    /*  ======================== Start Add Material In Table ============================== */
    quantityMaterial.on('keyup', function(e) {
        let cost = material.find('option:selected').attr('data-cost');
        let unitName = material.find('option:selected').attr('data-unit-name');
        let unitSize = material.find('option:selected').attr('data-unit-size');
        let materialCode = material.find('option:selected').attr('value');
        let materialName = material.find('option:selected').text();
        let unitPrice = cost / unitSize;
        let qty = $(this).val();
        let status = true;

        if(material.val()) {
            priceMaterial.val(parseFloat(qty * unitPrice).toFixed(2))
            if (e.keyCode === 13 && components.val() && $(this).val()) {

                $.ajax({
                    url: "{{route('getDetailsMaterialsCost')}}",
                    method: 'post',
                    data: {
                        _token,
                        branch:branch.val(),
                        sections:sections.val(),
                        material:material.val(),
                    },
                    success: function(data) {
                        if (data.status == true) {
                            let sectionQty = data.material.qty * data.material.sub_unit.size;

                            if (+qty > sectionQty) {
                                status = false
                            }

                            if (tableBody.find(`tr#${materialCode}`).length > 0) {
                                let tableRow = tableBody.find(`tr#${materialCode}`);
                                if(!status){
                                    tableRow.removeClass('table-danger').addClass('table-danger')
                                } else {
                                    tableRow.removeClass('table-danger')
                                }
                                tableRow.find('.tr-qty').text(qty);
                                tableRow.find('.tr-price').text(priceMaterial.val());
                            } else {
                                let html = `<tr id="${data.material.code}" class="${!status ? 'table-danger' : ''}">
                                    <td>${data.material.code}</td>
                                    <td>${data.material.material}</td>
                                    <td class="tr-price">${data.material.l_price}</td>
                                    <td class="tr-qty">${qty}</td>
                                    <td class="section-qty">${parseFloat(sectionQty).toFixed(2)}</td>
                                    <td class="tr-total">${priceMaterial.val()}</td>
                                    <td class="d-none unit-size">${parseFloat(data.material.sub_unit.size).toFixed(2)}</td>
                                    <td> <button class="btn btn-danger delete_Component"><i class="fa-regular fa-trash-can"></i></button> </td>
                                </tr>`
                                tableBody.append($(html));
                            }

                            saveOperation.prop('disabled', false)

                            tableBody.find('tr').each(function() {
                                if ($(this).hasClass('table-danger')) {
                                    saveOperation.prop('disabled', true)
                                }
                            });


                            priceMaterial.val('')
                            quantityMaterial.val('')
                            material.val(null).trigger("change");
                            $(this).blur();
                            setTimeout(() => {
                                material.select2('open');
                            }, 100);
                        }
                        calcPricePercent();
                    },
                });
            }
            checkForm();
        }
    });
    /*  ======================== End Add Material In Table ============================== */

    /*  ======================== Function Calculate Total Price ============================== */
    function calcPricePercent() {
        let totalPriceInput = tableBody.parent().find('tfoot .sumFinal');
        let totalPrice = 0;
        tableBody.find('td.tr-total').each(function() {
            totalPrice += parseFloat($(this).text());
        });
        totalPriceInput.text(totalPrice.toFixed(2));
        priceComp.val(totalPrice.toFixed(2))
    }
    /*  ======================== Function Calculate Total Price & Percentage ============================== */

    /*  ======================== Start Save Table ============================== */
    function setData() {
        let materialArray = [];
        tableBody.find('tr').each(function() {
            materialArray.push({
                code: $(this).find('td').eq(0).text(),
                itemName: $(this).find('td').eq(1).text(),
                priceUnit: $(this).find('td').eq(2).text(),
                quantity: $(this).find('td').eq(3).text(),
                unit: $(this).find('td').eq(5).text(),
                total: $(this).find('td').eq(6).text(),
                unitSize: $(this).find('td').eq(7).text(),
            });
        })

        let formData = new FormData();
        formData.set("_token", _token);
        formData.set("operation", operation.val());
        formData.set("date", date.val());
        formData.set("branch", branch.val());
        formData.set("sections", sections.val());
        formData.set("components", components.val());
        formData.set("priceComp", priceComp.val());
        formData.set("materialArray", JSON.stringify(materialArray));
        formData.set("sumTotal", $('.sumFinal').text());
        formData.set("quantityComp", quantityComp.val());

        return formData;

    }
    saveOperation.on('click', function() {
        if (quantityComp.val() && quantityComp.val() > 0 ) {
            $.ajax({
                url: "{{route('saveOperations')}}",
                method:'post',
                enctype:"multipart/form-data",
                processData:false,
                cache : false,
                contentType:false,
                'data' : setData(),
                success: function(data) {
                    if (data.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: data.data
                        });
                        $('#operation').val(data.id);
                        tableBody.html('');
                        calcPricePercent();
                        components.select2('open');
                        priceComp.val('');
                        quantityComp.val('');
                        mainGroup.val('');
                        material.val('');
                        unitLabel.val('');
                        priceMaterial.val('');
                        quantityMaterial.val('');
                        checkForm();
                    }
                },
            });
        } else {
            Toast.fire({
                icon: 'error',
                title: 'يجب ادخال المقدار'
            });
            return false
        }
    });
    /*  ======================== End Save Table ============================== */

    /*  ======================== Start New Operation ============================== */
    newOperation.on('click',function () {
        $('#save_operation').removeClass('d-none');
        $('#delete_operation').addClass('d-none');
        $.ajax({
            url: "{{route('getOperationViaOrder')}}",
            method: 'post',
            data: {
                _token,
            },
            success: function(data) {
                $('#operation').val(data.id);
                tableBody.html('');
                calcPricePercent();
                components.select2('open');
                priceComp.val('');
                quantityComp.val('');
                mainGroup.val('');
                material.val('');
                unitLabel.val('');
                priceMaterial.val('');
                quantityMaterial.val('');
                checkForm();
            },
        });
    });
    /*  ======================== End New Operation ============================== */

    /*  ======================== Search Operation Number============================== */
    operation.on('change',function (){
        $.ajax({
            url: "{{route('getOperationViaOrder')}}",
            method: 'post',
            data: {
                _token,
                order: operation.val(),
            },
            success: function(data) {
                let html = '';
                if (data.status == true) {
                    let sumTotal = 0
                    tableBody.empty()
                    priceComp.val(data.materials.price)
                    quantityComp.val(data.materials.qty)
                    data.materials.details.forEach((material) => {
                        if(material.quantity > material.section_qty) {
                            html += `<tr class="table-danger" id="${material.material_id}">`
                        } else {
                            html += `<tr id="${material.code}">`
                        }
                        html += ` <td>${material.code}</td>
                            <td>${material.material}</td>
                            <td class="tr-price">${material.price}</td>
                            <td class="tr-qty">${material.qty}</td>
                            <td>-</td>
                            <td class="tr-total" colspan='2'>${parseFloat(material.total).toFixed(2)}</td>
                        </tr>`;
                        sumTotal += material.cost
                    });
                } else if (data.status == false) {
                    tableBody.empty();
                    priceComp.val(0);
                    quantityComp.val(0);
                }
                checkForm();
                tableBody.append($(html));
                calcPricePercent();
                $('#save_operation').addClass('d-none');
                $('#delete_operation').removeClass('d-none');
            },
        });
    });


});

</script>

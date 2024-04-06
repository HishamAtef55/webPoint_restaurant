<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
const manufacturing = $('#manufacturing');
const date = $('#date');
const branch = $('#branch');
const sections = $('#sections');
const components = $('#component');
const quantityUnit = $('.quantity_unit');
const priceComp = $('#price_comp');
const total = $('#total');
const available = $('#available');
const halk = $('#halk');
const qtyManufacture = $('#qty_manufacture');
const priceManufacture = $('#price_manufacture');
const totalDynamic = $('input[name="total_dynamic"]');
const qtyDynamic = $('input[name="qty_dynamic"]');
const quantityComp = $('#quantity_comp');
const newManufacturing = $('#new_manufacturing');
const mainGroup = $('#main_group');
const material = $('#material');
const unitLabel = $('#unit_label');
const priceMaterial = $('#price_material');
const quantityMaterial = $('#quantity_material');
const totalMaterial = $('#total_material');
const tableBody = $('.table-materials tbody');
const saveManufacturing = $('#save_manufacturing');
const deleteManufacturing = $('#delete_manufacturing');
const statusCheck = 'static';


$(document).ready(function() {
    $('select').select2({
        selectOnClose: true,
        dir: "rtl"
    });
    /*  ======================== Start Change Branch ============================== */
    branch.on('change',function() {
            $.ajax({
                url: "<?php echo e(route('changePurchasesBranch')); ?>",
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
        let type = 'section';
        $.ajax({
            url: "<?php echo e(route('getMaterialsComponents')); ?>",
            method: 'post',
            data: {
                _token,
                section: sections.val(),
            },
            success: function(data) {
                let html = '<option value="" disabled selected></option>';
                data.materials.forEach((material) => {
                    html += `<option value="${material.id}" code="${material.code}">${material.material}</option>`
                });
                components.html(html);
                components.select2('open');
            },
        });

        $.ajax({
            url: "<?php echo e(route('getMaterialsManufacturing')); ?>",
            method: 'post',
            data: {
                _token,
                section: sections.val(),
                type : type
            },
            success: function(data) {
                let html = '<option value="" disabled selected></option>';
                data.materials.forEach((material) => {
                    html += `<option value="${material.code}" data-cost="${material.average}" code="${material.code}" data-unit-name="${material.unit}">${material.material}</option>`
                });
                material.html(html);
            },
        });
    });
    /*  ======================== End Change Sections ============================== */

    /*  ======================== Start Change Sections ============================== */


    /*  ======================== Start Change Items ============================== */
    components.on('change', function() {
        $.ajax({
            url: "<?php echo e(route('changePurchasesUnit')); ?>",
            method: 'post',
            data: {
                _token,
                type: 'section',
                id: components.val()
            },
            success: function(data) {
                quantityUnit.each(function() {
                    $(this).text(data.units[0].name);
                });
                priceComp.val(data.ava);
                available.val(data.qty);
                quantityComp.val('').prop('disabled', false).focus();
                checkForm();
                tableBody.html('');
                priceManufacture.text('');
                qtyManufacture.text('');
            },
        });
    });
    /*  ======================== End Change Items ============================== */

    /*  ======================== Start Change Quantity Component ============================== */
    quantityComp.on('keyup', function(e) {
        if (e.keyCode === 13 ) {
            if(+$(this).val() > +available.val()) {
                $(this).val(available.val())
            }
            let finalTotal = priceComp.val() * $(this).val()
            total.val(parseFloat(finalTotal).toFixed(2));
            checkForm();
            $(this).prop('disabled', true);
            qtyManufacture.text($(this).val());
            halk.val(0).prop('disabled', false).focus();
            priceManufacture.text(total.val());
            totalDynamic.val(total.val());
        }
    });
    /*  ======================== End Change Quantity Component ============================== */

    /*  ======================== Start Change Quantity halk  ============================== */
    halk.on('input', function(e) {
        if (+$(this).val() >= +quantityComp.val()) {
            $(this).val(quantityComp.val() - 1)
        }
        qtyManufacture.text(+quantityComp.val() - +$(this).val())
    });
    /*  ======================== End Change Quantity halk  ============================== */

    /*  ======================== Start Get Material ============================== */
    mainGroup.on('change', function() {
        $.ajax({
            url: "<?php echo e(route('components_items_get_material')); ?>",
            method: 'post',
            data: {
                _token,
                group:mainGroup.val(),
            },
            success: function(data) {
                if (data.status == true) {
                    let html ='<option value="" disabled selected></option>';
                    data.materials.forEach(material => {
                        let unitName = material.unit;
                        html +=`<option value="${material.code}" data-cost="${material.cost}" data-unit-name="${unitName}">${material.name}</option>`
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
        unitLabel.text(unitName);
        setTimeout(() => {
            quantityMaterial.focus();
        }, 100);
        priceMaterial.val(cost);
        checkForm();
    });
    /*  ======================== End Material Details ============================== */

    /*  ======================== Start Add Material In Table ============================== */
    quantityMaterial.on('keyup', function(e) {
        let totalPriceInput = tableBody.parent().find('tfoot .sumFinal');
        let priceMethod = $('input[name="price_method"]:checked').val();
        let materialCode = material.find('option:selected').attr('value');
        let materialName = material.find('option:selected').text();
        let unitPrice = 0;
        let qty = $(this).val();
        let status = true;
        let totalPrice = 0;

        if (priceMethod == 'static') {
            totalMaterial.text(parseFloat(qty * priceMaterial.val()).toFixed(2))
        }

        if (e.keyCode === 13) {
            if(!material.val()) {
                Toast.fire({
                    icon: 'error',
                    title: "يجب ادخال الخامة"
                });
                status = false;
            }

            if (!quantityComp.val()) {
                Toast.fire({
                    icon: 'error',
                    title: "يجب ادخال كمية المكون"
                });
                status = false;
            }

            if (components.val() && $(this).val() && status) {

                if (tableBody.find(`tr#${materialCode}`).length > 0) {
                    status = false
                    Toast.fire({
                        icon: 'error',
                        title: "هذا العنصر موجود بالفعل"
                    });
                }

                if (priceMethod == 'static' && status) {
                    unitPrice = material.find('option:selected').attr('data-cost');
                    totalPrice = parseFloat(qty * unitPrice);
                    if (totalPrice > totalDynamic.val()) {
                        status = false;
                        Toast.fire({
                            icon: 'error',
                            title: "اجمالى الخامه اكبر من السعر المتبقى للمكون"
                        });
                    }
                    if (status) {
                        totalDynamic.val(totalDynamic.val() - totalPrice);
                        checkDynamicQty(totalDynamic.val() / qtyDynamic.val())
                    }
                } else if (priceMethod == 'variable' && status) {
                    qtyDynamic.val(+qtyDynamic.val() +  +$(this).val())
                    unitPrice = parseFloat(totalDynamic.val() / qtyDynamic.val())
                    totalPrice = unitPrice * +qty;
                    checkDynamicQty(unitPrice)
                }
                priceManufacture.text(parseFloat(totalDynamic.val()).toFixed(2))

                if (status) {
                    let html = `<tr id="${materialCode}" class="${priceMethod} ${priceMethod == 'variable' ? 'table-danger' : ''}">
                        <td>${materialCode}</td>
                        <td>${materialName}</td>
                        <td class="tr-price">${parseFloat(unitPrice).toFixed(3)}</td>
                        <td class="tr-qty">${qty}</td>
                        <td class="tr-total">${parseFloat(totalPrice).toFixed(3)}</td>
                        <td class="d-none">${priceMethod}</td>
                    </tr>`
                    tableBody.append($(html));

                    priceMaterial.val('')
                    quantityMaterial.val('')
                    material.val(null).trigger("change");
                    $(this).blur();
                    setTimeout(() => {
                        material.select2('open');
                    }, 100);
                }
                calcPricePercent();
                totalMaterial.text('')
                priceMaterial.val('')
            }
        }
            checkForm();
    });

    const checkDynamicQty = function(unitPrice) {
        tableBody.find('tr').each(function() {
            if ($(this).hasClass('variable')) {
                let qty = $(this).find('.tr-qty').text();
                $(this).find('.tr-price').text(parseFloat(unitPrice).toFixed(3));
                $(this).find('.tr-total').text((+unitPrice * +qty).toFixed(3));
            }
        });
    }

    /*  ======================== End Add Material In Table ============================== */

    /*  ======================== Function Calculate Total Price ============================== */
    function calcPricePercent() {
        let totalPriceInput = tableBody.parent().find('tfoot .sumFinal');
        let totalPrice = 0;
        tableBody.find('td.tr-total').each(function() {
            totalPrice += parseFloat($(this).text());
        });
        totalPriceInput.text(totalPrice.toFixed(2));
        if (+totalPriceInput.text() == +total.val()) {
            saveManufacturing.prop('disabled', false)
        }
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
                total: $(this).find('td').eq(4).text(),
                type: $(this).find('td').eq(5).text(),
            });
        })

        let formData = new FormData();
        formData.set("_token", _token);
        formData.set("date", date.val());
        formData.set("branch", branch.val());
        formData.set("sections", sections.val());
        formData.set("components", components.find('option:selected').attr('code'));
        formData.set("priceComp", priceComp.val());
        formData.set("quantityComp", quantityComp.val());
        formData.set("halk", halk.val());
        formData.set("materialArray", JSON.stringify(materialArray));

        return formData;

    }
    saveManufacturing.on('click', function() {
        if (quantityComp.val() && quantityComp.val() > 0 ) {
            $.ajax({
                url: "<?php echo e(route('saveManufacturing')); ?>",
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
                        tableBody.html('');
                        calcPricePercent();
                        components.select2('open');
                        priceComp.val('');
                        material.val('');
                        halk.val('');
                        manufacturing.val(data.id)
                        quantityComp.val('').prop('disabled', false).change();
                        total.val('')
                        available.val('')
                        halk.val('')
                        qtyManufacture.text('')
                        priceManufacture.text('')
                        checkForm();
                        saveManufacturing.prop('disabled', true)
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
    newManufacturing.on('click',function () {
        $('#save_operation').removeClass('d-none');
        $('#delete_operation').addClass('d-none');
        $.ajax({
            url: "<?php echo e(route('getOperationViaOrder')); ?>",
            method: 'post',
            data: {
                _token,
            },
            success: function(data) {
                tableBody.html('');
                calcPricePercent();
                components.select2('open');
                priceComp.val('');
                material.val('');
                halk.val('');
                manufacturing.val(data.id)
                quantityComp.val('').prop('disabled', false).change();
                total.val('')
                available.val('')
                halk.val('')
                qtyManufacture.text('')
                priceManufacture.text('')
                checkForm();
                saveManufacturing.prop('disabled', true)
            },
        });
    });
    /*  ======================== End New Operation ============================== */

    /*  ======================== Search Operation Number============================== */
    manufacturing.on('change',function (){
        $.ajax({
            url: "<?php echo e(route('getManufacturingViaOrder')); ?>",
            method: 'post',
            data: {
                _token,
                order: manufacturing.val(),
            },
            success: function(data) {
                let html = '';
                if (data.status == true) {
                    branch.val(data.materials.branch_id).trigger('change').prop('disabled', true);
                    setTimeout(() => {
                        sections.val(data.materials.sec_store).trigger('change').prop('disabled', true);
                    }, 500);
                    setTimeout(() => {
                        sections.val(data.materials.sec_store).trigger('change').prop('disabled', true);
                    }, 1000);
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
                saveManufacturing.addClass('d-none');
                deleteManufacturing.removeClass('d-none');
            },
        });
    });


});

</script>
<?php /**PATH C:\xampp\htdocs\webpoint\resources\views/includes/stock/Stock_Ajax/material_manufacturing.blade.php ENDPATH**/ ?>
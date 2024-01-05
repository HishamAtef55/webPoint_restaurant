<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    let permission = $('#permission');
    let date = $('#date');
    let branch = $('#branch');
    let sections = $('#sections');
    let stores = $('#stores');
    let items = $('#items');
    let unit = $('#unit');
    let quantity = $('#quantity');
    let maxInputHidden = $('#max');
    let priceUnit = $('#price_unit');
    let totalUnit = $('#total_unit');
    let currentBalance = $('#current_balance');
    let notes = $('#notes');
    let tableBody = $('.table-purchases tbody');
    let permissionId = $('#permissionId');
    let updateBtn = $('#update_purchases');
    let saveBtn = $('#save_purchases');
    let deleteBtn = $('#delete_purchases');
    let Image;
    let now = new Date();

    let day = ("0" + now.getDate()).slice(-2);
    let month = ("0" + (now.getMonth() + 1)).slice(-2);

    let today = now.getFullYear()+"-"+(month)+"-"+(day) ;



$(document).ready(function() {

    items.select2({
        matcher: customMatcher,
        dir: "rtl"
    });
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
        if ($(data.element).attr('code').indexOf(params.term) > -1) {
            return data;
        }

        // If it doesn't contain the term, don't return anything
        return null;
    }
    /*  ======================== Start Change Purchases Method ============================== */
    $('input[name="purchases_method"]').on('change', function() {
        let type = $(this).val()
        $.ajax({
            url: "<?php echo e(route('changePurchasesType')); ?>",
            method: 'post',
            data: {
                _token,
                type,
            },
            success: function(data) {
                permission.val(data.serial)
                permissionId.attr('value', data.serial)
                if (type === 'section') {
                    $('.branch-sec').removeClass('d-none')
                    $('.stores').addClass('d-none')
                } else if(type === 'store') {
                    $('.branch-sec').addClass('d-none')
                    $('.stores').removeClass('d-none')
                }
                resetPage();
                calcTotal();
            }
        });
    });
    /*  ======================== End Change Purchases Method ============================== */
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
                },
            });
    });
    /*  ======================== End Change Branch ============================== */
    /*  ======================== Start Change Stores ============================== */
    stores.on('change',function() {
        $.ajax({
            url: "<?php echo e(route('changePurchasesStore')); ?>",
            method: 'post',
            data: {
                _token,
                store: stores.val(),
            },
            success: function(data) {
                let html = '<option value=""code="" disabled selected></option>';
                data.materials.forEach((material) => {
                    html += `<option value="${material.id}" code="${material.code}">${material.material}</option>`
                });
                items.html(html);
            },
        });
    });
    /*  ======================== End Change Stores ============================== */
    /*  ======================== Start Change Sections ============================== */
    sections.on('change',function() {
        $.ajax({
            url: "<?php echo e(route('changePurchasesSection')); ?>",
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
                items.html(html)
            },
        });
    });
    /*  ======================== End Change Sections ============================== */
    /*  ======================== Start Change Items ============================== */
    items.on('change', function() {
        let type = $('input[name="purchases_method"]:checked').val()
        $.ajax({
            url: "<?php echo e(route('changePurchasesUnit')); ?>",
            method: 'post',
            data: {
                _token,
                type,
                id: items.val()
            },
            success: function(data) {
                let html = '<option value="" disabled selected></option>';
                data.units.forEach((unit) => {
                    html += `<option value="${unit.size}">${unit.name}</option>`
                });
                unit.html(html);
                unit.val(data.units[0].size);
                priceUnit.val(data.last_price);
                quantity.select().focus();
                currentBalance.val(data.qty)
                maxInputHidden.val(data.max)
                checkForm();
            },
        });
    });
    /*  ======================== End Change Items ============================== */
    /*  ======================== Start Change QTY & Price & Total ============================== */
    quantity.on('input', function() {
        if(maxInputHidden.val() == 0) {
            totalUnit.val((+priceUnit.val() * +$(this).val()).toFixed(2))
        } else if(+$(this).val() + +currentBalance.val() >= maxInputHidden.val()) {
            $(this).val(+maxInputHidden.val() - +currentBalance.val());
            Toast.fire({
                icon: 'error',
                title: `الحد الأقصى لهذه الخامه (${maxInputHidden.val()}) `
            });
            totalUnit.val((+priceUnit.val() * +$(this).val()).toFixed(2))
        }
        totalUnit.val((+priceUnit.val() * +$(this).val()).toFixed(2))
        checkForm();
    });
    /*  ======================== End Change QTY & Price & Total ============================== */
    /*  ======================== Start Add Data To Table ============================== */
    function addDataToTable() {
        let code = items.find('option:selected').attr('code');
        let itemName = items.find('option:selected').html();
        let unitName = unit.find('option:selected').html();

        if (!code) {
            Toast.fire({
                icon: 'error',
                title: 'يجب اختبار صنف'
            });
            return false
        }
        if (!unitName) {
            Toast.fire({
                icon: 'error',
                title: 'يجب اختبار وحدة قياس'
            });
            return false
        }
        if (!quantity.val()) {
            Toast.fire({
                icon: 'error',
                title: 'يجب ادخال كمية'
            });
            return false
        }

        let html = `<tr rowId="0" class="new">
            <td>${code}</td>
            <td>${itemName}</td>
            <td>${unitName}</td>
            <td>${priceUnit.val()}</td>
            <td>
                <input type="number" value="${quantity.val()}"/>
                <span>${quantity.val()}</span>
            </td>
            <td class="totalPrice">${totalUnit.val()}</td>
            <td>
                <div class="del-edit">
                    <button class="btn btn-danger delete_unit"><i class="fa-regular fa-trash-can"></i></button>
                </div>
            </td>
        </tr>`

        tableBody.find('tr.not-found').length ? $('tr.not-found').remove() : '';
        tableBody.append($(html));
        totalUnit.val('');
        quantity.val('');
        priceUnit.val('');
        branch.prop('disabled', true);
        sections.prop('disabled', true);
        stores.prop('disabled', true);
        items.select2('open');
        calcTotal();
        checkForm();
    }
    /*  ======================== End Add Data To Table ============================== */
    /*  ======================== Start Add Row In Table ============================== */
    quantity.on('keyup', function(e) {
        if (e.keyCode === 13) {
            addDataToTable();
        }
    });
    /*  ======================== Start Add Row In Table ============================== */
    /*  ======================== Start Delete Row In Table ============================== */
    $(document).on('click', '.delete_unit', function() {
        let rowParent = $(this).parents('tr');
        let type = $('input[name="purchases_method"]:checked').val();
        let rowId = rowParent.attr('rowId');
        let code = rowParent.find('td').eq(0).text();
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
                calcTotal();
                if (rowParent.hasClass('new')) {
                    rowParent.remove();
                } else if (rowParent.hasClass('old')) {
                    $.ajax({
                        url: "<?php echo e(route('deleteItemPurchase')); ?>",
                        method: 'post',
                        data: {
                            _token,
                            type,
                            rowId,
                            code,
                            permission: permission.val(),
                            sumTotal: $('.sumTotal').text()
                        },
                        success: function(data) {
                            if (data.status == true) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.data
                                });
                                rowParent.remove();
                                calcTotal();
                            }
                        },
                    });
                }
            }
        })
    });
    /*  ======================== End Delete Row In Table ============================== */
    /*  ======================== Start Edit Row In Table ============================== */
    $(document).on('click', '.edit_unite', function() {
        let rowParent = $(this).parents('tr');
        rowParent.addClass('edit');
        rowParent.find('input').eq(0).focus().select()
    });
    /*  ======================== End Edit Row In Table ============================== */
    /*  ======================== Start Update Row In Table ============================== */
    $(document).on('click', '.update_unite', function() {
        let type = $('input[name="purchases_method"]:checked').val()
        let rowParent = $(this).parents('tr');
        let rowId = rowParent.attr('rowId');
        let code = rowParent.find('td').eq(0).text()
        rowParent.removeClass('edit');


        let price = +rowParent.find('td').eq(3).text()
        let qty =  +rowParent.find('input').eq(0).val()

        console.log(price)
        console.log(qty)

        let total = price * qty;

        rowParent.find('td').eq(4).find('span').text(qty);
        rowParent.find('td').eq(5).text(total);

        calcTotal();
        if (rowParent.hasClass('old')) {
            $.ajax({
                url: "<?php echo e(route('updateItemPurchase')); ?>",
                method: 'post',
                data: {
                    _token,
                    rowId,
                    code,
                    type,
                    permission: permission.val(),
                    sumTotal: $('.sumTotal').text(),
                    priceUnit: price,
                    quantity: qty,
                    totalUnit: total
                },
                success: function(data) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data
                    });
                },
            });
        }

    });
    /*  ======================== End Update Row In Table ============================== */
    /*  ======================== Start Calculate Total Table ============================== */
    function calcTotal() {
        let totalPrice = 0
        let totalTax = 0
        let totalDiscount = 0
        let finalTotal = 0
        $('.totalPrice').each(function() {
            totalPrice += +$(this).text();
        });

        $('.sumTotal').text(totalPrice.toFixed(2))
    }
    /*  ======================== End Calculate Total Table ============================== */
    $('#permission_file').on('change', function(e) {
        Image = e.target.files[0]
    })
    /*  ======================== Start Save Table ============================== */
    function setData() {
        let materialArray = [];
        let type = $('input[name="purchases_method"]:checked').val();

        tableBody.find('tr.new').each(function() {
            materialArray.push({
                code: $(this).find('td').eq(0).text(),
                itemName: $(this).find('td').eq(1).text(),
                unitName: $(this).find('td').eq(2).text(),
                priceUnit: $(this).find('td').eq(3).text(),
                quantity: $(this).find('td').eq(4).find('span').text(),
                totalUnit: $(this).find('td').eq(5).text(),
            });
        })

        let formData = new FormData();
        formData.set("_token", _token);
        formData.set("type", type)
        formData.set("permission", permission.val())
        formData.set("date", date.val())
        formData.set("branch", branch.val())
        formData.set("sections", sections.val())
        formData.set("stores", stores.val())
        formData.set("materialArray", JSON.stringify(materialArray))
        formData.set("sumTotal", $('.sumTotal').text())
        formData.set("notes", notes.val())
        formData.set("image", Image);
        if (materialArray.length == 0) {
            Toast.fire({
                icon: 'error',
                title: "لم يتم التعديل على اى شىء"
            });
            return false
        } else {
            return formData;
        }
    }
    saveBtn.on('click', function() {
        $.ajax({
            url: "<?php echo e(route('stock.orders.save')); ?>",
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
                    resetPage();
                    permissionId.attr('value', data.id);
                    permission.val(data.id);
                }
            },
        });
    });
    updateBtn.on('click', function() {
        $.ajax({
            url: "<?php echo e(route('stock.orders.update')); ?>",
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
                        title: data.data,
                    });
                    resetPage();
                    permission.val(permissionId.attr('value'));
                }
            },
        });
    });
    deleteBtn.on('click', function() {
        let type = $('input[name="purchases_method"]:checked').val();
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
                $.ajax({
                    url: "<?php echo e(route('stock.orders.destroy')); ?>",
                    method: 'post',
                    data: {
                        _token,
                        type,
                        permission: permission.val()
                    },
                    success: function(data) {
                        if (data.status == true) {
                            Toast.fire({
                                icon: 'success',
                                title: data.data
                            });
                            resetPage();
                            permission.val(permissionId.attr('value'));
                        }
                    },
                });
            }
        })
    });
    /*  ======================== End Save Table ============================== */
    /*  ======================== Start Permission Search ============================== */
    function getPurchase(data) {
        let type = $('input[name="purchases_method"]:checked').val();
        if (!data.data) {
            resetPage();
            Toast.fire({
                icon: 'error',
                title: 'الرقم الذى ادخلته غير موجود'
            });
            return false
        }
        if (type === 'section') {
            branch.val(data.data.branch_id).trigger('change').prop('disabled', true);
            setTimeout(() => {
                sections.val(data.data.section_id).trigger('change').prop('disabled', true);
            }, 500);
        } else if(type === 'store') {
            stores.val(data.data.sto_sec_id).trigger('change').prop('disabled', true);
        }
        permission.val(data.data.id);
        date.val(data.data.date);
        notes.val(data.data.note || '');
        $(`input[name="pay_method"][value="${data.data.type}"]`).prop('checked', true);
        $('.sumTotal').text(data.data.total);
        let html = "";
        data.data.details.forEach((detail) => {
            html += html = `<tr rowId="${detail.id}" class="old">
                <td>${detail.code}</td>
                <td>${detail.name}</td>
                <td>${detail.unit}</td>
                <td>${detail.price}</td>
                <td>
                    <input type="number" value="${detail.qty}"/>
                    <span>${detail.qty}</span>
                </td>
                <td class="totalPrice">${detail.qty * detail.price}</td>
                <td>
                    <div class="del-edit">
                        <button class="btn btn-danger delete_unit"><i class="fa-regular fa-trash-can"></i></button>
                    </div>
                </td>
            </tr>`
        });
        tableBody.html(html);
        saveBtn.addClass('d-none')
        updateBtn.removeClass('d-none')
        deleteBtn.removeClass('d-none')
        checkForm();
    }

    permission.on('change', function() {
        let type = $('input[name="purchases_method"]:checked').val()

        $.ajax({
            url: "<?php echo e(route('stock.orders.getData')); ?>",
            method: 'post',
            data: {
                _token,
                type,
                permission: permission.val(),
            },
            success: function(data) {
                getPurchase(data)
            },
        });
    });
    /*  ======================== End Permission Search ============================== */
    /*  ======================== Start Reset Page ============================== */
    function resetPage() {
        permission.val(permissionId.attr('value'));
        date.val(today);
        branch.val('').prop('disabled', false);
        sections.val('').prop('disabled', false);
        stores.val('').prop('disabled', false);
        items.val('');
        unit.val('');
        quantity.val('');
        priceUnit.val('');
        totalUnit.val('');
        notes.val('');
        tableBody.html('<tr class="not-found"> <td colspan="7">لا يوجد بيانات</td></tr>');
        saveBtn.removeClass('d-none');
        updateBtn.addClass('d-none');
        deleteBtn.addClass('d-none');
        calcTotal();
        checkForm();
    }
    /*  ======================== End Reset Page ============================== */

});
</script>
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/stock/Stock_Ajax/orders.blade.php ENDPATH**/ ?>
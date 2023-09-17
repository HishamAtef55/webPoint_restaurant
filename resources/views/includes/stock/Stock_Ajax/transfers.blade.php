@include('includes.stock.Stock_Ajax.public_function')
<script>
    let permission = $('#permission');
    let seriesNumber = $('#series_number');
    let date = $('#date');
    let branch = $('#branch');
    let fromSection = $('#fromSection');
    let toSection = $('#toSection');
    let fromStore = $('#fromStore');
    let toStore = $('#toStore');
    let items = $('#items');
    let unit = $('#unit');
    let tax = $('#tax');
    let quantity = $('#quantity');
    let priceUnit = $('#price_unit');
    let totalUnit = $('#total_unit');
    let lastPrice = $('#last_price');
    let currentBalance = $('#current_balance');
    let Expire = $('#Expire');
    let discount = $('#discount');
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

    $('select').select2({
        selectOnClose: true,
        dir: "rtl"
    });
    items.select2({
        matcher: customMatcher
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
        if ($(data.element).attr('code').toString().indexOf(params.term) > -1) {
            return data;
        }

        // If it doesn't contain the term, don't return anything
        return null;
    }
    /*  ======================== Start Change Purchases Method ============================== */
    $('input[name="purchases_method"]').on('change', function() {
        let type = $(this).val()
        $.ajax({
            url: "{{route('changeTransferType')}}",
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
                    fromSection.html(html)
                    toSection.html(html)
                },
            });
    });
    /*  ======================== End Change Branch ============================== */
    /*  ======================== Start Change Stores ============================== */
    fromStore.on('change',function() {
        $.ajax({
            url: "{{route('changePurchasesStore')}}",
            method: 'post',
            data: {
                _token,
                store: fromStore.val(),
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
    fromSection.on('change',function() {
        $.ajax({
            url: "{{route('changePurchasesSection')}}",
            method: 'post',
            data: {
                _token,
                section: fromSection.val(),
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
            url: "{{route('changePurchasesUnit')}}",
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
                lastPrice.val(data.last_price);
                priceUnit.val(data.ava).select().focus();
                currentBalance.val(data.qty);
                checkForm();
            },
        });
    });
    /*  ======================== End Change Items ============================== */
    /*  ======================== Start Change QTY & Price & Total ============================== */
    quantity.on('change', function() {
        if(priceUnit.val()) {
            totalUnit.val((+priceUnit.val() * +$(this).val()).toFixed(2))
        }
        if(totalUnit.val()) {
            priceUnit.val((+totalUnit.val() / +$(this).val()).toFixed(2))
        }
        checkForm();
    });
    priceUnit.on('change', function() {
        if(quantity.val()) {
            totalUnit.val((+quantity.val() * +$(this).val()).toFixed(2))
        }
        if(totalUnit.val()) {
            quantity.val((+totalUnit.val() / +$(this).val()).toFixed(2))
        }
        checkForm();
    });
    totalUnit.on('change', function() {
        if(quantity.val()) {
            priceUnit.val((+$(this).val() / +quantity.val()).toFixed(2))
        }
        if(priceUnit.val()) {
            quantity.val(( +$(this).val() / +priceUnit.val()).toFixed(2))
        }
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
        if (!priceUnit.val()) {
            Toast.fire({
                icon: 'error',
                title: 'يجب ادخال سعر'
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
        if (!totalUnit.val()) {
            Toast.fire({
                icon: 'error',
                title: 'يجب ادخال اجمالى'
            });
            return false
        }
        let taxPrice = (+totalUnit.val() * ((+tax.val() || 0) / 100)).toFixed(2)
        let discountPrice = (+totalUnit.val() * ((+discount.val() || 0) / 100)).toFixed(2);
        let finalTotal = (+totalUnit.val() + +taxPrice - +discountPrice).toFixed(2)
        let html = `<tr rowId="0" class="new">
            <td>${code}</td>
            <td>${itemName}</td>
            <td>${unitName}</td>
            <td>
                <input type="number"   value="${priceUnit.val()}"/>
                <span>${priceUnit.val()}</span>
            </td>
            <td>
                <input type="number"  value="${quantity.val()}"/>
                <span>${quantity.val()}</span>
            </td>
            <td class="finalTotal">${finalTotal}</td>
            <td>
                <div class="del-edit">
                    <button class="btn btn-danger delete_unit"><i class="fa-regular fa-trash-can"></i></button>
                    <button class="btn btn-warning edit_unite"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>
                <button class="btn btn-primary update_unite update">Update</button>
            </td>
        </tr>`

        tableBody.append($(html));
        discount.val('');
        totalUnit.val('');
        quantity.val('');
        priceUnit.val('');
        branch.prop('disabled', true);
        fromSection.prop('disabled', true);
        toSection.prop('disabled', true);
        fromStore.prop('disabled', true);
        toStore.prop('disabled', true);
        items.select2('open');
        calcTotal();
        checkForm();
    }
    /*  ======================== End Add Data To Table ============================== */
    /*  ======================== Start Add Row In Table ============================== */
    discount.on('keyup', function(e) {
        if (e.keyCode === 13) {
            addDataToTable()
        }
    });
    quantity.on('keyup', function(e) {
        if (e.keyCode === 13) {
            totalUnit.focus();
        }
    });
    priceUnit.on('keyup', function(e) {
        if (e.keyCode === 13) {
            quantity.focus();
        }
    });
    totalUnit.on('keyup', function(e) {
        if (e.keyCode === 13) {
            addDataToTable()
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
                        url: "{{route('deleteItemTransfer')}}",
                        method: 'post',
                        data: {
                            _token,
                            type,
                            rowId,
                            code,
                            permission: permission.val(),
                            sumTotal: $('.sumTotal').text(),
                            sumTax: $('.sumTax').text(),
                            sumDiscount: $('.sumDiscount').text(),
                            sumFinal: $('.sumFinal').text(),
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
        let taxRatio = +rowParent.find('.tax-ratio').text()
        let discountRatio = +rowParent.find('.discount-ratio').text()

        let price = +rowParent.find('input').eq(0).val()
        let qty =  +rowParent.find('input').eq(1).val()

        let total = price * qty;

        let taxPrice = (total * (taxRatio / 100)).toFixed(2);
        let discountPrice = (total * (discountRatio / 100)).toFixed(2);
        let finalTotal = (total + +taxPrice - +discountPrice).toFixed(2);

        rowParent.find('td').eq(3).find('span').text(price);
        rowParent.find('td').eq(4).find('span').text(qty);
        rowParent.find('td').eq(5).text(finalTotal);

        calcTotal();
        if (rowParent.hasClass('old')) {
            $.ajax({
                url: "{{route('updateItemTransfer')}}",
                method: 'post',
                data: {
                    _token,
                    rowId,
                    code,
                    type,
                    permission: permission.val(),
                    sumTotal: $('.sumTotal').text(),
                    sumTax: $('.sumTax').text(),
                    sumDiscount: $('.sumDiscount').text(),
                    sumFinal: $('.sumFinal').text(),
                    priceUnit: price,
                    quantity: qty,
                    totalUnit: total,
                    taxPrice,
                    discountPrice,
                    finalTotal,
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
        $('.totalTax').each(function() {
            totalTax += +$(this).text();
        });
        $('.totalDiscount').each(function() {
            totalDiscount += +$(this).text();
        });
        $('.finalTotal').each(function() {
            finalTotal += +$(this).text();
        });

        $('.sumTotal').text(totalPrice)
        $('.sumTax').text(totalTax)
        $('.sumDiscount').text(totalDiscount)
        $('.sumFinal').text(finalTotal)
    }
    /*  ======================== End Calculate Total Table ============================== */
    $('#permission_file').on('change', function(e) {
        Image = e.target.files[0]
    })
    /*  ======================== Start Save Table ============================== */
    function setData() {
        let materialArray = [];
        let type = $('input[name="purchases_method"]:checked').val();
        let payType = $('input[name="pay_method"]:checked').val();

        tableBody.find('tr.new').each(function() {
            materialArray.push({
                code: $(this).find('td').eq(0).text(),
                itemName: $(this).find('td').eq(1).text(),
                unitName: $(this).find('td').eq(2).text(),
                priceUnit: $(this).find('td').eq(3).find('span').text(),
                quantity: $(this).find('td').eq(4).find('span').text(),
                finalTotal: $(this).find('td').eq(5).text(),
            });
        })

        let formData = new FormData();
        formData.set("_token", _token);
        formData.set("type", type)
        formData.set("permission", permission.val())
        formData.set("seriesNumber", seriesNumber.val())
        formData.set("date", date.val())
        formData.set("branch", branch.val())
        formData.set("fromSection", fromSection.val())
        formData.set("toSection", toSection.val())
        formData.set("fromStore", fromStore.val())
        formData.set("toStore", toStore.val())
        formData.set("materialArray", JSON.stringify(materialArray))
        formData.set("sumFinal", $('.sumFinal').text())
        formData.set("payType", payType)
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
            url: "{{route('saveTransfer')}}",
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
            url: "{{route('updateTransfer')}}",
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
                    url: "{{route('deleteTransfer')}}",
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
        if (data.data.type === 'section') {
            branch.val(data.data.branch_id).trigger('change').prop('disabled', true);
            setTimeout(() => {
                fromSection.val(data.data.from).trigger('change').prop('disabled', true);
                toSection.val(data.data.to).trigger('change').prop('disabled', true);
            }, 500);
        } else if(data.data.type === 'store') {
            fromStore.val(data.data.from).trigger('change').prop('disabled', true);
            toStore.val(data.data.to).trigger('change').prop('disabled', true);
        }
        permission.val(data.data.id);
        seriesNumber.val(data.data.serial);
        date.val(data.data.date);
        notes.val(data.data.note || '');
        $('.sumFinal').text(data.data.total);
        let html = "";
        data.data.details.forEach((detail) => {
            html += html = `<tr rowId="${detail.id}" class="old">
                <td>${detail.code}</td>
                <td>${detail.name}</td>
                <td>${detail.unit}</td>
                <td>
                    <input type="number" value="${detail.price}"/>
                    <span>${detail.price}</span>
                </td>
                <td>
                    <input type="number" value="${detail.qty}"/>
                    <span>${detail.qty}</span>
                </td>
                <td class="finalTotal">${detail.total}</td>
                <td>
                    <div class="del-edit">
                        <button class="btn btn-danger delete_unit"><i class="fa-regular fa-trash-can"></i></button>
                        <button class="btn btn-warning edit_unite"><i class="fa-regular fa-pen-to-square"></i></button>
                    </div>
                    <button class="btn btn-primary update_unite">Update</button>
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
            url: "{{route('getTransfer')}}",
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

    seriesNumber.on('change', function() {
        let type = $('input[name="purchases_method"]:checked').val()
        $.ajax({
            url: "{{route('getTransferViaSerial')}}",
            method: 'post',
            data: {
                _token,
                type,
                serial: seriesNumber.val(),
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
        seriesNumber.val('');
        date.val(today);
        branch.val('').prop('disabled', false);
        fromSection.val('').prop('disabled', false);
        toSection.val('').prop('disabled', false);
        fromStore.val('').prop('disabled', false);
        toStore.val('').prop('disabled', false);
        items.val('');
        unit.val('');
        quantity.val('');
        priceUnit.val('');
        totalUnit.val('');
        lastPrice.val('');
        currentBalance.val('');
        Expire.val(today);
        discount.val('');
        notes.val('');
        tableBody.html('');
        saveBtn.removeClass('d-none');
        updateBtn.addClass('d-none');
        deleteBtn.addClass('d-none');
        calcTotal();
        checkForm();
    }
    /*  ======================== End Reset Page ============================== */

});
</script>

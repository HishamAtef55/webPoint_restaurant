@include('includes.Stock_Ajax.public_function')
<script>
    let permission = $('#permission');
    let seriesNumber = $('#series_number');
    let orderNumber = $('#order_number');
    let supplier = $('#supplier');
    let tax = $('#tax');
    let date = $('#date');
    let branch = $('#branch');
    let sections = $('#sections');
    let stores = $('#stores');
    let items = $('#items');
    let unit = $('#unit');
    let quantity = $('#quantity');
    let priceUnit = $('#price_unit');
    let totalUnit = $('#total_unit');
    let lastPrice = $('#last_price');
    let currentBalance = $('#current_balance');
    let Expire = $('#Expire');
    let discount = $('#discount');
    let notes = $('#notes');
    let tableBody = $('.table-exchange tbody');
    let permissionId = $('#permissionId');
    let updateBtn = $('#update_exchange');
    let saveBtn = $('#save_exchange');
    let deleteBtn = $('#delete_exchange');
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
                },
            });
    });
    /*  ======================== End Change Branch ============================== */
    {{--/*  ======================== Start Change Sections ============================== */--}}
    sections.on('change',function() {
        $.ajax({
            url: "{{route('changePurchasesSection')}}",
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
    {{--/*  ======================== End Change Sections ============================== */--}}
    /*  ======================== Start Change Items ============================== */
    items.on('change', function() {
        let type = $('input[name="purchases_method"]:checked').val()
        $.ajax({
            url: "{{route('changePurchasesUnit')}}",
            method: 'post',
            data: {
                _token,
                type:'section',
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
                priceUnit.val(data.last_price).select().focus();
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
                <input type="number" class="d-none"  value="${priceUnit.val()}"/>
                <span>${priceUnit.val()}</span>
            </td>
            <td>
                <input type="number" class="d-none" value="${quantity.val()}"/>
                <span>${quantity.val()}</span>
            </td>
            <td class="finalTotal">${finalTotal}</td>
            <td>
                <div class="del-edit">
                    <button class="btn btn-danger delete_unit"><i class="fa-regular fa-trash-can"></i></button>
                    <button class="btn btn-warning edit_unite"><i class="fa-regular fa-pen-to-square"></i></button>
                </div>
                <button class="btn btn-primary update_unite d-none">Update</button>
            </td>
        </tr>`

        tableBody.append($(html));
        discount.val('');
        totalUnit.val('');
        quantity.val('');
        priceUnit.val('');
        supplier.prop('disabled', true);
        branch.prop('disabled', true);
        sections.prop('disabled', true);
        stores.prop('disabled', true);
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
                        url: "{{route('deleteItemBackToStores')}}",
                        method: 'post',
                        data: {
                            _token,
                            rowId,
                            code,
                            permission: permission.val(),
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
                url: "{{route('updateItemBackToStores')}}",
                method: 'post',
                data: {
                    _token,
                    rowId,
                    code,
                    permission: permission.val(),
                    sumFinal: $('.sumFinal').text(),
                    priceUnit: price,
                    quantity: qty,
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
        formData.set("permission", permission.val())
        formData.set("seriesNumber", seriesNumber.val())
        formData.set("orderNumber", orderNumber.val())
        formData.set("date", date.val())
        formData.set("branch", branch.val())
        formData.set("sections", sections.val())
        formData.set("stores", stores.val())
        formData.set("materialArray", JSON.stringify(materialArray))
        formData.set("sumFinal", $('.sumFinal').text())
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
            url: "{{route('saveBackToStores')}}",
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
            url: "{{route('updateBackToStores')}}",
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
                    url: "{{route('deleteBackToStores')}}",
                    method: 'post',
                    data: {
                        _token,
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
        if (!data.data) {
            resetPage();
            Toast.fire({
                icon: 'error',
                title: 'الرقم الذى ادخلته غير موجود'
            });
            return false
        }
        permission.val(data.data.id);
        seriesNumber.val(data.data.serial);
        date.val(data.data.date);
        notes.val(data.data.note || '');
        supplier.val(data.data.supplier).trigger('change').prop('disabled', true);
        branch.val(data.data.branch_id).trigger('change').prop('disabled', true);
        setTimeout(() => {
            sections.val(data.data.section_id).trigger('change').prop('disabled', true);
        }, 500);
        stores.val(data.data.store_id).trigger('change').prop('disabled', true);

        $('.sumFinal').text(data.data.total);
        let html = "";
        data.data.details.forEach((detail) => {
            html += html = `<tr rowId="${detail.id}" class="old">
                <td>${detail.code}</td>
                <td>${detail.name}</td>
                <td>${detail.unit}</td>
                <td>
                    <input type="number" class="d-none" value="${detail.price}"/>
                    <span>${detail.price}</span>
                </td>
                <td>
                    <input type="number" class="d-none" value="${detail.qty}"/>
                    <span >${detail.qty}</span>
                </td>
                <td class="finalTotal">${detail.total}</td>
                <td>
                    <div class="del-edit">
                        <button class="btn btn-danger delete_unit"><i class="fa-regular fa-trash-can"></i></button>
                        <button class="btn btn-warning edit_unite"><i class="fa-regular fa-pen-to-square"></i></button>
                    </div>
                    <button class="btn btn-primary update_unite d-none">Update</button>
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
        $.ajax({
            url: "{{route('getBackToStores')}}",
            method: 'post',
            data: {
                _token,
                permission: permission.val(),
            },
            success: function(data) {
                getPurchase(data)
            },
        });
    });

    seriesNumber.on('change', function() {
        $.ajax({
            url: "{{route('getBackToStoresViaSerial')}}",
            method: 'post',
            data: {
                _token,
                serial: seriesNumber.val(),
            },
            success: function(data) {
                getPurchase(data)
            },
        });
    });
    orderNumber.on('change',function (){
        $.ajax({
            url: "{{route('getExchangeViaOrder')}}",
            method: 'post',
            data: {
                _token,
                order: orderNumber.val(),
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
        supplier.val('').prop('disabled', false);
        tax.val('');
        date.val(today);
        branch.val('').prop('disabled', false);
        sections.val('').prop('disabled', false);
        stores.val('').prop('disabled', false);
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

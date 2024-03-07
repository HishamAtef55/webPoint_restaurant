<script>
    // ###################### Start Remove Order #####################################

    $(document).on('click', '#Remove_Delivery', function() {
        let _token = $('input[name="_token"]').val();
        let order_id = $(this).parents(".box").find('.order_id').text();
        let deliveryButton = $('.nav-item.nav-delivery').find('.notification-num');
        let toGoButton = $('.nav-item.nav-togo').find('.notification-num');
        let toPilotNot = $('.to-pilot-btn').find('.notification-num');
        let deliveryHoldingList = $('.holding-list-btn').find('.notification-num');
        let toGoHoldingList = $('.holdingList').find('.notification-num');
        let btn = $(this);
        let page = $("#check_page");
        let operation = $('#operation').attr('value');
        let ordersNum = $(this).parents('.togo-order').find('.ordersNum');
        let ordersPrice = $(this).parents('.togo-order').find('.ordersPrice');
        let orderPrice = $('.box.focused').find('li.orderPrice span');

        Swal.fire({
            title: 'Are you sure?',
            text: "You Want Delete This Order",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('Remove.Delivery')); ?>",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data: {
                        _token: _token,
                        order_id: order_id,
                        operation: operation,
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            $("body").removeClass("blur");
                            btn.parents(".box").remove();

                            if (operation.attr('value') == 'Delivery') {
                                if (page.attr('value') == 'hold_order') {
                                    deliveryHoldingList.removeClass('del').text(parseInt(deliveryHoldingList.text()) - 1);
                                    if (deliveryHoldingList.text() == 0) {
                                        deliveryHoldingList.addClass('del')
                                    }
                                } else if (page.attr('value') == 'to_pilot') {
                                    toPilotNot.removeClass('del').text(parseInt(toPilotNot.text()) - 1);
                                    if (toPilotNot.text() == 0) {
                                        toPilotNot.addClass('del')
                                    }
                                }
                                deliveryButton.removeClass('del').text(parseInt(deliveryButton.text() || 0) - 1);
                                if (deliveryButton.text() <= 0) {
                                    deliveryButton.addClass('del')
                                }
                            } else if (operation.attr('value') == 'TO_GO') {
                                if (page.attr('value') == 'hold_togo') {
                                    toGoHoldingList.removeClass('del').text(parseInt(toGoHoldingList.text()) - 1);
                                    if (toGoHoldingList.text() == 0) {
                                        toGoHoldingList.addClass('del')
                                    }

                                    toGoButton.removeClass('del').text(parseInt(toGoButton.text() || 0) - 1);
                                    if (toGoButton.text() <= 0) {
                                        toGoButton.addClass('del')
                                    }
                                }
                                ordersNum.text(parseInt(ordersNum.text()) - 1);
                                ordersPrice.text((parseFloat(ordersPrice.text()) - parseFloat(orderPrice.text())).toFixed(2));
                            }
                        }
                    },
                });
            }
        });
    });
    // ###################### End Remove Order #####################################
    // ###################### Start Add Pilot #####################################
    function checkPilot(pilotId, pilotName) {
        let flag = 0
        $('#select_pilot option').each(function() {
            if ($(this).val() == pilotId) {
                flag = 1
            }
        });
        if (flag == 0) {
            let newOption = $(`<option value="${pilotId}">${pilotName}</option>`)
            $('#select_pilot').append(newOption);
        }
    }
    $(document).on('click', '.secoundprint', function() {
        $('#summary_hold').click();
        $('#pay-model').find('.checkout').addClass('d-none');
    });
    $(document).on('click', '#add_pilot', function() {
        let selectPilot = $(this).parents('.modal').find('select option:selected');
        let _token = $('input[name="_token"]').val();
        let order_id = $(this).parents("#pilot").attr('order_id');
        let pilotAccNot = $('.pilot-acc').find('.notification-num');
        let toPilotNot = $('.to-pilot-btn').find('.notification-num');
        let pilot = $('.custom-select').val();
        let page = $('#check_page').attr('value')
        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('add.pilot.Delivery')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                order_id: order_id,
                pilot: pilot
            },
            success: function(data) {
                if (data.status == 'true') {
                    // $('#summary_hold').click();
                    $('#new_order').attr('value', order_id);

                    if (page == 'pilot_account') {
                        $(`.box`).find(`.order_id:contains(${order_id})`).parents('ul').find('.pilot-name').text(selectPilot.text());
                        $('#pilot').modal('hide');
                        checkPilot(pilot, selectPilot.text());
                    } else {
                        $(`.box`).find(`.order_id:contains(${order_id})`).parents('.box').remove();
                        pilotAccNot.removeClass('del').text(parseInt(pilotAccNot.text()) + 1)
                        toPilotNot.removeClass('del').text(parseInt(toPilotNot.text()) - 1)
                        if (toPilotNot.text() == 0) {
                            toPilotNot.addClass('del')
                        }
                    }
                    $('#pilot').modal('hide')
                }
            },
        });
    });
    // ###################### End Add Pilot #####################################
    // ######################  View OrdersM in Main Table#####################################

    $(document).ready(function() {
        $('#select_location').on('change', function() {
            let _token = $('input[name="_token"]').val();
            let html = '';
            let query = $('#select_location').val();
            let page = $('#check_page').attr('value');
            $.ajax({
                url: "<?php echo e(route('Search.order.delivery')); ?>",
                method: 'post',
                data: {
                    query: query,
                    _token: _token,
                    page: page
                },
                success: function(data) {
                    let total = 0;
                    $('.box').remove();
                    let money = 0;
                    let counter = 0;
                    let pilotValue = 0;
                    for (var count = 0; count < data.length; count++) {
                        counter++
                        money += data[count].total
                        pilotValue += data[count].locations.pilot_value
                        html += `<div class='box' box_id="${data[count].order_id}">`

                        html += `<ul class="list-unstyled box-list">`
                        html += `<li>`
                        html += `<i class="fas fa-hashtag"></i>`
                        html += `<span class="order_id"> ${data[count].order_id}</span>`
                        html += `</li>`

                        html += `<li>`
                        html += `<i class="fas fa-user"></i>`
                        html += `<span> ${data[count].customer_name}</span>`
                        html += `</li>`
                        html += `<li>`
                        if (page === 'pilot_account' || page === 'delivery_order') {
                            html += `<i class="fas fa-biking fa-fw mr-1"></i>`
                            html += `<span class="pilot-name"> ${data[count].pilot_name}</span>`
                        } else {
                            html += `<i class="fas fa-user-tie"></i>`
                            html += `<span> ${data[count].user}</span>`
                        }
                        html += `</li>`

                        html += `<li>`
                        html += `<i class="fas fa-map-marker-alt"></i>`
                        html += `<span> ${data[count].locations.location}</span>`
                        html += `</li>`
                        html += `<li class='orderPrice'>`
                        html += `<i class="fas fa-money-bill-wave"></i>`
                        html += `<span> ${data[count].total}</span>`
                        html += `</li>`

                        html += `</ul>`

                        html += `<div class='box-menu'>`
                        if (page == "to_pilot") {
                            html += `<ul>`
                            html += `
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("add pilot")): ?>
                                        <li data-toggle="modal" data-target="#pilot">
                                            <a href="#">
                                                <i class="fas fa-biking fa-fw"></i>
                                                <span>Pilot</span>
                                            </a>`
                            html += `</li><?php endif; ?>`

                            html += `
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("edite delivery")): ?>
                                        <li>
                                            <a href="<?php echo e(url('menu/Edit_Delivery/${data[count].order_id}')); ?>">
                                                <i class="fas fa-edit fa-fw"></i>
                                                <span>Edit</span>
                                            </a>`
                            html += `</li><?php endif; ?>`

                            html += `
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("remove delivery")): ?>
                                        <li id="Remove_Delivery" class='remove'>
                                            <a href="#">
                                                <i  class="fas fa-trash-alt fa-fw"></i>
                                                <span>Remove</span>
                                            </a>`
                            html += `</li> <?php endif; ?>`
                            html += `</ul>`

                        } else if (page == "pilot_account") {
                            html += `<ul>`
                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("change pilot")): ?>
                                        <li data-toggle="modal" data-target="#pilot">
                                            <a href="#">
                                                <i class="fas fa-biking fa-fw"></i>
                                                <span>Change Pilot</span>
                                            </a>`
                            html += `</li><?php endif; ?>`

                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pay check delivery')): ?>
                                        <li class='done'>
                                            <a href="#">
                                                <i class="fas fa-check fa-fw"></i>
                                                <span> Pay </span>
                                            </a>`
                            html += `</li><?php endif; ?>`
                            html += `
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('print check delivery')): ?>
                                        <li>
                                            <a href="#">
                                                <i class="fas fa-print fa-fw"></i>
                                                <span class="secoundprint"> Print </span>
                                            </a>`
                            html += `</li><?php endif; ?>`
                            html += `</ul>`
                        }

                        html += `</div>`

                        html += `</div>`
                    }
                    $('#box_content').html(html);
                    $('.ordersNum').text(counter)
                    $('.ordersPrice').text(money)
                    $('.pilotValue').text(pilotValue)

                }
            });
        });

        $('#select_pilot').on('change', function() {
            let _token = $('input[name="_token"]').val();
            let html = '';
            let query = $('#select_pilot').val();
            let page = $('#check_page').attr('value');
            $.ajax({
                url: "<?php echo e(route('Search.pilot.delivery')); ?>",
                method: 'post',
                data: {
                    query: query,
                    _token: _token,
                    page: page
                },
                success: function(data) {
                    let total = 0;
                    let money = 0;
                    let counter = 0;
                    let pilotValue = 0;
                    $('.box').remove();
                    for (var count = 0; count < data.length; count++) {
                        counter++
                        money += data[count].total
                        pilotValue += data[count].locations.pilot_value
                        html += `<div class='box pilot' box_id="${data[count].order_id}">`

                        html += `<ul class="list-unstyled box-list">`
                        html += `<li>`
                        html += `<i class="fas fa-hashtag"></i>`
                        html += `<span class="order_id"> ${data[count].order_id}</span>`
                        html += `</li>`

                        html += `<li>`
                        html += `<i class="fas fa-user"></i>`
                        html += `<span> ${data[count].customer_name}</span>`
                        html += `</li>`
                        html += `<li>`
                        if (page === 'pilot_account' || page === 'delivery_order') {
                            html += `<i class="fas fa-biking fa-fw mr-1"></i>`
                            html += `<span class="pilot-name">${data[count].pilot_name}</span>`
                        } else {
                            html += `<i class="fas fa-user-tie"></i>`
                            html += `<span> ${data[count].user}</span>`
                        }
                        html += `</li>`

                        html += `<li>`
                        html += `<i class="fas fa-map-marker-alt"></i>`
                        html += `<span> ${data[count].locations.location}</span>`
                        html += `</li>`
                        html += `<li class='orderPrice'>`
                        html += `<i class="fas fa-money-bill-wave"></i>`
                        html += `<span> ${data[count].total}</span>`
                        html += `</li>`

                        html += `</ul>`

                        html += `<div class='box-menu'>`;
                        if (page == "to_pilot") {
                            html += `<ul>`
                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("add pilot")): ?><li data-toggle="modal" data-target="#pilot">
                                            <a href="#">
                                                <i class="fas fa-biking fa-fw"></i>
                                                <span>Pilot</span>
                                            </a>`
                            html += `</li><?php endif; ?>`

                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("edite delivery")): ?><li>
                                            <a href="<?php echo e(url('menu/Edit_Delivery/${data[count].order_id}')); ?>">
                                                <i class="fas fa-edit fa-fw"></i>
                                                <span>Edit</span>
                                            </a>`
                            html += `</li><?php endif; ?>`

                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("remove delivery")): ?><li id="Remove_Delivery" class='remove'>
                                            <a href="#">
                                                <i class="fas fa-trash-alt fa-fw"></i>
                                                <span>Remove</span>
                                            </a>`
                            html += `</li><?php endif; ?>`
                            html += `</ul>`

                        } else if (page == "pilot_account") {
                            html += `<ul>`
                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("change pilot")): ?><li data-toggle="modal" data-target="#pilot">
                                            <a href="#">
                                                <i class="fas fa-biking fa-fw"></i>
                                                <span>Change Pilot</span>
                                            </a>`
                            html += `</li><?php endif; ?>`

                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("pay check delivery")): ?><li class='done'>
                                            <a href="#">
                                                <i class="fas fa-check fa-fw"></i>
                                                <span> Pay </span>
                                            </a>`
                            html += `</li><?php endif; ?>`
                            html += `<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("print check delivery")): ?><li>
                                            <a href="#">
                                                <i class="fas fa-print fa-fw"></i>
                                                <span class="secoundprint"> Print </span>
                                            </a>`
                            html += `</li><?php endif; ?>`
                            html += `</ul>`
                        }
                        html += `</div>`

                        html += `</div>`
                    }
                    $('#box_content').html(html);
                    $('.ordersNum').text(counter)
                    $('.ordersPrice').text(money)
                    $('.pilotValue').text(pilotValue)
                }
            });
        });
    });
    // ######################  ##############################################################

    // ###################### Start Take_Order_Holde #####################################
    $(document).on('click', '#take_order_hold', function() {
        let _token = $('input[name="_token"]').val();
        let order = $(this).parents(".box").find('.order_id').text();
        let op = $('#operation').attr('value');
        let table = null;
        let myBox = $(this).parents('.box');
        let toPilotButton = $(`.delivery-item.to-pilot-btn`).find('span');
        let holdingListButton = $(`.delivery-item.holding-list-btn`).find('span');
        let NotificationArray = $('.notification-num');

        Swal.fire({
            title: 'Are you sure?',
            text: "You Want To Take Order!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Take Order!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('take.order.hold.delivery')); ?>",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data: {
                        _token: _token,
                        table: table,
                        op: op,
                        order: order,
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            $('#summary_hold').click();
                            if (op !== 'TO_GO') {
                                myBox.remove();
                            } else {
                                myBox.attr('id', 'box-removeing');
                            }
                            $("body").removeClass("blur");
                            toPilotButton.text(parseInt(toPilotButton.text()) + 1);
                            holdingListButton.text(parseInt(holdingListButton.text()) - 1);
                            NotificationArray.each(function() {
                                if ($(this).text() == 0) {
                                    $(this).addClass('del')
                                } else {
                                    $(this).removeClass('del')
                                }
                            });
                            $('#new_order').attr('value', order);
                        }
                    },
                });
            }
        });
    });

    $(document).on('click', '#summary_hold', function(e) {
        let _token = $('input[name="_token"]').val();
        e.preventDefault();
        let table = null;
        let order = $('.box.focused').attr('box_id') || $('#pilot').attr('order_id') || $(this).attr('order');
        let myModal = $("#pay-model");
        let totalPrice = $('.box.focused').find('.box-list li').last().children('span').text() || null;
        let bankRatio = myModal.find('#bank-ratio');

        if (window.location.href.indexOf("TO_GO") > -1) {
            $('#take_order').click();
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('Pay.check')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                table: table,
                totalPrice: totalPrice,
                order: order,
            },
            success: function(data) {
                let html = '';
                let allquan = 0;
                let alltotal = 0;
                let allsummary = 0;
                let allnew = 0;
                let bank = 0;
                if (data.status == true) {
                    if (data.type == 'credit') {
                        $('#credit-tab').click();
                        $('#visa-price').val(data.visa)
                    } else if (data.type == 'cash') {
                        $('#cash-tab').click();
                        $('#cash-price').val(data.visa)
                    } else if (data.type == 'hospitality') {
                        $('#hospitality-tab').click();
                    }
                    html += `<li>`
                    for (var count = 0; count < data.data.length; count++) {
                        allquan = +allquan + +data.data[count].quantity
                        alltotal += +data.data[count].total + +data.data[count].total_extra + +data.data[count].price_details - +data.data[count].total_discount

                        myModal.modal('show')

                        html += `<div>`
                        html += `<span>${data.data[count].quantity}<span style='color:var(--price-color)'> | </span>${data.data[count].name}</span>`
                        html += `<span>${+data.data[count].total + +data.data[count].total_extra + +data.data[count].price_details - +data.data[count].total_discount}</span>`
                        html += `</div>`

                    }
                    bank = data.bank_ratio
                    allsummary = parseFloat(+alltotal + +data.service[0].service + +data.service[0].tax - parseFloat(data.discount))
                    html += `</li>`
                    myModal.find('.summary ul').prepend(html);
                    myModal.find('.summary .last-item .items-quant').html(allquan);
                    myModal.find('.summary .last-item .summary-total').html(alltotal.toFixed(2));
                    myModal.find('.summary .last-item .summary-tax').html(parseFloat(data.service[0].tax).toFixed(2));
                    myModal.find('.summary .last-item .summary-service').html(parseFloat(data.service[0].service).toFixed(2));
                    myModal.find('.summary .last-item .summary-mincharge').html(parseFloat(data.min_charge).toFixed(2));
                    myModal.find('.summary .last-item .summary-discount').html(parseFloat(data.discount).toFixed(2));
                    myModal.find('.summary .summary-bank').html(data.value_bank);
                    allnew = allsummary
                    if (data.min_charge > allsummary) {
                        allnew = data.min_charge
                    }
                    bankRatio.val(parseFloat(data.bank_ratio).toFixed(2));
                    bankRatio.attr('data-allnew', parseFloat(allnew).toFixed(2));
                    myModal.find('.summary .last-item .all-total').html(parseFloat(allnew).toFixed(2));
                    myModal.find('.summary-price').each(function() {
                        $(this).html(parseFloat(allnew).toFixed(2));
                    })
                    myModal.find('.input-ser').each(function() {
                        if ($('#ser-check').is(':checked')) {
                            $(this).val(parseFloat(data.service[0].service_ratio));
                            $(this).prop('disabled', false)
                        } else {
                            $(this).val(0);
                            $(this).prop('disabled', true)
                        }

                    });
                    myModal.attr('totVal', $('#total-price').attr('totval'));
                    $('#ser-check').attr('dis') == '0' ? myModal.attr('status', 'without') : myModal.attr('status', 'with')
                } else if (data.status == "min") {
                    Swal.fire({
                        title: 'The operation is wrong',
                        html: `The MinCharge is ${data.min_charge} You Must Pay <span class='text-danger'>${data.rest}</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Pay',
                        cancelButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#total-price').text(data.min_charge);
                            $('#summary_check').click();
                        }
                    });
                } else if (data.status == "empty_order") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Order Not Found',
                    });
                }
            },
        });
    });
    // ############################### Start Print Check #############################

    $('#printcheck_hold').on('click', function() {
        let summaryTotal = $('.summary-total').text();
        let summaryService = $('.summary-service').text();
        let summaryTax = $('.summary-tax').text();
        let allTotal = $('.tab-pane.active .summary-price').text();
        let summaryDelivery = $('.summary-delivery').text();
        let summaryDiscount = $('.summary-discount').text();
        let method_bay = $(this).parents('.modal').find('.tab-pane.active').attr('id');
        let Price = $(this).parents('.modal').find('.tab-pane.active .price-value').val();
        let Rest = $(this).parents('.modal').find('.tab-pane.active .price-rest').text();
        let order = $('#new_order').attr('value') || $('#box-removeing').attr('box_id');
        let serButton = $('#ser-check');
        let myModal = $(this).parents('.modal')
        let serVal = myModal.find('.tab-pane.active .input-ser').val();
        let device = $('#device_id').val();
        let table = $('#table_id').attr('value');
        let operation = $('#operation').attr('value');
        let bank_value = $('.summary-bank').text() || 0;
        let _token = $('input[name="_token"]').val();
        let toGoButton = $('.nav-item.nav-togo').find('.notification-num');
        let toGoHoldingList = $('.holdingList').find('.notification-num');
        let ordersNum = $('.ordersNum');
        let ordersPrice = $('.ordersPrice');
        let orderPrice = $('#box-removeing').find('li.orderPrice span');

        serButton.attr('sertax', serVal);

        $.ajax({
            url: "<?php echo e(route('print.check')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                order: order,
                bank_value: bank_value,
                service: summaryService,
                tax: summaryTax,
                subtotal: summaryTotal,
                discount: summaryDiscount,
                total: allTotal,
                method_bay: method_bay,
                price: Price,
                rest: Rest,
                device: device,
                table: table,
                serviceratio: serVal,
                Delivery: summaryDelivery,
                operation: operation
            },
            success: function(data) {
                // CalcTotalCheck();
                myModal.find('.input-ser').each(function() {
                    $(this).val(serVal)
                })
                $('#pay-model').modal('hide')
                if (operation == "Table") {
                    location.href = '/webpoint/menu/Show_Table'
                } else if (operation == "Delivery") {
                    $('#new_order').attr('value', '')
                    $('#Edit_customer').attr('value', 'New_customer')
                    $('#Customer-model').modal('show')
                    $('.orderCheck').last().text('')
                    $('.cusName').last().text('')
                    $('#total-price').text('0.00')
                    $('#dis-val-check').text('0.00')
                    $('#services-value').text('0.00')
                    $('#tax-value').text('0.00')
                    $('.items-num').text('0')
                    $('.table-body').children().each(function() {
                        $(this).remove();
                    })
                    $('#box-removeing').attr('id', '');
                } else if (operation == "TO_GO") {
                    if ($("#check_page").attr('value') == 'hold_togo') {
                        toGoHoldingList.removeClass('del').text(parseInt(toGoHoldingList.text()) - 1);
                        if (toGoHoldingList.text() == 0) {
                            toGoHoldingList.addClass('del')
                        }
                        toGoButton.removeClass('del').text(parseInt(toGoButton.text() || 0) - 1);
                        if (toGoButton.text() <= 0) {
                            toGoButton.addClass('del')
                        }
                        ordersNum.text(parseInt(ordersNum.text()) - 1);
                        ordersPrice.text((parseFloat(ordersPrice.text()) - parseFloat(orderPrice.text())).toFixed(2));
                        $('#box-removeing').remove();
                    }
                }
                if (window.location.href.indexOf("TO_GO") > -1) {
                    $('.check .table-body').children().remove();
                    $('.orderCheck').children().last().text('');
                    $('#new_order').attr('value', '');
                    CalcTotalCheck();
                }

                let holdCheck = $('#check_hold').attr('value');
                if (operation == 'TO_GO' && holdCheck == '0') {
                    location.href = '/webpoint/menu/New_Order/TO_GO'
                }

            }
        });
    });
    // ############################### End Print Check #############################

    // ###################### End Take_Order_Holde #####################################
    $(document).on('click', '.done', function(e) {
        e.preventDefault();
        e.stopPropagation()
        let Box = $(this).parents(".box");
        Box.attr('id', 'box-removeing');
        // $('#printcheck_hold').addClass('d-none')
        $('#paycheck_del').removeClass('d-none')
        $('#summary_hold').click();
        $('#pay-model').find('.checkout').removeClass('d-none');
    });

    //  ############################## Start End Table #############################
    $('#paycheck_del').on('click', function() {
        let _token = $('input[name="_token"]').val();
        let summaryTotal = $('.summary-total').text();
        let summaryService = $('.summary-service').text();
        let summaryTax = $('.summary-tax').text();
        let summaryDiscount = $('.summary-discount').text();
        let allTotal = $('.tab-pane.active .summary-price').text();
        let method_bay = $(this).parents('.modal').find('.tab-pane.active').attr('id');
        let Price = $(this).parents('.modal').find('.tab-pane.active .price-value').val();
        let Rest = $(this).parents('.modal').find('.tab-pane.active .price-rest').text();
        let order = $('.box.focused').attr('box_id') || $('#box-removeing').attr('box_id');
        let serButton = $('#ser-check');

        let summaryDelivery = $('.summary-delivery').text();
        let myModal = $(this).parents('.modal')
        let serVal = myModal.find('.tab-pane.active .input-ser').val();
        let device = $('#device_id').val();
        let table = $('#table_id').attr('value');
        let operation = $('#operation').attr('value');
        let bank_value = $('.summary-bank').text() || 0;

        serButton.attr('sertax', serVal);


        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo e(route('Pay.check.money')); ?>",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data: {
                        _token: _token,
                        order: order,
                        service: summaryService,
                        tax: summaryTax,
                        subtotal: summaryTotal,
                        discount: summaryDiscount,
                        total: allTotal,
                        bank_value: bank_value,
                        method_bay: method_bay,
                        price: Price,
                        rest: Rest,
                        operation: operation,
                        device: device,
                        table: table,
                        serviceratio: serVal,
                        Delivery: summaryDelivery
                    },
                    success: function(data) {

                        let deliveryButton = $('.nav-item.nav-delivery').find('.notification-num');
                        let pilotAcc = $('.pilot-acc').find('.notification-num');
                        let ordersNum = $('.ordersNum');
                        let ordersPrice = $('.ordersPrice');
                        let orderPrice = $('#box-removeing').find('li.orderPrice span');

                        pilotAcc.text(parseInt(pilotAcc.text()) - 1);
                        deliveryButton.text(parseInt(deliveryButton.text()) - 1);
                        if (pilotAcc.text() == 0) {
                            pilotAcc.addClass('del')
                        };
                        if (deliveryButton.text() == 0) {
                            deliveryButton.addClass('del')
                        };
                        ordersNum.text(parseInt(ordersNum.text()) - 1);
                        ordersPrice.text((parseFloat(ordersPrice.text()) - parseFloat(orderPrice.text())).toFixed(2));
                        $('#box-removeing').remove();
                        $('#pay-model').modal('hide');
                    }
                });
            }
        });
    });
    //  ############################## End End Table ###############################
    //  ############################## Start Pay Modal ###############################

    $('body').on('click', '#credit-tab', function() {
        $('#cash-price').val('')
    });

    $('body').on('click', '#cash-tab', function() {
        let myModal = $("#pay-model");
        let total = myModal.find('#bank-ratio').attr('data-allnew');
        $('#visa-price').val('')
        myModal.find('.all-total').text(total);
        $('#credit-total-price').text(total);
        myModal.find('.summary-bank').text('0.00');
    });

    $(document).on('input', '.input-ser', function() {
        let bankRatio = myModal.find('#bank-ratio');
        bankRatio.attr('data-allnew', total.toFixed(2));
        if ($(this).attr('id') == 'visa-services') {
            $('#visa-price').val('');
            $('.summary-bank').text('0.00');
        }
    });
    //  ############################## End Pay Modal ###############################
    // ############################### Start Change Visa Value ######################
    $('body').on('input', '#visa-price', function() {
        let bankRatio = $('#bank-ratio');
        let bankValue = ($(this).val() * (bankRatio.val() / 100));
        bankRatio.attr('data-bank', bankValue.toFixed(2));
        $('.summary-bank').text(bankRatio.attr('data-bank'));
        let total = Number(bankRatio.attr('data-allnew')) + Number(bankRatio.attr('data-bank'));
        $('.summary .all-total').text(total.toFixed(2));
        $('#credit-total-price').text(total.toFixed(2));
    });
    // ############################### End Change Visa Value ######################

    $('#search_order').on('click', function() {
        let orderSearch = $(this).siblings('input').val();
        $('.box').each(function() {
            let orderNum = $(this).attr('box_id');
            let serialNum = $(this).attr('box_serial');
            if (serialNum.indexOf(orderSearch)) {
                $(this).addClass('d-none')
            } else {
                $(this).removeClass('d-none')
            }
        });
    });
</script>
<?php /**PATH F:\BackEnd\htdocs\webpoint\resources\views/includes/menu/delivery_order.blade.php ENDPATH**/ ?>
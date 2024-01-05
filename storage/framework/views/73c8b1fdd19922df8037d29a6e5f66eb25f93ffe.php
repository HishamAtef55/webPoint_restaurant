
<script>
    let orderNumber = $('#new_order').attr('value');
    let newOperation = $('#operation').attr('value');
    if (orderNumber == '' && newOperation == 'Table') {
        $('.calculator__keys .take-pay-btn').addClass('pay-delivery')
    }
// Check No OF Device wire
// This Function To Calculate Price Item With Discount , Details And Extra
function clacTotalItem(itemParent) {
    let priceItem    = parseFloat(itemParent.find('.price').text());
    let quantItem    = parseFloat(itemParent.find('.num').val());
    let totalItem    = itemParent.find('.total input');
    let extraItem    = Array.from(itemParent.find('.extra .extra-price'));
    let detailsItem  = Array.from(itemParent.find('.details .details_price'));
    let discount     = itemParent.find('.discount .discount-price');
    let discountItem = parseFloat(discount.text()) || 0;
    let discountItemValue;


    // if (discount.attr('type') == 'Ratio') {
    //     discountItemValue = (discountItem / 100) * priceItem * quantItem;
    // } else {
    // }
    discountItemValue = discountItem;

    let priceItemValue    = priceItem * quantItem;

    let extraItemValue = 0;
    extraItemValue = extraItem.reduce(function(extra, item){
        let extraPrice = parseFloat(item.textContent);
        return extra + extraPrice;
    }, 0);

    let detailsItemValue  = 0;
    detailsItemValue = detailsItem.reduce(function(details, item){
        let detailsPrice = parseFloat(item.textContent);
        return details + detailsPrice;
    }, 0);

    let TotalItemWithoutDis = (priceItemValue + extraItemValue + detailsItemValue);
    let TotalItemValue    = (priceItemValue + extraItemValue + detailsItemValue) - discountItemValue;

    itemParent.attr('value', TotalItemWithoutDis);
    totalItem.val(TotalItemValue);
}

// Function To Create Discount On All Check
function createDisCheck(type, input, total, disvalchek, disOption) {
    let priceAll = (total + parseFloat(disvalchek));
    let disVal = null;

    if (type === 'Ratio') {
        if (input !== '') {
            disVal = (parseFloat(input) / 100) * priceAll;
        } else {
            disVal = (disOption / 100) * priceAll
        }
    } else {
        if (input !== '') {
            disVal = parseFloat(input);
        } else {
            disVal = parseFloat(disOption);
        }
    }

    // $('#total-price').text(`${(priceAll - disVal).toFixed(2)}`);
    $('#dis-val-check').text(disVal.toFixed(2));
    return priceAll - disVal
}

// Create Services And Tax Without Discount On Check
function createSerTaxWithoutDis(checkBtn, element, ser) {
    let totCheckWithouDis = 0;
    $('.item-parent').each(function() {
        totCheckWithouDis += parseFloat($(this).attr('value'));
    });

    let serTaxVal = (totCheckWithouDis + ser) * (parseFloat(checkBtn.attr('serTax')) / 100);

    element.text(serTaxVal.toFixed(2));

    return serTaxVal;
}

function createTaxWithDis(checkBtn, element) {
    let totalPrice = $('.total-input');
    let totItems = 0;
    let disCheck = $('#dis-val-check').text();
    let serVal = $('#services-value').text();

    totalPrice.each(function() {
        totItems += parseFloat($(this).val())
    });

    let totalCheckPrice = (totItems - parseFloat(disCheck)) + parseFloat(serVal);

    let serTaxVal = totalCheckPrice * (parseFloat(checkBtn.attr('serTax')) / 100);

    element.text(serTaxVal.toFixed(2));

    return serTaxVal;
}

function createTax() {
    let taxBtn = $('#tax-check');
    let taxValDiv = $('#tax-value');
    let serVal = $('#services-value').text();
    let taxVal = 0;

    if (taxBtn.attr('dis') == 0) {
        let totCheckWithouDis = 0;
        $('.total-input').each(function() {
            totCheckWithouDis += parseFloat($(this).val())
        });

        taxVal = (totCheckWithouDis +  parseFloat(serVal)) * (parseFloat(taxBtn.attr('serTax')) / 100);

        taxValDiv.text(taxVal.toFixed(2));
    } else {
        taxVal = createTaxWithDis(taxBtn, taxValDiv);
    }
    return taxVal
}


// Function To Calculate Items Length And Total Price in End Check
function CalcTotalCheck() {
    let totalItem = Array.from($('.table-item .num'));
    let totalPrice = Array.from($('.total-input'));
    let priceSum = 0;
    let itemsNum = 0;
    let disType = $('.check').attr('dis-type');
    let disValue = $('.check').attr('dis-val');
    let disValCheck  = $('#dis-val-check');
    let servicesBtn = $('#ser-check');
    let servicesValDiv = $('#services-value');

    totalPrice.forEach(price => {
        priceSum += parseFloat(price.value);
    });

    totalItem.forEach(item => {
        itemsNum += parseFloat(item.value);
    });

    let totValue = 0
    $('.item-parent').each(function() {
        totValue += parseFloat($(this).attr('value'));
    });



    disValCheck.text(0)

    let dis = createDisCheck(disType, disValue, priceSum, disValCheck.text());

    let servicesVal = createSerTaxWithoutDis(servicesBtn, servicesValDiv, 0);

    let taxVal = createTax();

    $('#total-price').text(`${(dis + servicesVal + taxVal).toFixed(2)}`);
    $('#total-price').attr('totVal', totValue);
    $('.items-num').html(itemsNum);
}

let _token           = $('input[name="_token"]').val();
let Order_Number     = $('#new_order').attr('value');
let Quantity_item    = $('#num_quant');


$('#set_table').on('click', function() {
    let tableNum = $(this).parents('.modal').find('#tableNum').val();
    $('#togo_table').attr('value',tableNum);
    $('#table_num_div').find('button').addClass('d-none');
    $('#table_num_div').find('span:last-child').text(tableNum);
    $(this).parents('.modal').modal('hide')
});





$(document).ready(function(){
    $('#change_menu').on('click', function(e)
    {
        e.preventDefault();
        let branch = $(this).attr('branch_id');
        let menu   = $('#select_change_menu').val();

        $.ajax({
            url: "<?php echo e(route('change.menu')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token : _token,
                    branch : branch,
                    menu   : menu
                },
            success: function (data)
            {
                $('#pills-tabContent').hide();
                $('#pills-tab').find('.nav-item').remove();
                $('#subgroupnew').remove();
                let html='';
                for(let count=0 ; count < data.length ; count++)
                {
                    html+='<li class="nav-item" role="presentation">';
                        html+='<a class="nav-link" id="'+data[count].name+'-tab" value="'+data[count].id+'"  data-toggle="pill" href="#'+data[count].name+'">';
                            html+='<i class="fas fa-mug-hot fa-lg"></i>';
                            html+='<span>'+data[count].name+'</span>';
                        html+='</a>';
                    html+='</li>';
                }
                $('#pills-tab').html(html);
                $('#menus').modal('hide');
                $(".menu .sub-menu .nav-sub").find(".nav-item").first().find('.nav-link').click();
            }
        });
    });
});


$('body').on('click', '.nav-sub li a', function(e){
        // $('#pills-tabContent').hide();
        e.preventDefault();
        let group = $(this).attr('value');
            $.ajax({
                url: "<?php echo e(route('getnewsub.menu')); ?>",
                method: 'post',
                enctype: "multipart/form-data",
                data:
                    {
                        _token : _token,
                        group  : group,
                    },
                success: function (data)
                {
                    let html = '';
                    for (var count = 0 ; count < data.length ; count ++) {
                        html+=`<li SubNewID='${data[count].id}'>${data[count].name}</li>`
                    }
                    $('#subgroupnew ul').removeClass('hide').html(html);
                    $('#newrow').addClass('hide');
                }
            });


});


$('body').on('click', '#subgroupnew li', function(e){
        $('#pills-tabContent').hide();
        let op = $('#operation').attr('value');
        e.preventDefault();
        let Sub_ID = $(this).attr('SubNewID');
        let parent = $(this).parent()
        $.ajax({
            url: "<?php echo e(route('import.items')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {ID: Sub_ID, _token: _token},
            success: function (data) {
                $('#pills-tabContent').show();
                let html = '';
                let price = 0;
                for (var count = 0 ; count < data.length ; count ++) {
                    html +='<div class="col mywidth">';

                        html +='<div class="card" details_no="'+data[count].details.length+'" value="'+data[count].id+'" data-animate="animate__animated animate__fadeInUp" data-delay="0.1s">';

                            html += `<div class='d-none' id='details${data[count].id}'>`;

                                for(let i = 0; i < data[count].details.length ; i ++) {
                                    html += `<span class='d-none details_info' id_detail="${data[count].details[i].id}" id='detail_${data[count].details[i].id}' name='${data[count].details[i].name}' section='${data[count].details[i].pivot.section}' max='${data[count].details[i].pivot.max}' price='${data[count].details[i].pivot.price}'></span>`;
                                }

                            html += `</div>`;
                            html +=`<div class="d-none">`
                                for(let b = 0 ; b < data[count].barcode.length ; b++){
                                    html += `<span class='d-none barcode_info' id_barcode="${data[count].barcode[b].barcode}"></span>`;
                                }
                            html +=`</div>`

                            html += `<div class='card-background'></div>`;

                            html +=`<div class="card-image">`;
                                if(data[count].image != 'not_found.jpg'){
                                    html +='<img src="<?php echo e(URL::asset('control/images/items')); ?>/'+data[count].image+'" class="card-img-top">';
                                }

                            html +='</div>';

                            html +='<div class="card-body" value="'+data[count].name+'">';
                                html +='<h5 class="card-title text-center">'+data[count].name+'</h5>';
                                if(data[count].note != null){
                                    html +=`<p class="card-text">${data[count].note}</p>`;
                                }
                            html +='</div>';
                            if(op == "Table"){
                                price = data[count].price;
                            }else if(op == "Delivery"){
                                price = data[count].dellvery_price;
                            }else if(op == "TO_GO"){
                                price = data[count].takeaway_price;
                            }
                            html += '<div class="card-footer" value="' + price + '">';
                        if(data[count].price != null) {
                            html += '<div>';
                            html += '<i class="fas fa-money-bill-alt"></i>';
                            html += '<span class="price">' + price + ' &pound; </span>';
                            html += '</div>';
                        }
                            if(data[count].calories != null)
                            {
                                html +='<div>';
                                html +='<i class="fas fa-fire"></i>';
                                html +=`<span>${data[count].calories}</span>`;
                                html +='</div>';
                            }



                    if(data[count].wight != null) {
                        html += '<div>';
                        html += '<i class="fas fa-weight-hanging"></i>';
                        html += '<span>' + data[count].wight + ' ' + data[count].unit + '</span>';
                        html += '</div>';
                    }

                            html +='</div>';

                        html +='</div>';
                    html +='</div>';
                }
                $('#newrow').removeClass('hide').html(html);
                parent.addClass('hide')
            }
        });
});



$('body').on('dblclick', '.card', function() {
        let Item_ID          = $(this).attr('value');
        let Table_ID         = $('#table_id').attr('value');
        let togo_table         = $('#togo_table').attr('value');
        let Order_Number     = $('#new_order').attr('value');
        let operation        = $('#operation').attr('value');
        let Item_Name        = $(this).children('.card-body').attr('value');
        let Item_Price       = $(this).children('.card-footer').attr('value');
        let Order_Number_dev = $('#device_id').val();
        let Quantity         = $('#quantity').val();
        let subgroup_name    = $('.nav-sub .nav-link.active').find('span').text();
        let subgroup_id    = $('.nav-sub .nav-link.active').attr('value');
        Swal.fire({
            position: 'center-center',
            icon: 'success',
            title: 'Item Added',
            showConfirmButton: false,
            timer: 250
        });
        $('#newrow').addClass('no-click')

        $.ajax({
            url: "<?php echo e(route('wait.items')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
            {
                _token            :_token,
                Order_Number_dev  :Order_Number_dev,
                Item_ID           :Item_ID,
                Order_Number      :Order_Number,
                Item_Name         :Item_Name,
                Item_Price        :Item_Price,
                Table_ID          :Table_ID,
                Quantity          :Quantity,
                operation         :operation,
                subgroup_id       : subgroup_id,
                subgroup_name     : subgroup_name,
                togo_table     : togo_table
            },
            success: function (data) {
                $('#new_order').attr('value',data.order)
                $('.orderCheck').find('span:last-child').text(data.order)
                CalcTotalCheck();
                $('.table-body').stop().animate({
                    scrollTop: $('.table-body').prop("scrollHeight")
                }, 500);
                if(operation == "Table"){
                    $('.summary_check_btn').hide();
                    $('.take-pay-btn').addClass('pay-delivery')
                    $('.discount-all-check').addClass('d-none');
                }
                setTimeout(() => {
                    $('#newrow').removeClass('no-click')
                }, 500);
            }
        });
});
$('body').on('click', '#add_item', function() {
        let Table_ID         = $('#table_id').attr('value');
        let Order_Number     = $('#new_order').attr('value');
        let printer          = $('#chose_printer_item').val();
        let operation        = $('#operation').attr('value');
        let Item_Name        = $(this).parents('.modal').find('#itemName').val();
        let Item_Price       = $(this).parents('.modal').find('#itemPrice').val();
        let Quantity         = $(this).parents('.modal').find('#itemQty').val();
        let Order_Number_dev = $('#device_id').val();
        let subgroup_name    = $('.nav-sub .nav-link.active').find('span').text();
        let subgroup_id    = $('.nav-sub .nav-link.active').attr('value');
        let statusItem = 'openItem';
        let Item_ID = 0;
        Swal.fire({
            position: 'center-center',
            icon: 'success',
            title: 'Item Added',
            showConfirmButton: false,
            timer: 250
        });
        $('#newrow').addClass('no-click')

        $.ajax({
            url: "<?php echo e(route('wait.items')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
            {
                _token            :_token,
                Order_Number_dev  :Order_Number_dev,
                Order_Number      :Order_Number,
                Item_Name         :Item_Name,
                Item_Price        :Item_Price,
                Table_ID          :Table_ID,
                Quantity          :Quantity,
                operation         :operation,
                subgroup_id       :subgroup_id,
                subgroup_name     :subgroup_name,
                statusItem        :statusItem,
                Item_ID           :Item_ID,
                printer           :printer
            },
            success: function (data) {
                $('#new_order').attr('value',data.order)
                $('.orderCheck').find('span:last-child').text(data.order);
                let number_div = 0;
                if ($(".item-parent").length) {
                    number_div = $(".item-parent")
                        .last()
                        .attr("item_id");
                    number_div++;
                } else {
                    number_div = 1;
                }

                let parent = $(
                        `<div class='item-parent' item_id='${number_div}' item='${data.item_id}'> </div>`
                    ),
                    item = $(`<div class='table-item'> </div>`),
                    title = $(
                        `<div class='product-name'> ${Item_Name} </div>`
                    ),
                    quant = $(
                        `<div> <input type='number' value='${Quantity}' min='1' step="0.1" class='num' disabled /> </div>`
                    ),
                    cach = $(`<div class='price'>${Item_Price}</div>`),
                    total = +Quantity * +Item_Price,
                    colTotal = $(
                        `<div class='total'> <input type='number' value='${total}' min='1' disabled class='total-input' /> </div>`
                    ),
                    trashButton = $(
                        `<button class='btn btn-danger trash' id_order='${number_div}'> <i class='fas fa-trash-alt text-white'></i> </button>`
                    );

                parent.attr("value", total);
                parent.appendTo(".table-body");
                item.appendTo(parent);
                title.appendTo(item);
                quant.appendTo(item);
                cach.appendTo(item);
                colTotal.appendTo(item);
                trashButton.appendTo(item);

                let menu = $(`<div class='item-menu'> </div>`),
                    comment = $(
                        `<button class='btn' id="comment" id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="comment"> <i class='fas fa-comment-dots'></i> <input type="hidden" value="" class="comment_content"> Comment </button>`
                    ),
                    extra = $(
                        `<button class='btn'  id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="extra"> <i class='fas fa-plus-square'></i> Extra </button>`
                    ),
                    without = $(
                        `<button class='btn' id="without" id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="without"> <i class='fas fa-minus-square'></i> Without </button>`
                    );
                    discount = $(
                        `<button class='btn' id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="discount"> <i class='fas fa-tags'></i> Discount </button>`
                    );

                menu.prependTo(item);
                comment.appendTo(menu);
                extra.appendTo(menu);
                without.appendTo(menu);
                discount.appendTo(menu);

                quantInput.val("");


                CalcTotalCheck();
                $('.table-body').stop().animate({
                    scrollTop: $('.table-body').prop("scrollHeight")
                }, 500);
                if(operation == "Table"){
                    $('.summary_check_btn').hide();
                    $('.take-pay-btn').addClass('pay-delivery')
                    $('.discount-all-check').addClass('d-none');
                }
                setTimeout(() => {
                    $('#newrow').removeClass('no-click')
                }, 500);
            }
        });
});



$('body').on('click','.table-item .trash',function (e) {
    e.preventDefault();
    e.stopPropagation();
    let Order_ID      = $(this).attr('id_order');
    let table_id      = $('#table_id').attr('value');
    let Order_Number  = $('#new_order').attr('value');
    let tr            = $('#operation').attr('value');
    let device          = $('#device_id').val();
    let alaa = $(this)


    $('#newrow').addClass('no-click')
    $.ajax({
        url: "<?php echo e(route('delete.order')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data: {device:device,tr:tr,table_id:table_id,Order_ID: Order_ID,Order_Number:Order_Number, _token: _token},
        success: function (data) {

            alaa.parents('.item-parent').addClass('fall').fadeOut(400, function() {
                $(this).remove();
            });

            setTimeout(function() {
                if ($('.table-body').children().length == 0) {
                    $('#new_order').attr('value', '');
                    $('.orderCheck span:last-child').text('')
                }
                CalcTotalCheck()
                $('#newrow').removeClass('no-click');
            }, 500);
        }
    });



});



$('body').on('click','#save_comment',function () {
        let Order_Number     = $('#new_order').attr('value');
        let Order_ID     = $(this).parents('#item-menu-model').attr('id_order');
        let textComment  = $('#text_area_comment');
        let itemParent   = $(`.item-parent[item_id=${Order_ID}]`);
        itemParent.find('.item-menu input.comment_content').val(textComment.val());

        $.ajax({
            url: "<?php echo e(route('comment.order')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {Order_ID:Order_ID,Order_Number:Order_Number,text:textComment.val(), _token: _token},
            success: function (data) {
                let html ='';
                if(data.status == 'true'){
                    if (itemParent.find('.comment').length === 0) {
                        html = `<div class="comment">
                                    <div class="comment-name">
                                        <span>Comment</span>
                                        <pre>${textComment.val()}</pre>
                                    </div>
                                </div>`;
                        itemParent.append(html);
                    } else {
                        itemParent.find('.comment span').last().text(textComment.val());
                    }
                    $('#item-menu-model').modal('hide');
                    textComment.val('');
                }
            }
        });
});



function createWithout(parent, number, array) {
    let num = parent.find('input.num').val();
    parent.find('.without').each(function(){
        $(this).remove();
    })
    for (let i = 0; i < number; i++) {
        let withoutParent = $(`<div class='without'></div>`),
            withoutName = $(`<div class='without-name'><span>Without</span><span>${array[i].name}</span></div>`);

        withoutParent.appendTo(parent);
        withoutName.appendTo(withoutParent);
    }
}

$('body').on('click','#save_without',function () {

    let Order_ID     = $(this).parents('#item-menu-model').attr('id_order');
    let Order_Number     = $('#new_order').attr('value');
    let extraParent  = $(`.item-parent[item_id = ${$(this).attr('number_order')}]`);
    let Item = extraParent.attr('item');
    let withoutChecked = Array.from($(this).parents('#nav-without').find('input[type="checkbox"]:checked'));
    let subgroup_id    = $('.nav-sub .nav-link.active').attr('value');
    let new_quant = Quantity_item.val();
    let without_material = [];
    withoutChecked.forEach(without => {
        without_material.push({
            id: $(without).attr('id_without'),
            material_id: $(without).parent().attr('without'),
            name: $(without).next('label').find('span').text()
        })
    });

    let quantOld = extraParent.find('.num');
    let quantNew = $(this).parents('#item-menu-model').find('#num_quant');

    function increaseQuant(big, small) {
        if (big.val() != small.val()) {
            big.val(big.val() - small.val());
            createWithout(cloneNewItem(extraParent, small.val()), withoutChecked.length, without_material);
            clacTotalItem(extraParent);
        } else {
            createWithout(extraParent, withoutChecked.length, without_material);
            clacTotalItem(extraParent);
        }

    }

    increaseQuant(quantOld, quantNew);

    $.ajax({
        url: "<?php echo e(route('without.order')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data: {Order_ID,Order_Number, _token, without_material,Item,subgroup_id,new_quant},
        success: function (data) {
            if(data.status === true) {
                $('#item-menu-model').modal('hide');
            }
        }
    });
});



$(document).on('click', '#details-model input[type="checkbox"]', function() {
    let checked = $('#details-model input[type="checkbox"]:checked');
    let max     = $(this).parents('.tab-pane').attr('max');

    console.log($('#details-modal').find('#dismiss_modal'))
    if (checked.length >= 1) {
        $('#dismiss_modal').removeClass('d-none')
    } else {
        $('#dismiss_modal').addClass('d-none')
    }

    if (checked.length == max) {
        $('#details-model').modal('hide');
    }

});

let detailsPrice = [];
let detailsName  = [];
let detailsId = [];
let detailsArray = [];

function getInfo(checked, priceArray, nameArray, idArray, mainArray, from) {
    checked.forEach(element => {
        idArray.push(element.getAttribute(`id_${from}`));
        nameArray.push(element.nextElementSibling.firstElementChild.innerHTML);
        priceArray.push(element.nextElementSibling.lastElementChild.innerHTML);
        mainArray.push({
            'id'    : idArray[idArray.length - 1],
            'name'  : nameArray[nameArray.length - 1],
            'price' : priceArray[priceArray.length - 1]
        });
    });
    return mainArray
}

function createDetails(parent, number) {
    for (let i = 0; i < number; i++) {
        let num = parent.find('input.num').val();
        let detailsParent = $(`<div class='details'></div>`),
            detailsName = $(`<div class='details_name'><span>Detail</span><span>${detailsArray[i].name}</span></div>`),
            detailsPrice = $(`<div class='details_price'>${detailsArray[i].price * num}</div>`);

        detailsParent.appendTo(parent);
        detailsName.appendTo(detailsParent);
        detailsPrice.appendTo(detailsParent);
    }
}



$('#details-model').on('hidden.bs.modal', function() {

    let detailChecked = Array.from($(this).find('input[type="checkbox"]:checked'));
    // let length = $(`.item-parent`).length;
    let TableID     = $('#table_id').attr('value');
    let Order_Number_dev = $('#device_id').val();
    let Order_Number     = $('#new_order').attr('value');
    getInfo(detailChecked, detailsPrice, detailsName, detailsId, detailsArray, 'detail');
    $(this).find('#dismiss_modal').addClass('d-none')
    $.ajax({
        url: "<?php echo e(route('add.details.wait')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data: {Order_Number_dev:Order_Number_dev,Order_Number:Order_Number,TableID:TableID, _token: _token,detailsArray:detailsArray},
        success: function (data) {
            detailsArray = [];
            CalcTotalCheck();
        }
    });
    let parent = $(`.item-parent:last-child`);
    let number = $('#details-model input[type="checkbox"]:checked').length;

    createDetails(parent, number);

    clacTotalItem(parent);

    $('#card_chose').attr('id', '');

});
$('#details-model').on('shown.bs.modal', function() {
    $(this).find('.nav-link:first-child').click();
});
$(document).on('click', '#details-model .nav-link', function() {
    let max = $(this).attr('href');
    $('#details-model .details-max').text($(max).attr('max'))
});



function createDiscount(parent, name, type, value, number) {
    let disParent = $(`<div class='discount' id='discount_${number}'></div>`),
        disName = $(`<div class='discount-name'><span>Discount</span><span>${name}</span></div>`),
        disVal = null
        if (type == 'Value') {
            disVal = $(`<div class='discount-price' type="${type}">${value}</div>`);
        } else {
            disVal = $(`<div class='discount-price' type="${type}">${value.toFixed(2)}</div>`);
        }

        disParent.appendTo(parent);
        disName.appendTo(disParent);
        disVal.appendTo(disParent);

        clacTotalItem(parent);

}

function createDiscountInModal(name, orderNum, orderId, price, disId) {
    let discount = $(`<div class='discount-item' disId='${disId}'>
                        <div class='item-name'>${name}</div>
                        <button order_no="${orderNum}" item_id ="${orderId}" class='btn btn-danger del_discount_item' discount ="${price}">
                            <i class='fas fa-trash-alt text-white'></i>
                        </button>
                    </div>`);

    let modalParent = $('#discount-on-all-check .discount-list');
    discount.appendTo(modalParent);
}

$('body').on('click','#save_discount_item',function (e) {
    e.preventDefault();

    $('#item-menu-model').modal('hide');
    let new_quant      = Quantity_item.val();
    let typeContent    = $('input[name="type_discount"]:checked').data('value');
    let ID_Discount    = $(`${typeContent}`).find('option:selected').attr('id_discount');
    let Val_Discount   = $(`${typeContent}`).find('option:selected').val();
    let Type_Discount  = $(`${typeContent}`).find('option:selected').attr('type');
    let Input_value    = $(`${typeContent}`).find('input').val();
    let Name_Dis       = $(`${typeContent}`).find('option:selected').attr('name_dis');
    let Order_ID       = $(this).parents('#item-menu-model').attr('id_order');
    let discountParent = $(`.item-parent[item_id="${Order_ID}"]`);
    let Item           = discountParent.attr('item');
    let price          = parseFloat(discountParent.find('.price').text());
    let subgroup_id    = $('.nav-sub .nav-link.active').attr('value');
    let itemName       = discountParent.find('.product-name').text();
    let itemId    = parseInt($('.table-body').children().last().attr('item_id'))
    let op                = $('#operation').attr('value');

    let quantOld = discountParent.find('.num');

    let quantNew = $(this).parents('#item-menu-model').find('#num_quant');

   $(`#discount_${Order_ID}`).remove();
   $(`#discount-on-all-check .discount-item[disid="${Order_ID}"]`).remove();

   function increaseQuant(big, small, disVal) {
        if (big.val() != small.val()) {
            big.val(big.val() - small.val());
            createDiscount(cloneNewItem(discountParent, small.val()), Name_Dis, Type_Discount, disVal, itemId + 1);
            clacTotalItem(discountParent);
        } else {
            createDiscount(discountParent, Name_Dis, Type_Discount, disVal, Order_ID);
            clacTotalItem(discountParent);
        }

    }

    if(Input_value != '')
    {
        Name_Dis      = 'Discount';
        Type_Discount = 'Value';
    }
    let Order_Number     = $('#new_order').attr('value');

    $.ajax({
        url: "<?php echo e(route('Discount.items')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data:
        {
           _token         : _token,
           ID_Discount    :ID_Discount ,
           Val_Discount   :Val_Discount ,
           Type_Discount  :Type_Discount ,
           Input_value    :Input_value ,
           Order_ID       :Order_ID ,
           Order_Number   :Order_Number,
           Name_Dis       :Name_Dis,
           new_quant      :new_quant,
           price          :price,
           Item           :Item,
           subgroup_id    :subgroup_id,
            op            : op
        },
        success: function (data)
        {
            let parent = $(`.item-parent[item_id="${Order_ID}"]`),
                value = null;

            if(Input_value != '') {
                value = Input_value;
            } else {
                value = Val_Discount;
            }
            if(Type_Discount == 'Value' && Input_value == '0')
            {

            }else{
                increaseQuant(quantOld, quantNew, data.discount);
                createDiscountInModal(itemName, Order_Number, Order_ID, value, Order_ID);
                clacTotalItem(parent);
                CalcTotalCheck();
            }


        }
    });

});



$(document).on('click', '.del_discount_item', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let button = $(this)
    let discountItem  = button.parents('.discount-item');
    let Order_Number_ = $('#new_order').attr('value');
    let Order_ID      = button.attr('item_id');
    let Discount      = button.attr('discount');
    let item          = $(`.item-parent[item_id="${Order_ID}"]`);
    let Order_Number     = $('#new_order').attr('value');
    let op             = $('#operation').attr('value');



    Swal.fire({
        title: 'Are you sure?',
        text: "You Want Delete This Discount",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?php echo e(route('delete.discount')); ?>",
                method: 'post',
                enctype: "multipart/form-data",
                data: {Discount:Discount,op:op , Order_ID:Order_ID,Order_Number_:Order_Number_, _token: _token},
                success: function (data) {
                    discountItem.addClass('fall').fadeOut(400, function() {
                        button.remove();
                    });
                    item.find('.discount').remove();
                    clacTotalItem(item);
                    CalcTotalCheck();
                }
            });
        }
    });



});



$(document).on('click', '#save_discount_all',function (e){
    let Order_Number   = $('#new_order').attr('value');
    let typeContent    = $('input[name="discount-type"]:checked').data('value');
    let Type_Discount  = $('input[name="discount-type"]:checked').attr('dis-type');
    let ID_Discount    = $(`${typeContent}`).find('option:selected').attr('id_discount');
    let Val_Discount   = $(`${typeContent}`).find('option:selected').val();
    let Input_value    = $(`${typeContent}`).find('input').val();
    let Name_Dis       = $(`${typeContent}`).find('option:selected').attr('name_dis') || 'discount';
    let totCheckPrice  = $('#total-price');
    let disValCheck    = $('#dis-val-check').text();
    let val_me_all     = $('#total-price').attr('totval');
    let op             = $('#operation').attr('value');


    $.ajax({
        url: "<?php echo e(route('Discount.all')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data:
            {
                _token         : _token,
                ID_Discount    :ID_Discount ,
                Val_Discount   :Val_Discount ,
                Type_Discount  :Type_Discount ,
                Input_value    :Input_value ,
                Order_Number   :Order_Number,
                Name_Dis       :Name_Dis,
                val_me_all     : val_me_all,
                op             : op
            },
        success: function (data)
        {
            if(data.status == 'true')
            {
                let dis = createDisCheck(Type_Discount, Input_value, parseFloat(totCheckPrice.text()), disValCheck, Val_Discount);
                $('#dis-name-check').text(Name_Dis);
                $('.check').attr('dis-type', Type_Discount)
                $('.check').attr('dis-val', Input_value || Val_Discount);
                totCheckPrice.text(`${dis.toFixed(2)}`);
                $('#discount-on-all-check').modal('hide');
                CalcTotalCheck();
            }
        }
    });
});
// =================== Write In Discoun All Ratio Input =============
function selectDisabled(input) {
    if (input.value.length > 0) {
        input.previousElementSibling.setAttribute("disabled", "disabled");
    } else {
        input.previousElementSibling.removeAttribute("disabled");
    }
}







$('#item-menu-model').on('show.bs.modal', function (event) {

    let button           = $(event.relatedTarget); // Button that triggered the modal
    let item             = button.parents('.item-parent').attr('item');
    let itemId           = button.parents('.item-parent').attr('item_id');
    $('#extra-container').empty();
    $('#without-container').empty();
    $.ajax({
        url: "<?php echo e(route('find.extra.item')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data: {item:item, _token: _token},
        success: function (data) {
            let html = '';
            let without = '';
            if(data.status == true)
            {
                for(var count = 0 ; count < data.data[0].extra.length ; count ++)
                {
                    html+='<div class="form-group" item="'+item+'" extra="'+data.data[0].extra[count].id+'">';
                        html+='<input type="checkbox"  id_extra="'+data.data[count].id+'"  id="extra_'+data.data[0].extra[count].id+'">';
                        html+='<label for="extra_'+data.data[0].extra[count].id+'">';
                            html+='<span>'+data.data[0].extra[count].name+'</span>&nbsp; | &nbsp';
                            html+='<span>'+data.data[0].extra[count].pivot.price+'</span>';
                        html+='</label>';
                    html+='</div>';
                }
            }else if(data.status == false)
            {
                for(var count = 0 ; count < data.data.length ; count ++)
                {
                    html+='<div class="form-group" item="'+item+'" extra="'+data.data[count].id+'">';
                        html+='<input type="checkbox" id_extra="'+data.data[count].id+'"   id="extra_'+data.data[count].id+'">';
                        html+='<label for="extra_'+data.data[count].id+'">';
                            html+='<span>'+data.data[count].name+'</span>&nbsp; | &nbsp';
                            html+='<span>'+data.data[count].price+'</span>';
                        html+='</label>';
                    html+='</div>';
                }
            }
            html +='<button id="save_extra" number_order="'+itemId+'" class="btn btn-block bg-success text-white">Save</button>';
            $('#extra-container').html(html);
            for(var count = 0 ; count < data.materilas.length ; count++)
            {
                without+='<div class="form-group" item="'+item+'" without="'+data.materilas[count].material_id+'">';
                    without+='<input type="checkbox" id_without="'+data.materilas[count].id+'" id="without_'+data.materilas[count].material_id+'">';
                    without+='<label for="without_'+data.materilas[count].material_id+'">';
                        without+='<span>'+data.materilas[count].material_name+'</span>';
                    without+='</label>';
                without+='</div>';
            }
            without +='<button id="save_without" number_order="'+itemId+'" class="btn btn-block bg-success text-white">Save</button>';
            $('#without-container').html(without);
        },

    });
});

$('body').on('click', '#save_extra', function() {
    let extraPrice   = [];
    let ExtraName    = [];
    let idExtra      = [];
    let extraArray   = [];
    let extraChecked = Array.from($(this).parents('#nav-extra').find('input[type="checkbox"]:checked'));
    let extraParent  = $(`.item-parent[item_id = ${$(this).attr('number_order')}]`);
    let idItem       = $(this).attr('number_order');
    let Item         = extraParent.attr('item');
    let subgroup_id    = $('.nav-sub .nav-link.active').attr('value');
    let Order_Number     = $('#new_order').attr('value');



    let quantOld = extraParent.find('.num');

    let quantNew = $(this).parents('#item-menu-model').find('#num_quant');

    getInfo(extraChecked, extraPrice, ExtraName, idExtra, extraArray, 'extra');

    function increaseQuant(big, small) {
        if (big.val() != small.val() && extraArray.length !== 0) {
            big.val(big.val() - small.val());
            createExtra(cloneNewItem(extraParent, small.val()), extraChecked.length, extraArray);
            clacTotalItem(extraParent);
        } else {
            createExtra(extraParent, extraChecked.length, extraArray);
            clacTotalItem(extraParent);
        }

    }

    increaseQuant(quantOld, quantNew);

    let new_quant = Quantity_item.val();
    $.ajax({
        url    :"<?php echo e(Route('export.Extra.menu')); ?>",
        method :'post',
        enctype: "multipart/form-data",
        data: {
                _token         :_token,
                Order_Number   :Order_Number,
                extraArray     :extraArray,
                Item           :Item,
                idItem         :idItem,
                new_quant      :new_quant,
                subgroup_id    :subgroup_id
             },
        success: function (data) {
            // createExtra(extraParent, extraChecked.length, extraArray);
            CalcTotalCheck();
        }
    });

    $('#item-menu-model').modal('hide');
});

function createExtra(parent, number, array) {
    let num = parent.find('input.num').val()
    for (let i = 0; i < number; i++) {
        let extraParent = $(`<div class='extra'></div>`),
            extraName = $(`<div class='extra-name'><span>Extra</span><span>${array[i].name}</span></div>`),
            extraPrice = $(`<div class='extra-price'>${array[i].price * num}</div>`);

        extraParent.appendTo(parent);
        extraName.appendTo(extraParent);
        extraPrice.appendTo(extraParent);
    }
    clacTotalItem(parent);
}
// This Function to Create New Item With New Quantity For Extra, Discount And Details
function cloneNewItem(original, newQuant) {
    let newItem   = original.clone();
    let tableBody = $('.table-body');
    let itemId    = parseInt(tableBody.children().last().attr('item_id'))
    let price     = parseFloat(newItem.find('.price').text())

    newItem.find('.num').val(newQuant);
    newItem.attr('item_id', itemId + 1);
    newItem.find('.total input').val(newQuant * price);
    newItem.find('.trash').attr('id_order', itemId + 1);

    newItem.appendTo(tableBody);

    return newItem;

}
CalcTotalCheck();


$(document).on('click','#save_mincharge_menu',function (e)
    {
        let _token           = $('input[name="_token"]').val();

        e.preventDefault();
        let table      = $('#table_id').attr('value');
        let min_charge = $('#minchrage-input').val();
        let guest      = $('#guest-input').val();

        $.ajax({
            type    : 'POST',
            url     :"<?php echo e(route('change.charge')); ?>",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
            {
            _token         : _token,
            table          : table,
            min_charge     : min_charge,
            guest          : guest
            },
            success: function (data)
            {
                if (guest >= 1) {
                    $('#min_charge_modal_menu').modal('hide')
                    $('.check .table-info .minChargeCheck').remove();
                    $('.check .table-info .guestCheck').remove();
                    let html =''
                    html += `
                        <div class='minChargeCheck'>
                            <span>Min Charge</span>
                            <span>${min_charge * guest}</span>
                        </div>
                        <div class='guestCheck'>
                            <span>Guest</span>
                            <span>${guest}</span>
                        </div>
                    `;
                    $('.check .table-info').append(html)
                }
            },
        });
    });



$(document).on('click','#save_hold_delivery',function (e) {

    let myModal = $(this).parents('.modal')

    let order_id          = $('#new_order').attr('value');
    let _token            = $('input[name="_token"]').val();
    let date              = $('#date-input-hold').val();
    let time              = $('#time-input-hold').val();
    let toGoButton        = $('.nav-item.nav-togo').find('.notification-num');
    let deliveryButton    = $('.nav-item.nav-delivery').find('.notification-num');
    let toPilotButton     = $(`.delivery-item.to-pilot-btn`).find('.notification-num');
    let holdingDelivery   = $(`.delivery-item.holding-list-btn`).find('.notification-num');
    let holdingToGo       = $('.takeaway-item.holdingList').find('.notification-num');
    let NotificationArray = $('.notification-num');
    let dev               = $('#device_id').attr('value');
    let op                = $('#operation').attr('value');

    e.preventDefault();
    $.ajax({
        url:"<?php echo e(route('Save.hold.delivery')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data:
            {
                _token      : _token,
                order_id    : order_id,
                date        : date,
                time        : time,
                dev         : dev,
                op          : op
            },
        success: function (data)
        {
            if(data.status == 'true')
            {
                $('#new_order').attr('value',data.order)
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Accepted Holding',
                    showConfirmButton: false,
                    timer: 1250
                });
                if (op === 'TO_GO') {
                    holdingToGo.removeClass('del').text(parseInt(holdingToGo.text()) + 1);
                    toGoButton.removeClass('del').text(parseInt(toGoButton.text() || 0) + 1);
                } else if (op === 'Delivery') {
                    holdingDelivery.removeClass('del').text(parseInt(holdingDelivery.text()) + 1);
                    deliveryButton.removeClass('del').text(parseInt(deliveryButton.text() || 0) + 1);
                    // toPilotButton.removeClass('del').text(parseInt(toPilotButton.text()) - 1);
                    $('#Customer-model').modal('show');
                }
                NotificationArray.each(function() {
                    if($(this).text() == 0) {
                        $(this).addClass('del')
                    } else {
                        $(this).removeClass('del')
                    }
                });
                $('.check .table-body').children().remove();
                $('.orderCheck').children().last().text('');
                $('.cusName').children().last().text('');
                $('#new_order').attr('value', '');
                myModal.modal('hide');
                CalcTotalCheck();
            }
            if(data.status == "time")
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Enter Time Please',
                });
            }
            if(data.status == "order")
            {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Order Not Found',
                });
            }
            if(data.status == "none_customer"){
                console.log('yes No Customer')
                $('#Customer-model').modal('show')
            }
        },
    });
});



$(document).on('click','#summary_check',function (e)
    {
        let _token           = $('input[name="_token"]').val();
        e.preventDefault();
        let table      = $('#table_id').attr('value');
        let order      = $('#new_order').attr('value');
        let myModal = $("#pay-model");
        let totalPrice = parseFloat($('#total-price').text());
        let bankRatio = myModal.find('#bank-ratio');
        $.ajax({
            type    : 'POST',
            url     :"<?php echo e(route('Pay.check')); ?>",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
            {
                _token         : _token,
                table          : table,
                totalPrice     : totalPrice,
                order          : order,
            },
            success: function (data)
            {
                let html = '';
                let allquan = 0 ;
                let alltotal = 0;
                let allsummary = 0;
                let allnew = 0;
                let bank = 0;
                if(data.status == true)
                {
                    if (data.type == 'credit') {
                        $('#credit-tab').click();
                        $('#visa-price').val(data.visa + data.tip)
                    } else if (data.type == 'cash') {
                        $('#cash-tab').click();
                        $('#cash-price').val(data.total + data.tip)
                    } else if (data.type == 'hospitality') {
                        $('#hospitality-tab').click();
                    }
                    html += `<li>`
                    for(var count = 0 ; count < data.data.length ; count ++) {
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
                    if(data.min_charge > allsummary)
                    {
                        allnew  = data.min_charge
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
                    $('#ser-check').attr('dis') == '0' ?  myModal.attr('status', 'without') : myModal.attr('status', 'with')
                }else if(data.status == "min"){
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
                }else if(data.status == "empty_order"){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Order Not Found',
                    });
                }
            },
        });
    });

    // $('#pay-model').on('hidden.bs.modal', function() {
    //     $(this).find('.summary ul li:first-child').remove();
    // });

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
    $("#pay-model").on('hidden.bs.modal', function () {
        $(this).find('.summary ul li').not(".last-item").remove();
        // console.log($(this).find('.summary ul li'))
    });



$(document).on('click','#take_order',function (e) {
        let _token     = $('input[name="_token"]').val();
        let table      = $('#table_id').attr('value');
        let togo_table      = $('#togo_table').attr('value');
        let op         = $('#operation').attr('value');
        let order      = $('#new_order').attr('value');
        let takeOrder = $(this)
        let toPilot = $('.to-pilot-btn').find('.notification-num');
        let deliveryButton = $('.nav-item.nav-delivery').find('.notification-num');
        takeOrder.addClass('noClick');

        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('take.order')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token: _token,
                    table: table,
                    op: op,
                    order: order,
                    togo_table:togo_table,
                },
            success: function (data) {
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'take Order',
                    showConfirmButton: false,
                    timer: 250
                });
                setTimeout(() => {
                    takeOrder.removeClass('noClick')
                }, 1000);
                if (data.status == 'true') {
                    if (op == 'Delivery') {
                        let editCus = $('#Edit_customer').attr('value');
                        let checkHold = $('#check_hold').attr('value');
                        if (editCus == 'Edit_customer') {
                            if (checkHold == 0) {
                                location.href = '/menu/Delivery_to_pilot'
                            } else if (checkHold == 1) {
                                location.href = '/menu/Delivery_holding_list'
                            }
                        } else {
                            toPilot.removeClass('del').text(parseInt(toPilot.text()) + 1)
                            deliveryButton.removeClass('del').text(parseInt(deliveryButton.text() || 0) + 1);
                            Swal.fire({
                                position: 'center-center',
                                icon: 'success',
                                title: 'Send To Kitchen',
                                showConfirmButton: false,
                                timer: 1250
                            });
                            $('.check .table-body').children().remove();
                            $('.orderCheck').children().last().text('');
                            $('.cusName').children().last().text('');
                            $('#new_order').attr('value', '');
                            $('#pay-model').modal('hide');
                            $('#Customer-model').modal('show')
                            CalcTotalCheck();
                        }
                    }
                    else if (op == "Table") {
                        if(data.status = "true"){
                            location.href = '/menu/Show_Table'
                        }
                    }

                    $('.nav-sub .nav-item:first-of-type .nav-link').click()

                    // if (op == 'Delivery') {
                    //     let txt = (parseInt(delivery.text()) + 1) || 1
                    //     delivery.removeClass('del').text(txt)
                    //     toPilot.removeClass('del').text(parseInt(toPilot.text()) + 1)
                    // }
                }else if(data.status == 'none_customer'){
                    $('#Customer-model').modal('show')
                }else if(data.status == 'none_order'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Order Not Found',
                    });
                }
            }
        });
    });



$('.checkserviceandtax').on('change', function() {
    let order= $('#new_order').attr('value');
    let op  = $(this).attr('name');
    let SerTaxSave = $(this).attr('sertaxsave');
    let SerTax = $(this).attr('serTax');
    let active = '';
    let operation = $('#operation').attr('value');
    if ($(this).is(':checked')) {
      active = 0;
      $(this).attr('serTax', SerTaxSave);
      $(this).attr('sertaxsave', 0);
    } else {
      active = 1;
      $(this).attr('serTax', 0);
      $(this).attr('sertaxsave', SerTax);
    }
    $.ajax({
      url: "<?php echo e(route('CheckService')); ?>",
      method: 'post',
      enctype: "multipart/form-data",
      data: {
        _token  : _token,
        order   : order,
        active  : active,
        op      : op,
        operation  : operation
      },
      success: function (data) {
        CalcTotalCheck();
      }
    });

  });



function createServices(total, servRatio, taxRatio) {
    let serVal = total * (servRatio / 100);
    let taxval = (total + serVal) * (taxRatio / 100);

    return [serVal, taxval]
}

function createTaxWith(total, discount, service, taxRatio) {
    return (total - discount + service) * (taxRatio / 100)
}

$(document).on('input', '.input-ser', function() {
    let myModal = $(this).parents('.modal');
    let totalValue = parseFloat(myModal.attr('totVal'));
    let totalWithDis = parseFloat(myModal.find('.summary-total').text());
    let discount = parseFloat(myModal.find('.summary-discount').text());
    let taxRatio = $('#tax-check').attr('sertax');
    let Services = createServices(parseFloat(totalValue), $(this).val(), taxRatio)[0];
    let tax = 0;
    let currentValue = $(this).val()

    if (myModal.attr('status') === 'without') {
        tax = createServices(totalValue, $(this).val(), taxRatio)[1]
    } else {
        tax = createTaxWith(totalWithDis, discount, Services, taxRatio)
    }
    myModal.find('.summary-service').text(Services.toFixed(2));
    myModal.find('.summary-tax').text(tax.toFixed(2));

    let total = totalWithDis + Services + tax - discount;

    myModal.find('.all-total').text(total.toFixed(2));
    $('.price.summary-price').text(total.toFixed(2));

    $('.input-ser').each(function() {
        $(this).val(currentValue)
    });

    let bankRatio = myModal.find('#bank-ratio');
    bankRatio.attr('data-allnew', total.toFixed(2));
    if ($(this).attr('id') == 'visa-services') {
        $('#visa-price').val('');
        $('.summary-bank').text('0.00');
    }
});



$('body').on('input', '#visa-price', function() {
    let bankRatio = $('#bank-ratio');
    let bankValue = ($(this).val() * (bankRatio.val()/100));
    bankRatio.attr('data-bank', bankValue.toFixed(2));
    $('.summary-bank').text(bankRatio.attr('data-bank'));
    let total = Number(bankRatio.attr('data-allnew')) + Number(bankRatio.attr('data-bank'));
    $('.summary .all-total').text(total.toFixed(2));
    $('#credit-total-price').text(total.toFixed(2));
});



$('#printcheck').on('click', function() {
    let summaryTotal    = $('.summary-total').text();
    let summaryService  = $('.summary-service').text();
    let summaryTax      = $('.summary-tax').text();
    let allTotal        = $('.tab-pane.active .summary-price').text();
    let summaryDelivery = $('.summary-delivery').text();
    let summaryDiscount = $('.summary-discount').text();
    let method_bay          = $(this).parents('.modal').find('.tab-pane.active').attr('id');
    let Price           = $(this).parents('.modal').find('.tab-pane.active .price-value').val();
    let Rest            = $(this).parents('.modal').find('.tab-pane.active .price-rest').text();
    let order           = $('#new_order').attr('value');
    let serButton       = $('#ser-check');
    let myModal         = $(this).parents('.modal')
    let serVal          = myModal.find('.tab-pane.active .input-ser').val();
    let device          = $('#device_id').val();
    let table           = $('#table_id').attr('value');
    let operation       = $('#operation').attr('value');
    let bank_value      = $('.summary-bank').text();
    let type_method = $('#operation').attr('value');


    serButton.attr('sertax', serVal);

    if (type_method == 'TO_GO') {
            $('#take_order').click();
        }

    $.ajax({
        url: "<?php echo e(route('print.check')); ?>",
        method: 'post',
        enctype: "multipart/form-data",
        data: {
            _token  : _token,
            order   : order,
            bank_value :bank_value,
            service : summaryService,
            tax     : summaryTax,
            subtotal: summaryTotal,
            discount: summaryDiscount,
            total   : allTotal,
            method_bay  : method_bay,
            price   : Price,
            rest    : Rest,
            device  : device,
            table   : table,
            serviceratio : serVal,
            Delivery :summaryDelivery,
            operation:operation
        },
        success: function (data) {
            CalcTotalCheck();
            myModal.find('.input-ser').each(function() {
                $(this).val(serVal)
            })
            if(operation == "Table"){
                location.href = '/menu/Show_Table'
            }else if(operation == "Delivery"){
                $('#new_order').attr('value','')
                $('#Edit_customer').attr('value','New_customer')
                $('#Customer-model').modal('show')
                $('.orderCheck').last().text('')
                $('.cusName').last().text('')
                $('#total-price').text('0.00')
                $('#dis-val-check').text('0.00')
                $('#services-value').text('0.00')
                $('#tax-value').text('0.00')
                $('.items-num').text('0')
                $('.table-body').children().each(function() { $(this).remove() })
                $('#pay-model').modal('hide')
            }

            if (window.location.href.indexOf("TO_GO") > -1) {
                $('.check .table-body').children().remove();
                $('.orderCheck').children().last().text('');
                $('#new_order').attr('value', '');
                $('#togo_table').attr('value', '');
                $('#pay-model').modal('hide');
                $('#table_num_div').find('button').removeClass('d-none');
                $('#table_num_div').find('span:last-child').text('');
                $('#table_id').val('');
                $('#set_table_number').find('#tableNum').val('');
                CalcTotalCheck();
            }

            let holdCheck = $('#check_hold').attr('value');
            if (operation == 'TO_GO' && holdCheck == '0') {
                location.href = '/menu/New_Order/TO_GO'
            }

        }
    });
});



$('#paycheck').on('click', function() {
    let summaryTotal    = $('.summary-total').text();
    let summaryService  = $('.summary-service').text();
    let summaryTax      = $('.summary-tax').text();
    let summaryDiscount = $('.summary-discount').text();
    let allTotal        = $('.tab-pane.active .summary-price').text();
    let method_bay       = $(this).parents('.modal').find('.tab-pane.active').attr('id');
    let Price           = $(this).parents('.modal').find('.tab-pane.active .price-value').val();
    let Rest            = $(this).parents('.modal').find('.tab-pane.active .price-rest').text();
    let order           = $('#new_order').attr('value');
    let serButton       = $('#ser-check');

    let summaryDelivery = $('.summary-delivery').text();
    let myModal         = $(this).parents('.modal')
    let serVal          = myModal.find('.tab-pane.active .input-ser').val();
    let device          = $('#device_id').val();
    let table           = $('#table_id').attr('value');
    let operation       = $('#operation').attr('value');
    let bank_value      = $('.summary-bank').text();;

    serButton.attr('sertax', serVal);

    Swal.fire({
        title: 'Are you sure?',
        text: "You Want End Table",
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
                    _token  : _token,
                    order   : order,
                    service : summaryService,
                    tax     : summaryTax,
                    subtotal: summaryTotal,
                    discount: summaryDiscount,
                    total   : allTotal,
                    bank_value :bank_value,
                    method_bay  : method_bay,
                    price   : Price,
                    rest    : Rest,
                    device  : device,
                    table   : table,
                    serviceratio : serVal,
                    Delivery :summaryDelivery
                },
                success: function (data) {
                    location.href = '/menu/Show_Table'
                }
            });
        }
    });
});


    $("document").ready(function() {
        let holdCheck = $('#check_hold').attr('value');
        let operation = $('#operation').attr('value');
        let holdButton = $('#save_hold_delivery');
        if (window.location.href.indexOf("TO_GO") > -1 || operation == 'TO_GO' && holdCheck == '0') {
            $('.take-pay-btn').addClass('pay-togo');
            $('.footer-btns').addClass('pay-togo-mobil');
            $('.pay-modal').find('#paycheck').hide()
            $('.pay-modal').find('.modal-footer').addClass('flex-row-reverse');
        }
        if( operation == 'TO_GO' || operation == 'Delivery' ) {
            if(holdCheck == '1') {
                $('.take-pay-btn').addClass('change-hold').find('#change_hold').toggleClass('d-none d-flex');
                $('.footer-btns').addClass('change-hold-mobil').find('#change_hold').toggleClass('d-none d-block');
            }
        }
        if( operation == 'Delivery') {
            $('.take-pay-btn').addClass('pay-delivery');
            $('.footer-btns').addClass('pay-delivery-mobil');
        }
    });
    $('body').on('click','#change_hold', function () {
        let operation = $('#operation').attr('value');
        if(operation == 'Delivery') {
            location.href = '/menu/Delivery_holding_list'
        } else if (operation == 'TO_GO') {
            location.href = '/menu/TOGO_holding_list'
        }
    })


</script>
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/menu/import_item.blade.php ENDPATH**/ ?>
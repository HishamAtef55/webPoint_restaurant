@include('includes.stock.Stock_Ajax.public_function')
<script>
const date = $('#date');
const stores = $('#stores');
const mainGroup = $('#main_group');
const subGroup = $("#sub_group");
const unit = $("#unit");
const limitMinBtn = $("#limit_min_btn");
const limitMaxBtn = $("#limit_max_btn");
const storeBalanceBtn = $("#store_balance_btn");
const inventoryBtn = $("#inventory_btn");


$(document).ready(function() {
    $('select').select2({
        selectOnClose: true,
        dir: "rtl"
    });

    /*  ======================== Start Get Sub Group ============================== */
    mainGroup.on('change', function() {
        let mainGroup = $('#main_group').val()
        $.ajax({
            url: "{{route('get_sub_group')}}",
            method: 'post',
            data: {
                _token,
                mainGroup,
            },
            success: function(data) {
                if (data.status == 'true') {
                    let html = '<option selected disabled>اختر المجموعة الفرعية</option>';
                    data.subGroup.forEach((group) => {
                        html += `<option value="${group.id}">${group.name}</option>`
                    });
                    subGroup.html(html)
                    subGroup.select2('open');
                }
            },
        });
    });
    /*  ======================== End Get Sub Group ============================== */

    /*  ======================== Start limit Min Report ============================== */
    limitMinBtn.on('click', function() {
        console.log('hi')
        $.ajax({
            url: "{{route('get_sub_group')}}",
            method: 'post',
            data: {
                _token,
                date: date.val(),
                stores: stores.val(),
                mainGroup: mainGroup.val(),
                subGroup: subGroup.val(),
                unit: unit.val(),
            },
            success: function(data) {
                if (data.status == true) {}
            },
        });
    });
    /*  ======================== End limit Min Report ============================== */
});
</script>

<script>
    let _token = $('input[name="_token"]').val();
    let min_order_no = $('#min_order_no');
    let max_order_no = $('#max_order_no');
    let guests_no = $('#guests_no');
    let guests_avg = $('#guests_avg');
    let cash = $('#cash');
    let visa = $('#visa');
    let hos = $('#hos');
    let line = $('#line');
    let total_cash = $('#total_cash');
    let cus_payment = $('#cus_payment');
    let total = $('#total');
    let t_services = $('#t_services');
    let de_services = $('#de_services');
    let to_Services = $('#to_Services');
    let t_tax= $('#t_tax');
    let de_tax = $('#de_tax');
    let to_tax = $('#to_tax');
    let t_orders_no = $('#t_orders_no');
    let de_orders_no = $('#de_orders_no');
    let to_orders_no = $('#to_orders_no');
    let details = $('#details');
    let extras = $('#extras');
    let discounts = $('#discounts');
    let services = $('#services');
    let tax = $('#tax');
    let tip = $('#tip');
    let ration_bank = $('#ration_bank');
    $('#view_check').on('click', function(e){
        e.preventDefault();
        let date = $('#date').val();
        let shift = $('#shift').val();

        $.ajax({
            type    : 'POST',
            url     :"{{route('view.copy_close_check')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token,
                    date,
                    shift
                },
            success: function (data)
            {
                if(data.status == true){
                    $('#min_order_no').text(parseFloat(data.data.min_order).toFixed(2))
                    $('#max_order_no').text(parseFloat(data.data.max_order).toFixed(2))
                    $('#guests_no').text(parseFloat(data.data.gust_no).toFixed(2))
                    $('#guests_avg').text(parseFloat(data.data.gust_avarge).toFixed(2))
                    $('#cash').text(parseFloat(data.data.cash).toFixed(2))
                    $('#visa').text(parseFloat(data.data.visa).toFixed(2))
                    $('#hos').text(parseFloat(data.data.hos).toFixed(2))
                    $('#total_cash').text(parseFloat(data.data.total_cash).toFixed(2))
                    $('#cus_payment').text(parseFloat(data.data.customer_payments).toFixed(2))
                    $('#total').text(parseFloat(data.data.total_cash).toFixed(2))
                    $('#t_services').text(parseFloat(data.data.table_ser).toFixed(2))
                    $('#de_services').text(parseFloat(data.data.delivery_ser).toFixed(2))
                    $('#to_Services').text(parseFloat(data.data.to_go_ser).toFixed(2))
                    $('#t_tax').text(parseFloat(data.data.table_tax).toFixed(2))
                    $('#de_tax').text(parseFloat(data.data.delivery_tax).toFixed(2))
                    $('#to_tax').text(parseFloat(data.data.to_go_tax).toFixed(2))
                    $('#t_orders_no').text(parseFloat(data.data.table_no).toFixed(2))
                    $('#de_orders_no').text(parseFloat(data.data.delivery_no).toFixed(2))
                    $('#to_orders_no').text(parseFloat(data.data.to_go_no).toFixed(2))
                    $('#details').text(parseFloat(data.data.details).toFixed(2))
                    $('#extras').text(parseFloat(data.data.extras).toFixed(2))
                    $('#discounts').text(parseFloat(data.data.discount).toFixed(2))
                    $('#services').text(parseFloat(data.data.service).toFixed(2))
                    $('#tax').text(parseFloat(data.data.tax).toFixed(2))
                    $('#tip').text(parseFloat(data.data.tip).toFixed(2))
                    $('#ration_bank').text(parseFloat(data.data.r_bank).toFixed(2))
                    $('#min_order_no').text(parseFloat(data.data.min_order).toFixed(2))
                    let html ='';
                    data.groups.forEach(group=>{
                        html+=`<li>
                         <div class="col-3">
                            <span>${group.name}</span>
                        </div>
                         <div class="col-3">
                            <span>${parseFloat(group.total).toFixed(2)}</span>
                        </div>
                        <div class="col-3">
                            <span>${parseFloat(group.total_pre).toFixed(2)}</span>
                        </div>
                        <div class="col-3">
                            <span>${parseFloat(group.quantity).toFixed(2)}</span>
                        </div>
                    </li>`
                    })
                    $('#sales_group').html(html)
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Enter Date And Select Shift",
                    });
                }
            },
        });
    });
    $('#copy_check').on('click',function(e){
        e.preventDefault();
        let date = $('#date').val();
        let shift = $('#shift').val();
        let devId = localStorage.getItem("device_number");
        $.ajax({
            type    : 'POST',
            url     :"{{route('print.copy_close_check')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token,
                    date,
                    shift,
                    devId
                },
            success: function (data)
            {
                if(data.status == true){
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Printed',
                        showConfirmButton: false,
                        timer: 1250
                    });
                    $('#min_order_no').text(data.data.min_order)
                    $('#max_order_no').text(data.data.max_order)
                    $('#guests_no').text(data.data.gust_no)
                    $('#guests_avg').text(parseFloat(data.data.gust_avarge).toFixed(2))
                    $('#cash').text(parseFloat(data.data.cash).toFixed(2))
                    $('#visa').text(parseFloat(data.data.visa).toFixed(2))
                    $('#hos').text(parseFloat(data.data.hos).toFixed(2))
                    $('#total_cash').text(parseFloat(data.data.total_cash).toFixed(2))
                    $('#cus_payment').text(parseFloat(data.data.customer_payments).toFixed(2))
                    $('#total').text(parseFloat(data.data.total_cash).toFixed(2))
                    $('#t_services').text(parseFloat(data.data.table_ser).toFixed(2))
                    $('#de_services').text(parseFloat(data.data.delivery_ser).toFixed(2))
                    $('#to_Services').text(parseFloat(data.data.to_go_ser).toFixed(2))
                    $('#t_tax').text(parseFloat(data.data.table_tax).toFixed(2))
                    $('#de_tax').text(parseFloat(data.data.delivery_tax).toFixed(2))
                    $('#to_tax').text(parseFloat(data.data.to_go_tax).toFixed(2))
                    $('#t_orders_no').text(parseFloat(data.data.table_no).toFixed(2))
                    $('#de_orders_no').text(parseFloat(data.data.delivery_no).toFixed(2))
                    $('#to_orders_no').text(parseFloat(data.data.to_go_no).toFixed(2))
                    $('#details').text(parseFloat(data.data.details).toFixed(2))
                    $('#extras').text(parseFloat(data.data.extras).toFixed(2))
                    $('#discounts').text(parseFloat(data.data.discount).toFixed(2))
                    $('#services').text(parseFloat(data.data.service).toFixed(2))
                    $('#tax').text(parseFloat(data.data.tax).toFixed(2))
                    $('#tip').text(parseFloat(data.data.tip).toFixed(2))
                    $('#ration_bank').text(parseFloat(data.data.r_bank).toFixed(2))
                    $('#min_order_no').text(parseFloat(data.data.min_order).toFixed(2))
                    let html ='';
                    data.groups.forEach(group=>{
                        html+=`<li>
                         <div class="col-3">
                            <span>${group.name}</span>
                        </div>
                         <div class="col-3">
                            <span>${parseFloat(group.total).toFixed(2)}</span>
                        </div>
                        <div class="col-3">
                            <span>${parseFloat(group.total_pre).toFixed(2)}</span>
                        </div>
                        <div class="col-3">
                            <span>${parseFloat(group.quantity).toFixed(2)}</span>
                        </div>
                    </li>`
                    })
                    $('#sales_group').html(html)
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "Enter Date And Select Shift",
                    });
                }

            }
        })
    })
</script>

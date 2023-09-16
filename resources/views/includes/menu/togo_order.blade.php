<script>
    {{--###################### Start Remove Order ##################################### --}}
    let _token      = $('input[name="_token"]').val();
    $(document).on('click','#Remove_Delivery',function () {
        let order_id    = $(this).parents(".box").find('.order_id').text();
        // let orderPrice = $('#box-removeing').find('li.orderPrice span');
        $.ajax({
            type    : 'POST',
            url     :"{{route('Remove.Delivery')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token         : _token,
                    order_id       : order_id
                },
            success: function (data)
            {
                if (data.status == 'true')
                {
                    $("body").removeClass("blur");
                    $(this).parents(".box").remove();
                }

            },
        });
    });
    {{--###################### End Remove Order ##################################### --}}
    {{--###################### Start Add Pilot ##################################### --}}
    $(document).on('click','#add_pilot',function () {
        let order_id    = $(this).parents("#pilot").attr('order_id');
        let pilot       = $('.custom-select').val();
        $.ajax({
            type    : 'POST',
            url     :"{{route('add.pilot.Delivery')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token         : _token,
                    order_id       : order_id,
                    pilot          : pilot
                },
            success: function (data)
            {
                $('#pilot').modal('hide')
                if(data.status == 'true')
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Accepted Matching',
                        showConfirmButton: false,
                        timer: 1250
                    });
                }
            },
        });
    });
    {{--###################### End Add Pilot ##################################### --}}
    {{--######################  View OrdersM in Main Table##################################### --}}
    $(document).ready(function(){
        $('#select_location').on('change',function () {
            let html = '';
            let query = $('#select_location').val();
            let page  = $('#check_page').attr('value');
            console.log(query);
            $.ajax({
                url:"{{route('Search.order.delivery')}}",
                method:'post',
                data:
                    {
                        query  :query,
                        _token :_token,
                        page   :page
                    },
                success:function(data)
                {
                    $('.box').remove();
                    for(var count = 0 ; count < data.length ; count ++)
                    {
                        html+=`<div class='box' box_id="${data[count].wait_order_id}">`

                            html+=`<ul class="list-unstyled box-list">`
                                html+=`<li>`
                                    html+=`<i class="fas fa-hashtag"></i>`
                                    html+=`<span class="order_id">${data[count].wait_order_id}</span>`
                                html+=`</li>`

                                html+=`<li>`
                                    html+=`<i class="fas fa-user"></i>`
                                    html+=`<span>${data[count].customer[0].name}</span>`
                                html+=`</li>`
                                html+=`<li>`
                                    html+=`<i class="fas fa-user-tie"></i>`
                                    html+=`<span>${data[count].user}</span>`
                                html+=`</li>`

                                html+=`<li>`
                                    html+=`<i class="fas fa-map-marker-alt"></i>`
                                    html+=`<span>${data[count].location}</span>`
                                html+=`</li>`

                                html+=`<li>`
                                    html+=`<i class="fas fa-money-bill-wave"></i>`
                                    html+=`<span>2000</span>`
                                html+=`</li>`

                            html+=`</ul>`

                            html+=`<div class='box-menu'>`

                                html+=`<ul>`

                                    html+=`<li data-toggle="modal" data-target="#pilot">`
                                        html+=`<i class="fas fa-biking fa-fw"></i>`
                                        html+=`<span>Pilot</span>`
                                    html+=`</li>`

                                    html+=`<li>`
                                        html+=`<i class="fas fa-edit fa-fw"></i>`
                                        html+=`<a href="{{url('menu/Edit_Delivery/${data[count].wait_order_id}')}}"> Edit </a>`
                                    html+=`</li>`

                                    html+=`<li id="Remove_Delivery"  class='remove'>`
                                        html+=`<i class="fas fa-trash-alt fa-fw"></i>`
                                        html+=`<span>Remove</span>`
                                    html+=`</li>`

                                html+=`</ul>`

                            html+=`</div>`

                        html+=`</div>`
                    }$('#box_content').html(html);
                }
            });
        });
    });
    {{--######################  ############################################################## --}}

    {{--###################### Start Take_Order_Holde ##################################### --}}
    $(document).on('click','#take_order_hold',function () {
        let order_id    = $(this).parents(".box").find('.order_id').text();
        console.log(order_id)
        $.ajax({
            type    : 'POST',
            url     :"{{route('take.order.hold.delivery')}}",
            method  : 'post',
            enctype : "multipart/form-data",
            data:
                {
                    _token         : _token,
                    order_id       : order_id
                },
            success: function (data)
            {
                if (data.status == 'true')
                {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Accepted Order',
                        showConfirmButton: false,
                        timer: 1250
                    });
                }

            },
        });
    });
    {{--###################### End Take_Order_Holde ##################################### --}}



</script>

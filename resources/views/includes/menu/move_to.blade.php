
<script>
let _token = $('input[name="_token"]').val();
let Order_Number_dev = $('#device_id');
{{--######################  View OrdersM in Main Table##################################### --}}
$(document).ready(function(){
    $('#search_main_table').on('change',function () {
        let html = '';
        let ID = $('#search_main_table').val();
            $('#tbody').show();
            $.ajax({
                url:"{{route('search.main.table')}}",
                method:'post',
                data:
                {
                    ID     :ID,
                    _token :_token
                },
                success:function(data)
                {
                    for(var count = 0 ; count < data.length ; count ++)
                    {
                        html+='<li subgroupid="'+data[count].subgroup_id+'" subgroupname="'+data[count].subgroup_name+'" item_id="'+data[count].item_id+'" subID="'+data[count].sub_num_order+'" orderID="'+data[count].order_id+'" quantity="'+data[count].quantity+'" data-item="'+data[count].id+'">';
                            html+='<span>'+data[count].name+'</span>';
                            html+='<input type="number" value="'+data[count].quantity+'" disabled />';
                        if(data[count].discount > 0) {
                            html+='<div class="discount" id="'+data[count].id+'">';
                                html+='<div class="discount-name">';
                                    html+='<span>Discount</span>';
                                    html+='<span>'+data[count].discount_name+'</span>';
                                html+='</div>';
                            html+='</div>';
                        }


                        for(var detail = 0 ; detail < data[count].details.length ; detail ++)
                        {
                            html+='<div class="details" id="'+data[count].details[detail].detail_id+'">';
                                html+='<div class="details_name">';
                                    html+='<span>Detail</span>';
                                    html+='<span>'+data[count].details[detail].name+'</span>';
                                html+='</div>';
                            html+='</div>';
                        }

                        for(let extra= 0 ; extra < data[count].extra.length ; extra ++)
                        {
                            html+='<div class="extra" id="'+data[count].extra[extra].extra_id+'">';
                                html+='<div class="extra-name">';
                                    html+='<span>Extra</span>';
                                    html+='<span>'+data[count].extra[extra].name+'</span>';
                                html+='</div>';
                            html+='</div>';
                        }
                        html+='</li>';
                    }
                    $('#main_order_view').html(html);
                    $('#search_new_table')
                    .children(`option:contains(Table ${ID})`)
                    .prop('disabled', true)
                    .siblings().not(':first-child').prop('disabled', false);
                }
            });
    });
});
{{--######################  ############################################################## --}}

{{--######################  View OrdersM in New Table ##################################### --}}
$(document).ready(function(){
    $('#search_new_table').on('change',function () {
        let html = '';
        let ID = $('#search_new_table').val();
        $('#tbody').show();

        $.ajax({
            url:"{{route('search.new.table')}}",
            method:'post',
            data:
            {
                ID     :ID,
                _token :_token
            },
            success:function(data)
            {
                for(var count = 0 ; count < data.length ; count ++)
                    {
                       html+='<li subgroupid="'+data[count].subgroup_id+'" subgroupname="'+data[count].subgroup_name+'" subID="'+data[count].sub_num_order+'" orderID="'+data[count].order_id+'" quantity="'+data[count].quantity+'" data-item="'+data[count].id+'">';
                            html+='<span>'+data[count].name+'</span>';
                            html+='<input type="number" value="'+data[count].quantity+'" disabled />';
                        if(data[count].discount > 0)
                        {
                            html+='<div class="discount" id="'+data[count].id+'">';
                                html+='<div class="discount-name">';
                                    html+='<span>Discount</span>';
                                    html+='<span>'+data[count].discount_name+'</span>';
                                html+='</div>';
                            html+='</div>';
                        }


                        for(var detail = 0 ; detail < data[count].details.length ; detail ++)
                        {
                            html+='<div class="details" id="'+data[count].details[detail].detail_id+'">';
                                html+='<div class="details_name">';
                                    html+='<span>Detail</span>';
                                    html+='<span>'+data[count].details[detail].name+'</span>';
                                html+='</div>';
                            html+='</div>';
                        }

                        for(let extra= 0 ; extra < data[count].extra.length ; extra ++)
                        {
                            html+='<div class="extra" id="'+data[count].extra[extra].extra_id+'">';
                                html+='<div class="extra-name">';
                                    html+='<span>Extra</span>';
                                    html+='<span>'+data[count].extra[extra].name+'</span>';
                                html+='</div>';
                            html+='</div>';
                        }
                       html+='</li>';
                    }
                $('#new_order_view').html(html);
            }
        });
    });
});
    {{--######################  ############################################################## --}}


{{--######################  Transfer Data ############################################### --}}
    $(document).ready(function(){
        $('#transfer').on('click',function (e) {
            e.preventDefault();

            let table_id  = $('.new_table').val();
            let mainTable = $('.main_table').val();
            let subId     = [];
            let quantity  = [];
            let idRow     = [];
            let item_id   = [];
            let group_id  = [];
            let group_name= [];
            let size_data = 0;


            function getDetailsInfo() {
                let mainArray = [];

                let left = Array.from($(`.lists .left ul li.added`));

                left.forEach(element => {
                    subId.push(element.getAttribute('subid'));
                    quantity.push(element.getAttribute('quantity'));
                    idRow.push(element.getAttribute('data-item'));
                    item_id.push(element.getAttribute('item_id'));
                    group_id.push(element.getAttribute('subgroupid'));
                    group_name.push(element.getAttribute('subgroupname'));
                    mainArray.push({
                        'subId'    : subId[subId.length - 1],
                        'quantity' : quantity[quantity.length - 1],
                        'idRow'    : idRow[idRow.length - 1],
                        'item_id'  : item_id[item_id.length - 1],
                        'group_id'  : group_id[group_id.length - 1],
                        'group_name'  : group_name[group_name.length - 1]
                });
            });
                size_data = mainArray.length
                return {mainArray, size_data}
            }
            let rightLength= Array.from($(`.lists .right li`)).length;


            $.ajax({
                url:"{{route('move.to.item')}}",
                method:'post',
                data:
                {
                    _token           :_token,
                    Order_Number_dev : Order_Number_dev.val(),
                    table_id         : table_id,
                    maintable        : mainTable,
                    size_data        : getDetailsInfo().size_data,
                    Data             : getDetailsInfo().mainArray,
                    rightLength      : rightLength
                },
                success:function(data)
                {
                    if(data.status == 'true') {
                        $('#alert_show').show();
                        setTimeout(function () {
                            $('#alert_show').hide();
                        }, 2500);
                        $(`.left ul li`).remove();
                         location.href = '/menu/Show_Table'
                    }

                }
            });
        });
    });
{{--######################  ############################################################## --}}
</script>

<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    const saveItemPriceBtn = $('#save_item_price');
    const material = $('#material');
    const subGroups = $('#subGroup');
    const groups = $('#group');
    const branch = $('#branch');
    const total = $('#total');
    const expectedSale = $('#expected_sale');
    const indirect = $('#indirect');
    const safe = $('#safe');
    const tbody = $('tbody')

    $(document).ready(function() {
        $('select').select2({
            selectOnClose: true,
            dir: "rtl"
        });

        /* ========================= Start Change Branch ============================== */
        branch.on('change', function() {
            $.ajax({
                url: "<?php echo e(route('reports.items-pricing.getGroups')); ?>",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function (data) {
                    if (data.status == true) {
                        let html = '<option value="all" >All Group</option>';
                        data.groups.forEach((row) => {
                            html += `<option value="${row.id}" >${row.name}</option>`
                        });
                        groups.html(html)
                        groups.select2({
                            dir: "rtl"
                        });
                        groups.select2('open');
                    }
                },
            });
        });
        /* ========================= End Change Branch ================================ */

        /* ========================= Start Change Group =============================== */
        groups.on('change', function() {
            $.ajax({
                url: "<?php echo e(route('reports.items-pricing.getSubGroups')); ?>",
                method: 'post',
                data: {
                    _token,
                    group: groups.val(),
                },
                success: function (data) {
                    if (data.status == true) {
                        let html = '<option value="all">All Sub-Group</option>';
                        data.subGroups.forEach((row) => {
                            html += `<option value="${row.id}" >${row.name}</option>`
                        });
                        subGroups.html(html)
                        subGroups.select2({
                            dir: "rtl"
                        });
                        subGroups.select2('open');
                    }
                },
            });
        });
        /* ========================= End Change Group ================================ */

        /*  ======================== Start All Functions ============================== */
        const undirectCalc = (rowParent, indirectVal, safeVal) => {
            let mainCost = +rowParent.find('td').eq(3).text();
            let detailsCost = +rowParent.find('td').eq(4).text();
            let sauceCost = +rowParent.find('td').eq(5).text();
            let packingCost = +rowParent.find('td').eq(6).text();
            let undirectValue = (mainCost + detailsCost + sauceCost + packingCost) * (indirectVal / 100 );
            rowParent.find('td').eq(7).text(undirectValue.toFixed(2));
            let finalCostValue = undirectValue + mainCost + detailsCost + sauceCost + packingCost
            let finalCostWithSafe = finalCostValue + (finalCostValue * safeVal / 100)
            rowParent.find('td').eq(8).text(finalCostWithSafe.toFixed(2));
        }

        const calcNetValues = (rowParent, price, serviceVal, taxVal) => {
            let detailsPrice = +rowParent.find('td').eq(2).text();
            let finalCost = +rowParent.find('td').eq(8).text();
            let netTableVal = ((+price + +detailsPrice) * serviceVal) - finalCost
            rowParent.find('td').eq(9).text(netTableVal.toFixed(2));
            let netTakeAwayVal = (+price + +detailsPrice) - finalCost
            rowParent.find('td').eq(10).text(netTakeAwayVal.toFixed(2));
            let netDeliveryVal = (+price + +detailsPrice) - taxVal - finalCost
            rowParent.find('td').eq(11).text(netDeliveryVal.toFixed(2));
            let costPercent = finalCost / (+price + +detailsPrice)
            rowParent.find('td').eq(12).text((costPercent * 100).toFixed(2) + '%');
            let profitPercent = 1 - costPercent
            rowParent.find('td').eq(13).text((profitPercent * 100).toFixed(2) + '%');
        }
        /*  ======================== End All Functions ============================== */

        /*  ======================== Start Save Item Price ============================== */
        saveItemPriceBtn.on('click', function() {
            $.ajax({
                url: "<?php echo e(route('reports.items-pricing.getItems')); ?>",
                method: 'post',
                data: {
                    _token,
                    groupId     : groups.val(),
                    branch      : branch.val(),
                    subGroupsId : subGroups.val(),
                    material    : material.val()
                },
                success: function(data) {
                    let html = null
                    data.items.forEach( (item) => {
                        html += `<tr>
                            <td> ${item.name}</td>
                            <td> <input class="priceItem" value="${item.price}" style="width: 60px"> </td>
                            <td> ${item.price_details} </td>
                            <td> ${(+item.material_direct_sum_cost).toFixed(2)} </td>
                            <td> ${item.cost_details} </td>
                            <td> 0 </td>
                            <td> ${item.material_indirect_sum_cost} </td>`;
                            let indirectVal = (item.material_direct_sum_cost + item.cost_details + 0 + item.material_indirect_sum_cost) * (indirect.val() / 100)
                            let totalPrice = item.price + item.price_details;
                            html += `<td> ${indirectVal.toFixed(2)} </td>`
                            let finalCost = (indirectVal + item.material_direct_sum_cost + item.cost_details + 0 + item.material_indirect_sum_cost)
                            let finalCostWithSafe = finalCost + (finalCost * (safe.val() / 100)) ;
                            let costPercent = (finalCostWithSafe / totalPrice);
                            let profitPercent = 1 - costPercent;
                            html += `<td> ${finalCostWithSafe.toFixed(2)} </td>
                            <td> ${(totalPrice * 0.12 - finalCostWithSafe).toFixed(2)} </td>
                            <td> ${(totalPrice - finalCostWithSafe).toFixed(2)} </td>
                            <td> ${(totalPrice - 15 - finalCostWithSafe).toFixed(2)} </td>
                            <td> ${(costPercent  * 100).toFixed(2)} % </td>
                            <td> ${(profitPercent  * 100).toFixed(2)} % </td>
                        </tr>`
                    });
                    tbody.html(html)
                },
            });
        });
        /*  ======================== End Save Item Price ============================== */

        /*  ======================== Start Change Price In Table ============================== */
        $(document).on('change', '.priceItem', function() {
            let parentRow = $(this).parents('tr');
            undirectCalc(parentRow, indirect.val(), safe.val());
            calcNetValues(parentRow, $(this).val(), 0.12, 15)
        })
        /*  ======================== End Change Price In Table ============================== */



        total.on('change', function() {
            if(expectedSale.val()) {
                indirect.val((+$(this).val() / +expectedSale.val() * 100).toFixed(3) )
            }
            if(tbody.find('tr').length > 0) {
                tbody.find('tr').each(function() {
                    $(this).find('.priceItem').change();
                });
            }
        });

        expectedSale.on('change', function() {
            if (total.val()) {
                indirect.val((+total.val() / +$(this).val() * 100).toFixed(3))
            }
            if(tbody.find('tr').length > 0) {
                tbody.find('tr').each(function() {
                    $(this).find('.priceItem').change();
                });
            }
        });

        indirect.on('change', function() {
            total.val((+expectedSale.val() * (+$(this).val() / 100)).toFixed(3))
            if(tbody.find('tr').length > 0) {
                tbody.find('tr').each(function() {
                    $(this).find('.priceItem').change();
                });
            }
        });

        safe.on('change', function() {
            if(tbody.find('tr').length > 0) {
                tbody.find('tr').each(function() {
                    $(this).find('.priceItem').change();
                });
            }
        });
    });


</script>
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/stock/reports_ajax/itemsPricing.blade.php ENDPATH**/ ?>
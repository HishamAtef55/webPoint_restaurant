<script>
    let _token           = $('input[name="_token"]').val();
    /*######################### Waiter Sales Report ###########################*/
    $(document).on('click','#waiter_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let trans    = []
        let transLabel = []
        $('#transaction').find('input:checked').each(function() {
                trans.push($(this).val())
                transLabel.push($(this).siblings('label').text())
        })
        let bay_way  = []
        let bay_way_label  = []
        $('#bay_way').find('input:checked').each(function() {
            bay_way.push($(this).val())
            bay_way_label.push($(this).siblings('label').text())
        });
        let user     = []
        let userLabel     = []
        $('#user').find('input:checked').each(function() {
            user.push($(this).val())
            userLabel.push($(this).siblings('label').text())
        });

        $.ajax({
            url:"<?php echo e(route('search_water_sales_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    trans,
                    bay_way,
                    user,
                    from,
                    to,
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>Waiter name</th>
                            <th>Order No</th>
                            <th>Total</th>
                            <th>Tip</th>
                            <th>Guests No</th>
                            <th>Ave/Guest</th>
                        </tr>
                    </thead>
                    <tbody>`;
                let allTotal = 0;
                let allAvgTotal = 0;
                let allTip = 0;
                for (const info in data.waiters) {
                    html += `<tr class="table-light">
                        <td>${data.waiters[info].waiter}</td>
                        <td>${data.waiters[info].orders}</td>
                        <td>${parseFloat(data.waiters[info].total).toFixed(2)}</td>
                        <td>${parseFloat(data.waiters[info].tip).toFixed(2)}</td>`
                        allTip += data.waiters[info].tip
                        allTotal += data.waiters[info].total
                        html += `<td>${data.waiters[info].guest}</td>
                        <td>${parseFloat(data.waiters[info].avg).toFixed(2)}</td>`
                        allAvgTotal += data.waiters[info].avg
                    html += `</tr>`
                }
                html += `</tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td>Total</td>
                            <td>-</td>
                            <td>${parseFloat(allTotal).toFixed(2)}</td>
                            <td>${parseFloat(allTip).toFixed(2)}</td>
                            <td>${parseFloat(allAvgTotal).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>`;
                $('.waiter').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                `
                                transLabel.length > 0 ? pdfMsg += `Transaction : ${transLabel.join(' , ')}
                                ` : ''
                                bay_way_label.length > 0 ? pdfMsg += `Bay Way : ${bay_way_label.join(' , ')}
                                ` : ''
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                transLabel.length > 0 ? message += `<span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
                                bay_way_label.length > 0 ? message += `<span class="d-block w-50">Bay Way : ${bay_way_label.join(' , ')}</span>` : ''
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });


    $(document).on('click','#shift_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let trans    = []
        let transLabel = []
        $('#transaction').find('input:checked').each(function() {
                trans.push($(this).val())
                transLabel.push($(this).siblings('label').text())
        })
        let bay_way  = []
        let bay_way_label  = []
        $('#bay_way').find('input:checked').each(function() {
            bay_way.push($(this).val())
            bay_way_label.push($(this).siblings('label').text())
        });
        let shift    = []
        let shiftLabel    = []
        $('#shift').find('input:checked').each(function() {
            shift.push($(this).val())
            shiftLabel.push($(this).siblings('label').text())
        });

        $.ajax({
            url:"<?php echo e(route('search_shift_sales_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    trans,
                    bay_way,
                    shift,
                    from,
                    to,
                },
            success: function (data) {
                let html = '';
                html +=` <table class="reports table text-center mb-5 mt-3 table-report">
                <thead class="thead-light">
                    <tr>
                        <th>Shift name</th>
                        <th>Order No</th>
                        <th>Total</th>
                        <th>Guests No</th>
                        <th>Ave/Guest</th>
                    </tr>
                </thead>
                <tbody>`;
                let allTotal = 0;
                let allAvgTotal = 0;
                for (const info in data.shifts) {
                    let shiftName = $(`input.check-shift[value="${data.shifts[info].shift}"]`).next('label').text()
                    html += `<tr class="table-light">
                        <td>${shiftName}</td>
                        <td>${data.shifts[info].orders}</td>
                        <td>${parseFloat(data.shifts[info].total).toFixed(2)}</td>`
                        allTotal += data.shifts[info].total
                        html += `<td>${data.shifts[info].guest}</td>
                        <td>${parseFloat(data.shifts[info].avg).toFixed(2)}</td>`
                        allAvgTotal += data.shifts[info].avg
                    html += `</tr>`
                }
                html += `</tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td>Total</td>
                            <td>-</td>
                            <td>${parseFloat(allTotal).toFixed(2)}</td>
                            <td>-</td>
                            <td>${parseFloat(allAvgTotal).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>`;
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                `
                                transLabel.length > 0 ? pdfMsg += `Transaction : ${transLabel.join(' , ')}
                                ` : ''
                                bay_way_label.length > 0 ? pdfMsg += `Bay Way : ${bay_way_label.join(' , ')}
                                ` : ''
                                shiftLabel.length > 0 ? pdfMsg += `Shift : ${shiftLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                transLabel.length > 0 ? message += `<span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
                                bay_way_label.length > 0 ? message += `<span class="d-block w-50">Bay Way : ${bay_way_label.join(' , ')}</span>` : ''
                                shiftLabel.length > 0 ? message += `<span class="d-block w-50">Shift : ${shiftLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    $(document).on('click','#transfer_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let type     = $('#transfer').val()
        $.ajax({
            url:"<?php echo e(route('search_transfer_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:{
                _token,
                from,
                type,
                to,
            },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">`
                let x = 1;
                if(type == "Move-To"){
                    html +=`<tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Waiter</th>
                        <th>Type</th>
                    </tr></thead><tbody>`;
                }else{
                    html +=`<tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Table</th>
                        <th>Type</th>
                    </tr></thead><tbody>`;
                }
                for(var count = 0 ; count < data.trans.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${x}</td>
                        <td>${data.trans[count].date}</td>
                        <td>${data.trans[count].time}</td>
                        <td>${data.trans[count].from}</td>
                        <td>${data.trans[count].to}</td>
                        <td>${data.trans[count].waiter}</td>
                        <td>${data.trans[count].type}</td>
                    </tr>`
                    x++;
                }
                html += `</tbody></table>`
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            messageTop: `Restaurant Name: ${data.res_nmae}
                            From : ${from}
                            To : ${to}
                            Type: ${type}`,
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            customize: function ( win ) {
                                $(` <p> Restaurant Name : ${data.res_nmae}</p>
                                    <p> From : ${from}</p>
                                    <p> To : ${to}</p>
                                    <p> Type : ${type}</p>`
                                ).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    $(document).on('click','#discount_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let trans    = []
        let transLabel = []
        $('#transaction').find('input:checked').each(function() {
                trans.push($(this).val())
                transLabel.push($(this).siblings('label').text())
        })
        let bay_way  = []
        let bay_way_label  = []
        $('#bay_way').find('input:checked').each(function() {
            bay_way.push($(this).val())
            bay_way_label.push($(this).siblings('label').text())
        });
        $.ajax({
            url:"<?php echo e(route('search_discount_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    trans,
                    bay_way,
                    from,
                    to,
                },
            success: function (data) {
                let html = '';
                html =`<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Dis-Type</th>
                            <th>Waiter</th>
                            <th>total</th>
                            <th>discount</th>
                            <th>R-total</th>
                        </tr>
                    </thead>
                    <tbody>`;
                let Total = 0;
                let discount= 0;
                let allTotal= 0;
                for(var count = 0 ; count < data.orders.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${data.orders[count].order_id}</td>
                        <td>${data.orders[count].d_order}</td>
                        <td>${data.orders[count].discount_type}</td>
                        <td>${data.orders[count].user}</td>
                        <td>${data.orders[count].total + data.orders[count].total_discount}</td>`;
                        Total += data.orders[count].total + data.orders[count].total_discount
                        html += `<td>${data.orders[count].total_discount}</td>`;
                        discount += data.orders[count].total_discount
                        html += `<td>${data.orders[count].total}</td>`;
                        allTotal += data.orders[count].total
                    html += `</tr>`
                }
                html += `</tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td>Total</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>${parseFloat(Total).toFixed(2)}</td>
                            <td>${parseFloat(discount).toFixed(2)}</td>
                            <td>${parseFloat(allTotal).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>`;
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                `
                                transLabel.length > 0 ? pdfMsg += `Transaction : ${transLabel.join(' , ')}
                                ` : ''
                                bay_way_label.length > 0 ? pdfMsg += `Bay Way : ${bay_way_label.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                transLabel.length > 0 ? message += `<span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
                                bay_way_label.length > 0 ? message += `<span class="d-block w-50">Bay Way : ${bay_way_label.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    $(document).on('click','#void_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let type     = $('#type').val();
        let user     = []
        let userLabel     = []
        $('#user').find('input:checked').each(function() {
            user.push($(this).val())
            userLabel.push($(this).siblings('label').text())
        });
        $.ajax({
            url:"<?php echo e(route('search_void_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    user,
                    from,
                    to,
                    type
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Qyt</th>
                            <th>total</th>
                            <th>user</th>
                            <th>status</th>
                        </tr>
                    </thead>
                <tbody>`;
                let allTotal = 0;
                let allQyt = 0;
                for(var count = 0 ; count < data.voids.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${data.voids[count].order_id}</td>
                        <td>${data.voids[count].date}</td>
                        <td>${data.voids[count].name}</td>
                        <td>${data.voids[count].quantity}</td>`
                        allQyt += data.voids[count].quantity
                        html += `<td>${data.voids[count].total}</td>`
                        allTotal += data.voids[count].total
                        html += `<td>${data.voids[count].user}</td>
                        <td>${data.voids[count].status}</td>
                    </tr>`
                }
                html += `</tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td>Total</td>
                            <td>-</td>
                            <td>-</td>
                            <td>${parseFloat(allQyt).toFixed(2)}</td>
                            <td>${parseFloat(allTotal).toFixed(2)}</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>`;
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                    Type : ${type}
                                `
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                message += `<span class="d-block w-50">Type : ${type}</span>`
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    $(document).on('click','#sales_item_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        $.ajax({
            url:"<?php echo e(route('search_item_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    from,
                    to,
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th> Item-ID </th>
                            <th> Item-Name</th>
                        </tr>
                    </thead>
                    <tbody>`;
                for (const info in data.items) {
                    html += `<tr class="table-light">
                            <td>${data.items[info].id}</td>
                            <td>${data.items[info].name}</td>
                        </tr>`
                }
                html += '</tbody></table>'
                $('.waiter').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            messageTop: `Restaurant Name: ${data.res_nmae}
                            From : ${from}
                            To : ${to}`,
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            customize: function ( win ) {
                                $(` <p> Restaurant Name : ${data.res_nmae}</p>
                                    <p> From : ${from}</p>
                                    <p> To : ${to}</p>`
                                ).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    $(document).on('click','#costReport',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let userLabel= []
        $.ajax({
            url:"<?php echo e(route('costReport')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    from,
                    to,
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Cost</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                <tbody>`;
                let total = 0;
                let cost = 0;
                let profit = 0;
                let realcount = 1;
                for(var count = 0 ; count < data.orders.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${realcount++}</td>
                        <td>${data.orders[count].date}</td>
                        <td>${parseFloat(data.orders[count].total).toFixed(2)}</td>`
                        total+=data.orders[count].total
                        html += `<td>${parseFloat(data.orders[count].cost).toFixed(2)}</td>`
                        cost += data.orders[count].cost
                        html += `<td>${parseFloat(data.orders[count].total - data.orders[count].cost).toFixed(2)}</td>`
                        profit += data.orders[count].total - data.orders[count].cost
                    html += `</tr>`
                }
                html += `</tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td>Total</td>
                            <td>-</td>
                            <td>${parseFloat(total).toFixed(2)}</td>
                            <td>${parseFloat(cost).toFixed(2)}</td>
                            <td>${parseFloat(profit).toFixed(2)}</td>
                        </tr>
                    </tfoot>
                </table>`;
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                    Type : ${type}
                                `
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                message += `<span class="d-block w-50">Type : ${type}</span>`
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    // ================================================ Cost Sold Reports =============================
    $(document).on('click','#cost_sold_items',function (e) {
        e.preventDefault();
        let type     = $(this).attr('id');
        let trans    = []
        let transLabel = []
        $('#transaction').find('input:checked').each(function() {
            trans.push($(this).val())
            transLabel.push($(this).siblings('label').text())
        })
        let bay_way  = []
        let bay_way_label  = []
        $('#bay_way').find('input:checked').each(function() {
            bay_way.push($(this).val())
            bay_way_label.push($(this).siblings('label').text())
        });
        let shift    = []
        let shiftLabel    = []
        $('#shift').find('input:checked').each(function() {
            shift.push($(this).val())
            shiftLabel.push($(this).siblings('label').text())
        });
        let user     = []
        let userLabel     = []
        $('#user').find('input:checked').each(function() {
            user.push($(this).val())
            userLabel.push($(this).siblings('label').text())
        });
        let device   = []
        let deviceLabel   = []
        $('#device').find('input:checked').each(function() {
            device.push($(this).val())
            deviceLabel.push($(this).siblings('label').text())
        });
        let addition = []
        let additionLabel = []
        $('#addition').find('input:checked').each(function() {
            addition.push($(this).val())
            additionLabel.push($(this).siblings('label').text())
        });
        let ex_de    = []
        let ex_de_label    = []
        $('#ex_de').find('input:checked').each(function() {
            ex_de.push($(this).val())
            ex_de_label.push($(this).siblings('label').text())
        });
        let from     = $('#from').val();
        let to       = $('#to').val();
        let sortReverse = $('#sort_reverse');
        $.ajax({
            url:"<?php echo e(route('cost_sold_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    from,
                    to,
                },
            success: function (data) {
                $('.report-filter').modal('hide');
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                <thead  class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Quant</th>
                        <th>Price unit</th>
                        <th>Total</th>
                    </tr>

                    </thead> <tbody>`
                for(let group in data.orders) {
                    let groupTotal = 0;
                    html +=`<tr  class="table-light">
                        <td></td>
                        <td class="text-center h5 font-weight-bold">${data.orders[group].name}</td>
                        <td></td>
                        <td></td>
                    </tr>`;
                    for(let subGroup in data.orders[group].sub_group) {
                        if(data.orders[group].sub_group[subGroup].sold) {
                            if (sortReverse.is(":checked")) {
                                data.orders[group].sub_group[subGroup].sold.sort((a,b) => b.quan - a.quan);
                            } else {
                                data.orders[group].sub_group[subGroup].sold.sort((a,b) => a.quan - b.quan);
                            }
                            let subGroupTotal = 0;
                            html += `
                        <tr  class="table-light">
                            <td class="text-left pl-3 h6 font-weight-bold">${data.orders[group].sub_group[subGroup].name}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>`;
                            for(let sold in data.orders[group].sub_group[subGroup].sold) {
                                html += `
                                    <tr  class="table-light">
                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].name}</td>
                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].quan}</td>
                                        <td>${(data.orders[group].sub_group[subGroup].sold[sold].price / data.orders[group].sub_group[subGroup].sold[sold].quan).toFixed(2)}</td>
                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].price}</td>
                                    </tr>`;
                                subGroupTotal += data.orders[group].sub_group[subGroup].sold[sold].price;
                                if(ex_de == 'Extra' || ex_de == 'with_ex_de'){
                                    if (data.orders[group].sub_group[subGroup].sold[sold].extra) {
                                        html += `<tr>
                                                    <td class="text-center h6 font-weight-bold">Extra</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>`;
                                        for (let extra in data.orders[group].sub_group[subGroup].sold[sold].extra) {
                                            html += `<tr class="table-secondary">
                                                        <td class="text-right">${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].name}</td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].quan}</td>
                                                        <td>${(data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price / data.orders[group].sub_group[subGroup].sold[sold].extra[extra].quan).toFixed(2)}</td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price}</td>
                                                    </tr>`;
                                            subGroupTotal += data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price;
                                        }
                                    }
                                }
                                if(ex_de == ""){ex_de = "with_ex_de" }
                                if(ex_de == 'Details' || ex_de == 'with_ex_de'){
                                    if (data.orders[group].sub_group[subGroup].sold[sold].details) {
                                        html += `<tr>
                                                    <td class="text-center h6 font-weight-bold">Details</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>`;
                                        for (let detail in data.orders[group].sub_group[subGroup].sold[sold].details) {
                                            html += `<tr class="table-secondary">
                                                        <td class="text-right">${data.orders[group].sub_group[subGroup].sold[sold].details[detail].name}</td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].details[detail].quan}</td>
                                                        <td>${(data.orders[group].sub_group[subGroup].sold[sold].details[detail].price / data.orders[group].sub_group[subGroup].sold[sold].details[detail].quan).toFixed(2)}</td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].details[detail].price}</td>
                                                    </tr>`;
                                            subGroupTotal += data.orders[group].sub_group[subGroup].sold[sold].details[detail].price;
                                        }
                                    }
                                }
                            }
                            html += `<tr class="table-light">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="font-weight-bold h6">Total: ${subGroupTotal}</td>
                                </tr>`;
                            groupTotal += subGroupTotal;
                        }
                    }
                    html += `<tr class="table-light">
                        <td></td>
                        <td class="font-weight-bold h5">Total:  ${groupTotal}</td>
                        <td></td>
                        <td></td>
                    </tr>`;
                }
                html+= '</tbody></table>'
                $('#report-output').html(html)
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    ordering: false,
                    // paging: false,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            pageSize: 'A4',
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                `
                                transLabel.length > 0 ? pdfMsg += `Transaction : ${transLabel.join(' , ')}
                                ` : ''
                                bay_way_label.length > 0 ? pdfMsg += `Bay Way : ${bay_way_label.join(' , ')}
                                ` : ''
                                shiftLabel.length > 0 ? pdfMsg += `Shift : ${shiftLabel.join(' , ')}
                                ` : ''
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                deviceLabel.length > 0 ? pdfMsg += `Device : ${deviceLabel.join(' , ')}
                                ` : ''
                                additionLabel.length > 0 ? pdfMsg += `Addition : ${additionLabel.join(' , ')}
                                ` : ''
                                ex_de_label.length > 0 ? pdfMsg += `Details&Extra : ${ex_de_label.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                transLabel.length > 0 ? message += `<span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
                                bay_way_label.length > 0 ? message += `<span class="d-block w-50">Bay Way : ${bay_way_label.join(' , ')}</span>` : ''
                                shiftLabel.length > 0 ? message += `<span class="d-block w-50">Shift : ${shiftLabel.join(' , ')}</span>` : ''
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                deviceLabel.length > 0 ? message += `<span class="d-block w-50">Device : ${deviceLabel.join(' , ')}</span>` : ''
                                additionLabel.length > 0 ? message += `<span class="d-block w-50">Addition : ${additionLabel.join(' , ')}</span>` : ''
                                ex_de_label.length > 0 ? message += `<span class="d-block w-50">Details&Extra : ${ex_de_label.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            },
        });
    });

    // ================================================ search_expenses_report Reports =============================
    $(document).on('click','#expenses_report',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let type     = $('#type').val();
        let user     = []
        let userLabel     = []
        $('#user').find('input:checked').each(function() {
            user.push($(this).val())
            userLabel.push($(this).siblings('label').text())
        });
        $.ajax({
            url:"<?php echo e(route('search_expenses_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    user,
                    from,
                    to,
                    type
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>Order</th>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Qyt</th>
                            <th>total</th>
                            <th>user</th>
                            <th>status</th>
                        </tr>
                    </thead>
                <tbody>`;
                let allTotal = 0;
                let allQyt = 0;
                for(var count = 0 ; count < data.voids.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${data.voids[count].order_id}</td>
                        <td>${data.voids[count].date}</td>
                        <td>${data.voids[count].name}</td>
                        <td>${data.voids[count].quantity}</td>`
                        allQyt += data.voids[count].quantity
                        html += `<td>${data.voids[count].total}</td>`
                        allTotal += data.voids[count].total
                        html += `<td>${data.voids[count].user}</td>
                        <td>${data.voids[count].status}</td>
                    </tr>`
                }
                html += `</tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td>Total</td>
                            <td>-</td>
                            <td>-</td>
                            <td>${parseFloat(allQyt).toFixed(2)}</td>
                            <td>${parseFloat(allTotal).toFixed(2)}</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>`;
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                    Type : ${type}
                                `
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                message += `<span class="d-block w-50">Type : ${type}</span>`
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });

    // ===================== Collapse Reports Filter ================
    $('#transaction, #bay_way, #shift, #user, #device, #addition, #ex_de').on('hide.bs.collapse', function () {
        let checkedBoxLength = $(this).find('input:checked').length;
        if (checkedBoxLength > 0) {
            $(this).siblings('h5').addClass('closed').attr('length', checkedBoxLength)
        } else {
            $(this).siblings('h5').removeClass('closed')
        }
    });

    $('#transaction, #bay_way, #shift, #user, #device, #addition, #ex_de').on('show.bs.collapse', function () {
        $(this).siblings('h5').removeClass('closed')
    });

    $('input[type="reset"]').on('click', function() {
        $('h5.closed').each(function() {
            $(this).removeClass('closed')
        });
    });

    // ============================================ Log Report ========================================================
    $(document).on('click','#logReport',function (e) {
        e.preventDefault();
        let from     = $('#from').val();
        let to       = $('#to').val();
        let userLabel= []
        $.ajax({
            url:"<?php echo e(route('logReport')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    from,
                    to,
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Order</th>
                            <th>OP</th>
                            <th>Table</th>
                            <th>User</th>
                            <th>View</th>
                        </tr>
                    </thead>
                <tbody>`;
                let total = 0;
                let cost = 0;
                let profit = 0;
                let realcount = 1;
                for(var count = 0 ; count < data.orders.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${realcount++}</td>
                        <td>${data.orders[count].d_order}</td>
                        <td>${data.orders[count].t_order}</td>
                        <td>${data.orders[count].order_id}</td>
                        <td>${data.orders[count].op}</td>
                        <td>${data.orders[count].table}</td>
                        <td>${data.orders[count].user}</td>
                        <td><i class="far fa-eye viewLog" idRow="${data.orders[count].order_id}"></i></td>`
                    html += `</tr>`
                }
                // html += `</tbody>
                //     <tfoot class="table-dark">
                //         <tr>
                //             <td>Total</td>
                //             <td>-</td>
                //             <td>${parseFloat(total).toFixed(2)}</td>
                //             <td>${parseFloat(cost).toFixed(2)}</td>
                //             <td>${parseFloat(profit).toFixed(2)}</td>
                //         </tr>
                //     </tfoot>
                // </table>`;
                $('#report-output').html(html);
                $('.report-filter').modal('hide');
                $('.filter').addClass('d-none');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                    Type : ${type}
                                `
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                message += `<span class="d-block w-50">Type : ${type}</span>`
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });



    $(document).on('click','.viewLog',function (e) {
        e.preventDefault();
        let order = $(this).attr('idRow');
        $.ajax({
            url:"<?php echo e(route('viewLog')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    order
                },
            success: function (data) {
                let html = '';
                html += `<table class="reports table text-center mb-5 mt-3 table-report">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>OP</th>
                            <th>Table</th>
                            <th>Order</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                <tbody>`;
                let total = 0;
                let cost = 0;
                let profit = 0;
                let realcount = 1;
                for(var count = 0 ; count < data.logs.length ; count ++){
                    html += `<tr class="table-light">
                        <td>${realcount++}</td>
                        <td>${data.logs[count].date}</td>
                        <td>${data.logs[count].time}</td>
                        <td>${data.logs[count].op}</td>
                        <td>${data.logs[count].Table}</td>
                        <td>${data.logs[count].order}</td>
                        <td>${data.logs[count].user}</td>
                        <td>${data.logs[count].type}</td>
                        <td>${data.logs[count].item}</td>
                        <td>${data.logs[count].qty}</td>
                        <td>${data.logs[count].note}</td>`
                    html += `</tr>`
                }
                // html += `</tbody>
                //     <tfoot class="table-dark">
                //         <tr>
                //             <td>Total</td>
                //             <td>-</td>
                //             <td>${parseFloat(total).toFixed(2)}</td>
                //             <td>${parseFloat(cost).toFixed(2)}</td>
                //             <td>${parseFloat(profit).toFixed(2)}</td>
                //         </tr>
                //     </tfoot>
                // </table>`;
                $('#repViewLog').html(html);
                $('#report-log').modal('show');
                $('.table-report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Filter',
                            className: 'btn-filter',
                            action: function ( e, dt, node, conf ) {
                                $('.filter').click();
                            }
                        },
                        'copy',
                        'csv',
                        'excel',
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            alignment: "center",
                            footer: true,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
                                    From : ${from}
                                    To : ${to}
                                    Type : ${type}
                                `
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')}
                                ` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.defaultStyle.font = 'Cairo';
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableBodyEven.lineHeight = "1.5";
                                doc.styles.tableBodyOdd.lineHeight = "1.5";
                                doc.styles.tableFooter.alignment = "center";
                                doc.styles.tableHeader.alignment = "center";
                            }
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p class='m-0'>Restaurant Name : ${data.res_nmae}</p>`
                                message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                message += `<span class="d-block w-50">Type : ${type}</span>`
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ))
                            }
                        },
                        'pageLength',
                    ]
                });
            }
        });
    });
</script>
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/reports/general_reports.blade.php ENDPATH**/ ?>
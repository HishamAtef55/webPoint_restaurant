<script>
    let _token           = $('input[name="_token"]').val();
    $(document).on('click','#daily_report',function (e) {
        e.preventDefault();
        $('#loading').addClass('show')


        const toDataURL = url => fetch(url)
        .then(response => response.blob())
        .then(blob => new Promise((resolve, reject) => {
            const reader = new FileReader()
            reader.onloadend = () => resolve(reader.result)
            reader.onerror = reject
            reader.readAsDataURL(blob)
        }));
        let logo = null;
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
        $.ajax({
            url:"<?php echo e(route('daily_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    type,
                    trans,
                    bay_way,
                    shift,
                    user,
                    device,
                    addition,
                    ex_de,
                    from,
                    to,
                },
            success: function (data) {

                let now = new Date();
                let jsDate = `${now.getFullYear()}-${now.getMonth()+1}-${now.getDate()}`;
                toDataURL(`<?php echo e(asset('${data.res.image}')); ?>`)
                .then(dataUrl => {
                    logo = dataUrl
                });

                $('.report-filter').modal('hide');
                // Start Full Data In Table Head
                $('.sold-report').html('');
                let html = '';
                let groupArray = [];
                html += `<table class="reports table text-center mb-5 mt-3 table-report" width="100%">
                    <thead class="thead-light">
                        <tr>`;
                        if(from != to){
                            html += `
                            <th>Date</th>
                            <th>Orders</th>`;
                        }else{
                            html += `
                            <th>Order</th>
                            <th>OP</th>
                            <th>Table</th>
                            <th>Time</th>`;
                        }
                        data.group.forEach(g => {
                            html += `<th>${g.name}</th>`;
                            groupArray.push({name: g.name, total: 0})
                        });
                        html += `<th>Sub Total</th>
                            <th>Service</th>
                            <th>Tax</th>
                            <th>Disc</th>
                            <th>Hosp</th>
                            <th>Min.ch</th>`;
                        if(from == to){
                            html += `<th>Bay Way</th> `;
                        }
                        html += `
                            <th>Visa</th>
                            <th>Cash</th>
                            <th>Exp</th>
                            <th>Tip</th>
                            <th>Total</th>
                        </tr>
                    </thead> <tbody>`;
                // End Full Data In Table Head
                // Start Full Data In Table Body
                let totalSub = 0;
                let totalServices = 0;
                let totalTax = 0;
                let totalDiscount = 0;
                let totalHospitality = 0;
                let totalCharge = 0;
                let totalCash = 0;
                let totalVisa = 0;
                let totalBank = 0;
                let totalTip = 0;
                let allTotal = 0;
                for(const order in data.orders) {
                    html += `<tr class="table-light">`;
                        if(from != to){
                            html += `<td>${data.orders[order].d_order}</td>
                            <td>${data.orders[order].order_id}</td>
                            `;
                        }else{
                            html += `
                            <td>${data.orders[order].order_id}</td>
                            <td>${data.orders[order].op}</td>
                            <td>${data.orders[order].table}</td>
                            <td>${data.orders[order].t_closeorder}</td>`;
                        }
                    groupArray.forEach(group =>{
                        let price = data.orders[order][group.name] || 0
                        html += `<td>${parseFloat(price).toFixed(2)}</td>`;
                        group.total += price;
                    });
                    html += `<td>${parseFloat(data.orders[order].sub_total).toFixed(2)}</td>`
                    totalSub +=data.orders[order].sub_total

                    html += `<td>${parseFloat(data.orders[order].services).toFixed(2)}</td>`
                    totalServices +=data.orders[order].services
                    html += `<td>${parseFloat(data.orders[order].tax).toFixed(2)}</td>`
                    totalTax +=data.orders[order].tax
                    html += `<td>${parseFloat(data.orders[order].total_discount).toFixed(2) || 0 }</td>`
                    totalDiscount +=data.orders[order].total_discount || 0
                    html += `<td>${parseFloat(data.orders[order].hosp_total).toFixed(2) || 0 }</td>`
                    totalHospitality +=data.orders[order].hosp_total
                    html += `<td>${data.orders[order].min_charge}</td>`
                    totalCharge +=data.orders[order].min_charge
                    if(from == to){
                        html += `<td>${data.orders[order].method}</td>`;
                    }
                    html += `<td>${parseFloat(data.orders[order].visa).toFixed(2)}</td>`
                    totalVisa+=data.orders[order].visa
                    html += `<td>${parseFloat(data.orders[order].cash).toFixed(2)}</td>`
                    totalCash+=data.orders[order].cash
                    html += `<td>${data.orders[order].r_bank}</td>`
                    totalBank+=data.orders[order].r_bank
                    html += `<td>${parseFloat(data.orders[order].tip).toFixed(2)}</td>`
                    totalTip+=data.orders[order].tip
                    html += `<td>${parseFloat(data.orders[order].total).toFixed(2)}</td>`
                    allTotal+=data.orders[order].total
                    html += `</tr>`;
                }
                // End Full Data In Table Body
                html+= '</tbody><tfoot class="table-dark">'
                 // Start Full Data In Table Footer
                if(from != to) {
                    html += `<tr>
                                <td>Total</td>
                                <td>-</td>`;
                } else {
                    html += `<tr>
                    <td>Total</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>`;
                }
                let lastCol = totalCash + totalVisa + totalBank
                groupArray.forEach(group => html += `<td>${parseFloat(group.total).toFixed(2)}</td>`);
                html += `
                    <td>${parseFloat(totalSub).toFixed(2)}</td>
                    <td>${parseFloat(totalServices).toFixed(2)}</td>
                    <td>${parseFloat(totalTax).toFixed(2)}</td>
                    <td>${parseFloat(totalDiscount).toFixed(2)}</td>
                    <td>${parseFloat(totalHospitality).toFixed(2)}</td>
                    <td>${parseFloat(totalCharge).toFixed(2)}</td>`;
                    if(from == to){
                        html += `
                        <td>-</td>`
                    }
                    html += `<td>${parseFloat(totalVisa).toFixed(2)}</td>
                    <td>${parseFloat(totalCash).toFixed(2)}</td>
                    <td>${parseFloat(totalBank).toFixed(2)}</td>
                    <td>${parseFloat(totalTip).toFixed(2)}</td>
                    <td>${parseFloat(allTotal).toFixed(2)}</td>`;
                html += `</tr></tfoot></table>`;
                // End Full Data In Table Footer
                $('#report-output').html(html)
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
                            filename: data.report_type,
                            download: 'open',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            footer: true,
                            exportOptions: {
                                orthogonal: "PDF",
                                columns: ':visible',
                                search: 'applied',
                                order: 'applied'
                            },
                            messageTop: function() {
                                let pdfMsg = `From : ${from} \n To : ${to} \n`
                                transLabel.length > 0 ? pdfMsg += `Transaction : ${transLabel.join(' , ')} \n` : ''
                                bay_way_label.length > 0 ? pdfMsg += `Bay Way : ${bay_way_label.join(' , ')} \n` : ''
                                shiftLabel.length > 0 ? pdfMsg += `Shift : ${shiftLabel.join(' , ')} \n` : ''
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')} \n` : ''
                                deviceLabel.length > 0 ? pdfMsg += `Device : ${deviceLabel.join(' , ')} \n` : ''
                                additionLabel.length > 0 ? pdfMsg += `Addition : ${additionLabel.join(' , ')} \n` : ''
                                ex_de_label.length > 0 ? pdfMsg += `Details&Extra : ${ex_de_label.join(' , ')} \n` : ''
                                return pdfMsg;
                            },
                            customize: function (doc) {
                                doc.content.splice(0,1);

                                doc.pageMargins = [15,60,15,25];
                                doc.defaultStyle.fontSize = 8;
                                doc.defaultStyle.font = "Cairo";
                                doc.content[0].lineHeight = 0.8;
                                doc.content[0].fontSize = 10;
                                doc.styles.tableHeader.fillColor = '#159d71';
                                doc.styles.tableHeader.color = '#FFF';
                                doc.styles.tableHeader.fontSize = 9;
                                doc.styles.tableHeader.alignment = 'center';
                                doc.styles.tableBodyOdd.alignment = 'center';
                                doc.styles.tableBodyEven.alignment = 'center';
                                doc.styles.tableFooter.alignment = 'center';
                                doc.styles.tableFooter.fontSize = 9;

                                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');

                                doc['header']=(function() {
                                    return {
                                        columns: [
                                            {
                                                image: logo,
                                                width: 50
                                            },
                                            {
                                                alignment: 'left',
                                                bold: true,
                                                text: data.res.name,
                                                fontSize: 13,
                                                margin: [5,15]
                                            },
                                            {
                                                alignment: 'right',
                                                fontSize: 13,
                                                bold: true,
                                                text: data.report_type.replace('_', ' '),
                                                margin: [5,15]
                                            }
                                        ],
                                        margin: [15,5]
                                    }
                                });

                                doc['footer']=(function(page, pages) {
                                    return {
                                        columns: [
                                            {
                                                alignment: 'left',
                                                text: ['Created on: ', { text: jsDate.toString() }]
                                            },
                                            {
                                                alignment: 'right',
                                                text: ['page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
                                            }
                                        ],
                                        margin: [15,5]
                                    }
                                });

                                let objLayout = {};
                                objLayout['hLineWidth'] = function(i) { return .5; };
                                objLayout['vLineWidth'] = function(i) { return .5; };
                                objLayout['hLineColor'] = function(i) { return '#ddd'; };
                                objLayout['vLineColor'] = function(i) { return '#ddd'; };
                                objLayout['paddingLeft'] = function(i) { return 4; };
                                objLayout['paddingRight'] = function(i) { return 4; };
                                doc.content[1].layout = objLayout;
                            }
                        },
                        {
                            extend: 'print',
                            orientation: 'landscape',
                            footer: true,
                            autoPrint: true,
                            exportOptions: {
                                columns: ':visible',
                                search: 'applied',
                                order: 'applied'
                            },
                            customize: function ( win, doc ) {
                                $(win.document.body).find('h1').remove()
                                $(win.document.body).css( 'background', '#FFF' )
                                let message = `<div> <div class="d-flex align-items-center justify-content-between flex-wrap">
                                    <div class="w-50 d-flex align-items-center ">
                                        <img src="${logo}" width="100" height="100">
                                        <h4 class="font-weight-bold ml-2">${data.res.name}</h4>
                                    </div>
                                    <div class="w-50"> <h3 class="font-weight-bold text-right">${data.report_type.replace('_', ' ')}</h3> </div>
                                </div>`
                                message += `<div class="mt-2 d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">From : ${from}</span>`
                                message += `<span class="d-block w-50">To : ${to}</span>`
                                transLabel.length > 0 ? message += `<span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
                                bay_way_label.length > 0 ? message += `<span class="d-block w-50">Bay Way : ${bay_way_label.join(' , ')}</span>` : ''
                                shiftLabel.length > 0 ? message += `<span class="d-block w-50">Shift : ${shiftLabel.join(' , ')}</span>` : ''
                                userLabel.length > 0 ? message += `<span class="d-block w-50">User : ${userLabel.join(' , ')}</span>` : ''
                                deviceLabel.length > 0 ? message += `<span class="d-block w-50">Device : ${deviceLabel.join(' , ')}</span>` : ''
                                additionLabel.length > 0 ? message += `<span class="d-block w-50">Addition : ${additionLabel.join(' , ')}</span>` : ''
                                ex_de_label.length > 0 ? message += `<span class="d-block w-50">Details&Extra : ${ex_de_label.join(' , ')}</span>` : ''
                                message += '</div>'
                                $(message).insertBefore($(win.document.body).find( 'table' ));
                            }
                        },
                        {
                            extend: 'colvis',
                        }
                    ]
                });
            },
            complete: function(data) {
                $('#loading').removeClass('show');
            }
        });
    });

    $(document).on('click','#sold_items',function (e) {
        e.preventDefault();
        $('#loading').addClass('show')

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
            url:"<?php echo e(route('daily_sold_report')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:
                {
                    _token,
                    type,
                    trans,
                    bay_way,
                    shift,
                    user,
                    device,
                    addition,
                    ex_de,
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
                                        subGroupTotal += +data.orders[group].sub_group[subGroup].sold[sold].price;
                                        if(ex_de == ""){ex_de = "with_ex_de" }
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
                                                        <td class="text-right">${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].name} (E) </td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].quan}</td>
                                                        <td>${(data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price / data.orders[group].sub_group[subGroup].sold[sold].extra[extra].quan).toFixed(2)}</td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price}</td>
                                                    </tr>`;
                                                    subGroupTotal += +data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price;
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
                                                        <td class="text-right">${data.orders[group].sub_group[subGroup].sold[sold].details[detail].name} (D) </td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].details[detail].quan}</td>
                                                        <td>${(data.orders[group].sub_group[subGroup].sold[sold].details[detail].price / data.orders[group].sub_group[subGroup].sold[sold].details[detail].quan).toFixed(2)}</td>
                                                        <td>${data.orders[group].sub_group[subGroup].sold[sold].details[detail].price}</td>
                                                    </tr>`;
                                                    subGroupTotal += +data.orders[group].sub_group[subGroup].sold[sold].details[detail].price;
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
                            groupTotal += +subGroupTotal;
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
            error: function(data) {
                Swal.fire({
                    position: 'center-center',
                    icon: 'error',
                    title: data.responseJSON.message,
                    showConfirmButton: true,
                });
            },
            complete: function(data) {
                $('#loading').removeClass('show');
            }
        });
    });
</script>
<?php /**PATH C:\xampp\htdocs\webpoint\resources\views/includes/reports/daily_reports.blade.php ENDPATH**/ ?>
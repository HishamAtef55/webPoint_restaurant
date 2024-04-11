<script>
    let _token           = $('input[name="_token"]').val();
    $(document).on('click','.btn-report-in-day',function (e) {
        e.preventDefault();
        let type = $(this).attr('id');
        let title = $(this).text();
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

        $.ajax({
            url:"<?php echo e(route('cashier_report')); ?>",
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
                    ex_de
                },
            success: function (data)
            {
                $('.report-filter').modal('hide');
                // Start Full Data In Table Head
                $('.sold-report').html('');
                let html = ''
                let groupArray = [];
                    html += `
                    <table class="reports table text-center mb-5 mt-3 table-report">
                        <thead class="thead-light">
                            <tr>
                                <th>Order</th>
                                <th>OP</th>
                                <th>Table</th>
                                <th>Time</th>`;
                                data.group.forEach(g => {
                                    html += `<th>${g.name}</th>`;
                                    groupArray.push({name: g.name, total: 0})
                                });
                                html += `
                                <th>Sub Total</th>
                                <th>Service</th>
                                <th>Tax</th>
                                <th>Disc</th>
                                <th>Min.ch</th>`;
                                if(type == 'cashier_report'){
                                    html += `
                                    <th>Bay Way</th>
                                    <th>Cash</th>
                                    <th>Visa</th>
                                    <th>Bank</th>
                                    <th>Tip</th>`
                                }
                                html += `
                                    <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>`;
                // End Full Data In Table Head
                // Start Full Data In Table Body
                let totalSub = 0;
                let totalServices = 0;
                let totalTax = 0;
                let totalDiscount = 0;
                let totalCharge = 0;
                let totalCash = 0;
                let totalVisa = 0;
                let totalBank = 0;
                let totalTip = 0;
                let allTotal = 0;
                for(const order in data.orders) {
                    html += `<tr class="table-light">`;
                    html += `
                    <td>${data.orders[order].order_id}</td>
                    <td>${data.orders[order].op}</td>
                    <td>${data.orders[order].table}</td>`;
                    if(type == 'cashier_report'){
                        html += `
                        <td>${data.orders[order].t_closeorder}</td>`;
                    }else if(type == 'busy_order'){
                        html += `
                        <td>${data.orders[order].t_order}</td>`;
                    }
                    groupArray.forEach(group => {
                        let price = data.orders[order][group.name] || 0
                        html += `<td>${parseFloat(price).toFixed(2)}</td>`;
                        group.total += parseFloat(price).toFixed(2);

                    })
                    html += `
                    <td>${data.orders[order].sub_total}</td>${totalSub+=data.orders[order].sub_total}
                    <td>${data.orders[order].services}</td>${totalServices+=data.orders[order].services}
                    <td>${data.orders[order].tax}</td>${totalTax+=data.orders[order].tax}
                    <td>${data.orders[order].total_discount || 0 }</td>${totalDiscount+=data.orders[order].total_discount || 0}
                    <td>${data.orders[order].min_charge}</td>${totalCharge+=data.orders[order].min_charge}`;
                    if(type == 'cashier_report'){
                        html += `
                        <td>${data.orders[order].method}</td>
                        <td>${data.orders[order].cash}</td>${totalCash+=data.orders[order].cash}
                        <td>${data.orders[order].visa}</td>${totalVisa+=data.orders[order].visa}
                        <td>${data.orders[order].r_bank}</td>${totalBank+=data.orders[order].r_bank}
                        <td>${data.orders[order].tip}</td>${totalTip+=data.orders[order].tip}
                        <td>${data.orders[order].total}</td>${allTotal+=data.orders[order].total}`;
                    }else if(type == 'busy_order') {
                        html += `<td>${data.orders[order].total}</td>${allTotal+=data.orders[order].total}`;
                    };
                    html += `</tr>`;
                }
                html += `</tbody> <tfoot class="table-dark">`;
                // End Full Data In Table Body
                // Start Full Data In Table Footer
                html += `<tr>
                <td>Total</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                `;
                groupArray.forEach(group => html += `<td>${parseFloat(group.total).toFixed(2)}</td>`);
                html += `
                    <td>${parseFloat(totalSub).toFixed(2)}</td>
                    <td>${parseFloat(totalServices).toFixed(2)}</td>
                    <td>${parseFloat(totalTax).toFixed(2)}</td>
                    <td>${parseFloat(totalDiscount).toFixed(2)}</td>
                    <td>${parseFloat(totalCharge).toFixed(2)}</td>`;
                    if(type == 'cashier_report'){
                        html += `
                        <td>-</td>
                        <td>${parseFloat(totalCash).toFixed(2)}</td>
                        <td>${parseFloat(totalVisa).toFixed(2)}</td>
                        <td>${parseFloat(totalBank).toFixed(2)}</td>
                        <td>${parseFloat(totalTip).toFixed(2)}</td>
                        <td>${parseFloat(allTotal).toFixed(2)}</td>`;
                    }else if(type == 'busy_order') {
                        html += `<td>${parseFloat(allTotal).toFixed(2)}</td>`;
                    };
                html += `</tr></tfoot></table>`;
                $('#report-output').html(html)
                // End Full Data In Table Footer
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
                        {
                            extend: 'excel',
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
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
                        },
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            orientation: 'landscape',
                            pageSize: 'A3',
                            title: title,
                            footer: true,
                            exportOptions: {
                                orthogonal: "PDF",
                            },
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae} \n`
                                transLabel.length > 0 ? pdfMsg += `Transaction : ${transLabel.join(' , ')} \n` : ''
                                bay_way_label.length > 0 ? pdfMsg += `Bay Way : ${bay_way_label.join(' , ')} \n` : ''
                                shiftLabel.length > 0 ? pdfMsg += `Shift : ${shiftLabel.join(' , ')} \n` : ''
                                userLabel.length > 0 ? pdfMsg += `User : ${userLabel.join(' , ')} \n` : ''
                                deviceLabel.length > 0 ? pdfMsg += `Device : ${deviceLabel.join(' , ')} \n` : ''
                                additionLabel.length > 0 ? pdfMsg += `Addition : ${additionLabel.join(' , ')} \n` : ''
                                ex_de_label.length > 0 ? pdfMsg += `Details&Extra : ${ex_de_label.join(' , ')} \n` : ''
                                return pdfMsg;
                            },
                            messageBottom: function() {
                                return 'hi'
                            },
                            customize: function (doc) {
                                doc.pageMargins = [15,15];
                                doc.content[0].margin = [0,0,0,5]
                                doc.defaultStyle.font = "Cairo";
                                doc.defaultStyle.fontSize = 10;
                                doc.defaultStyle.alignment = 'center';
                                doc.styles.tableHeader.fillColor = '#159d71';
                                doc.styles.tableHeader.color = 'white';
                                doc.styles.tableHeader.alignment = "center";
                                doc.styles.tableFooter.alignment = "center";
                                doc.content[1].margin = [0,0,0,5]
                                doc.content[2].table.widths = Array(doc.content[2].table.body[0].length + 1).join('*').split('');
                            },
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p style="margin: 0">Restaurant Name : ${data.res_nmae}</p>`
                                transLabel.length > 0 ? message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
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
                document.getElementById('report-output').firstChild.remove();
            },
        });
    });

    $(document).on('click','.btn-report-sold-in-day',function (e) {
        e.preventDefault();
        let type     = $(this).attr('id');
        let title = $(this).text();
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
        let sortReverse = $('#sort_reverse');

        $.ajax({
            url:"<?php echo e(route('cashier_report_sold')); ?>",
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
                    ex_de
                },
            success: function (data)
            {
                if(ex_de == ""){ex_de = "with_ex_de" }
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

                </thead>
                <tbody>`
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
                                    <td>${data.orders[group].sub_group[subGroup].sold[sold].price / data.orders[group].sub_group[subGroup].sold[sold].quan}</td>
                                    <td>${data.orders[group].sub_group[subGroup].sold[sold].price}</td>
                                </tr>`;
                                    subGroupTotal += +data.orders[group].sub_group[subGroup].sold[sold].price;

                                    if(ex_de == 'Extra' || ex_de == 'with_ex_de') {
                                        if (data.orders[group].sub_group[subGroup].sold[sold].extra) {
                                            html += `<tr>
                                                <td class="text-center h6 font-weight-bold">Extra</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>`;
                                            for (let extra in data.orders[group].sub_group[subGroup].sold[sold].extra) {
                                                html += `<tr class="table-secondary">
                                                    <td  class="text-right">${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].name} (E) </td>
                                                    <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].quan}</td>
                                                    <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price / data.orders[group].sub_group[subGroup].sold[sold].extra[extra].quan}</td>
                                                    <td>${data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price}</td>
                                                </tr>`;
                                                subGroupTotal += +data.orders[group].sub_group[subGroup].sold[sold].extra[extra].price;
                                            }
                                        }
                                    }



                                    if(ex_de == 'Details' || ex_de == 'with_ex_de') {
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
                                                    <td>${data.orders[group].sub_group[subGroup].sold[sold].details[detail].price / data.orders[group].sub_group[subGroup].sold[sold].details[detail].quan}</td>
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
                    paging: false,
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
                        {
                            extend: 'excel',
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae}
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
                        },
                        {
                            extend: 'pdfHtml5',
                            download: 'open',
                            pageSize: 'A4',
                            title: title,
                            messageTop: function() {
                                let pdfMsg = `Restaurant Name : ${data.res_nmae} \n`
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
                                let textAlign = null;
                                doc.pageMargins = [10,10];
                                doc.defaultStyle.font = "Cairo";
                                doc.defaultStyle.fontSize = 12;
                                // doc.content[0].margin = [0,0,0,5]
                                doc.content[1].lineHeight = 0.7
                                // doc.styles.title.fontSize = 16;
                                // doc.styles.title.bold = true;
                                doc.styles.tableHeader.fillColor = '#159d71';
                                doc.styles.tableHeader.color = 'white';
                                doc.styles.tableHeader.alignment = "center";
                                doc.styles.tableBodyEven.alignment = "center";
                                doc.styles.tableBodyOdd.alignment = "center";
                                doc.styles.tableFooter.alignment = "center";
                                // doc.content.splice( 1, 0, {
                                //     alignment: 'left',
                                //     text: `Restaurant Name : ${data.res_nmae}`
                                // });
                                // textAlign = 'right'
                                // if(transLabel.length > 0) {
                                //     doc.content.splice( -2, 0, {
                                //         alignment: textAlign,
                                //         text: `Transaction : ${transLabel.join(' , ')}`
                                //     });
                                //     textAlign = 'left'
                                // }
                                // if(bay_way_label.length > 0) {
                                //     doc.content.splice( -2, 0, {
                                //         alignment: textAlign,
                                //         text: `Bay Way : ${bay_way_label.join(' , ')}`
                                //     });
                                // }
                                // console.log(doc)
                                doc.content[doc.content.length - 1].table.widths = Array(doc.content[doc.content.length - 1].table.body[0].length + 1).join('*').split('');
                            },
                        },
                        {
                            extend: 'print',
                            footer: true,
                            customize: function ( win ) {
                                let message = `<div> <p style="margin: 0">Restaurant Name : ${data.res_nmae}</p>`
                                transLabel.length > 0 ? message += `<div class="d-flex align-items-center justify-content-between flex-wrap"><span class="d-block w-50">Transaction : ${transLabel.join(' , ')}</span>` : ''
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
                // document.getElementById('report-output').firstChild.remove();
            },
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

</script>
<?php /**PATH C:\xampp\htdocs\webpoint\resources\views/includes/reports/sales_current_reports.blade.php ENDPATH**/ ?>
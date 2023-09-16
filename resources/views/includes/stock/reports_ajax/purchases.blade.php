@include('includes.Stock_Ajax.public_function')
<script>
    let branch = $('#branch');
    let fromSection = $('#fromSection');
    let toSection = $('#toSection');
    let fromStore = $('#fromStore');
    let toStore = $('#toStore');
    let supplier = $('#supplier');
    let dateFrom = $('#date_from');
    let dateTo = $('#date_to');
    let showTransferReport = $('#showTransferReport');
    $(document).ready(function() {
        $('select').select2({
            selectOnClose: true,
            dir: "rtl"
        });
        /*  ======================== Start Change Purchases Method ============================== */
        $('input[name="purchases_method"]').on('change', function() {
            let type = $(this).val()
            if (type === 'section') {
                $('.branch-sec').removeClass('d-none')
                $('.stores').addClass('d-none')
            } else if(type === 'store') {
                $('.branch-sec').addClass('d-none')
                $('.stores').removeClass('d-none')
            }
        });
        /*  ======================== End Change Purchases Method ============================== */
        /*  ======================== Start Change Branch ============================== */
        branch.on('change',function() {
            $.ajax({
                url: "{{route('changePurchasesBranch')}}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function(data) {
                    let html = `<option value="" disabled selected></option>
                    <option value="all">All</option>`;
                    data.sections.forEach((section) => {
                        html += `<option value="${section.id}">${section.name}</option>`
                    });
                    fromSection.html(html)
                    toSection.html(html)
                },
            });
        });
        /*  ======================== End Change Branch ============================== */
        showTransferReport.on('click', function() {
            let type = $('input[name="purchases_method"]:checked').val()
            $.ajax({
                url: "{{route('reports.purchases.report')}}",
                method: 'post',
                data: {
                    _token,
                    type,
                    branch: branch.val(),
                    section: fromSection.val(),
                    store: fromStore.val(),
                    dateFrom: dateFrom.val(),
                    dateTo: dateTo.val(),
                    supplier:supplier.val()

                },
                success: function(data) {
                    if (data.status == true) {
                        let html = `<table class="report_table table table-striped w-100">
                        <thead>
                            <tr>
                                <th> رقم الإذن </th>
                                <th> التاريخ </th>
                                <th> كود الصنف </th>`

                        if (type === 'store') {
                            html += `
                                    <th> المخزن </th>
                                    <th> المورد </th>`
                        } else {
                            html += `
                                    <th> القسم </th>
                                    <th>  المورد </th>`
                        }

                        html +=`<th> اسم الصنف </th>
                                <th> وحدة القياس </th>
                                <th> التكلفه </th>
                                <th> الكمية </th>
                                <th> الإجمالى </th>
                            </tr>
                        </thead>
                        <tbody>`;
                        data.purchases.forEach(transfer => {
                            html += `<tr>
                            <td> ${transfer.order_id} </td>
                            <td> ${transfer.main.date} </td>
                            <td> ${transfer.code} </td>`
                            if (type === 'store') {
                                html += `
                                <td> ${transfer.main.store.name} </td>
                                <td> ${transfer.main.supplier.name} </td>`
                            } else {
                                html += `
                                <td> ${transfer.main.section.name} </td>
                                <td> ${transfer.main.supplier.name} </td>`
                            }
                            html += `<td> ${transfer.name} </td>
                            <td> ${transfer.unit} </td>
                            <td class="price"> ${transfer.price} </td>
                            <td class="qty"> ${transfer.qty} </td>
                            <td class="total"> ${transfer.total} </td>
                        </tr>`;
                        });
                        html += ` </tbody></table>`
                        $('#report_content').html(html)
                        $(".report_table").DataTable({
                            // scrollY: '405px',
                            paging: true,
                            dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-6'i><'.col-6'p>>",
                            buttons: [
                                "copy",
                                "csv",
                                "excel",
                                {
                                    extend: "pdfHtml5",
                                    download: "open",
                                    // title: title.split(' ').reverse().join('  '),
                                    exportOptions: {
                                        orthogonal: "PDF",
                                    },
                                    customize: function (doc,s) {
                                        doc.content[1].table.body[0].forEach(row => {
                                            row.text = row.text.split(' ').reverse().join('  ');
                                        })
                                        doc.pageMargins = [10,5];
                                        doc.content[0].margin = [0,0]
                                        doc.defaultStyle.font = "Cairo";
                                        doc.defaultStyle.alignment = "center";
                                        doc.styles.tableHeader.fillColor = '#159d71';
                                        doc.styles.tableHeader.color = 'white';
                                        doc.styles.tableHeader.fontSize = 10;
                                        doc.defaultStyle.fontSize = 10;
                                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                                    },
                                },
                                "print",
                            ],
                            columnDefs: [{
                                targets: [1],
                                render: function(data, type, row) {
                                    if (type === 'PDF') {

                                        if(!/[a-z]/.test(data)) {
                                            return data.split(' ').reverse().join('  ');
                                        }
                                    }
                                    return data;
                                }
                            }]
                        });
                    }
                },
            });
        });
    })
</script>

@include('includes.stock.Stock_Ajax.public_function')
<script>
    let branch = $('#branch');
    let sections = $('#sections');
    let stores = $('#stores');
    let dateFrom = $('#date_from');
    let dateTo = $('#date_to');
    let tbody = $('tbody');
    let showExchangeReport = $('#showExchangeReport');
$(document).ready(function() {

    $('select').select2({
        selectOnClose: true,
        dir: "rtl"
    })
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
                        <option value="all"code="">All</option>`;
                    data.sections.forEach((section) => {
                        html += `<option value="${section.id}">${section.name}</option>`
                    });
                    sections.html(html)
                },
            });
    });
    /*  ======================== End Change Branch ============================== */
    showExchangeReport.on('click', function() {
        $.ajax({
            url: "{{route('reports.exchange.report')}}",
            method: 'post',
            data: {
                _token,
                branch: branch.val(),
                sections: sections.val(),
                stores: stores.val(),
                dateFrom: dateFrom.val(),
                dateTo: dateTo.val(),
            },
            success: function(data) {
                if (data.status == true) {
                    let html = `<table class="report_table table table-striped w-100">
                        <thead>
                            <tr>
                                <th> رقم الإذن </th>
                                <th> التاريخ </th>
                                <th> كود الصنف </th>
                                <th> القسم </th>
                                <th> اسم الصنف </th>
                                <th> وحدة القياس </th>
                                <th> التكلفه </th>
                                <th> الكمية </th>
                                <th> الإجمالى </th>
                            </tr>
                        </thead>
                        <tbody>`;
                    data.exchanges.forEach(exchange => {
                        html += `<tr>
                            <td> ${exchange.order_id} </td>
                            <td> ${exchange.main.date} </td>
                            <td> ${exchange.code} </td>
                            <td> ${exchange.main.section.name} </td>
                            <td> ${exchange.name} </td>
                            <td> ${exchange.unit} </td>
                            <td class="price"> ${exchange.price} </td>
                            <td class="qty"> ${exchange.qty} </td>
                            <td class="total"> ${exchange.total} </td>
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

    /*  ============================= Start Calc Footer Table */
    function calcTotal() {
        let sumPrice = 0
        let sumQty = 0
        let sumTotal = 0


        tbody.find('.price').each(function() {
            sumPrice += +$(this).text();
        });
        tbody.find('.qty').each(function() {
            sumQty += +$(this).text();
        });
        tbody.find('.total').each(function() {
            sumTotal += +$(this).text();
        });

        $('.sumPrice').text(sumPrice)
        $('.sumQty').text(sumQty)
        $('.sumTotal').text(sumTotal)
    }
    /*  ============================= End Calc Footer Table */
});
</script>

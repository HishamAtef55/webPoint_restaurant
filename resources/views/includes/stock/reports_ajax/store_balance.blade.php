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
    // limitMinBtn.on('click', function() {
    //     let title = $(this).text();
    //     $.ajax({
    //         url: "{{route('itemsWithOutMaterials')}}",
    //         method: 'post',
    //         data: {
    //             _token,
    //             branch:branch.val()
    //         },
    //         success: function(data) {
    //             if (data.status == true) {
    //                 let html = `<table class="report_table table table-striped w-100">
    //                     <thead>
    //                         <tr>
    //                             <th>كود الصنف</th>
    //                             <th>اسم الصنف</th>
    //                             <th>وحدة القياس</th>
    //                             <th>الكمية</th>
    //                             <th>التكلفة</th>
    //                             <th>الاجمالى</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>`;
    //                 data.data.forEach(item => {
    //                     html += `<tr>
    //                             <td>${item.id}</td>
    //                             <td>${item.name}</td>
    //                             <td>${item.price}</td>
    //                             <td>${item.price}</td>
    //                             <td>${item.price}</td>
    //                             <td>${item.cost_price || 0}</td>
    //                         </tr>`
    //                 });
    //                 html += `</tbody></table>`;
    //                 $('#report_content').html(html)
    //                 $(".report_table").DataTable({
    //                     scrollY: '405px',
    //                     dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-12'i>>",
    //                     buttons: [
    //                         "copy",
    //                         "csv",
    //                         "excel",
    //                         {
    //                             extend: "pdfHtml5",
    //                             download: "open",
    //                             customize: function (doc) {
    //                                 doc.defaultStyle.font = "Cairo";
    //                                 doc.styles.tableBodyEven.alignment = "center";
    //                                 doc.styles.tableBodyOdd.alignment = "center";
    //                                 doc.styles.tableBodyEven.lineHeight = "1.5";
    //                                 doc.styles.tableBodyOdd.lineHeight = "1.5";
    //                                 doc.styles.tableFooter.alignment = "center";
    //                                 doc.styles.tableHeader.alignment = "center";
    //                             },
    //                         },
    //                         "print",
    //                     ],
    //                 });
    //             } else {
    //                 Toast.fire({
    //                     icon: 'error',
    //                     title: data.data
    //                 });
    //             }
    //         },
    //     });
    // });
    /*  ======================== End limit Min Report ============================== */
    /*  ======================== Start limit Min Report ============================== */
    let newArr = [1,2,3,4,56,6,4,5,7,8,9,7,4,1,5,2,8,2,4,8,2,8,6,4,5];

    limitMinBtn.on('click', function() {
        let title = $(this).text();
        let html = `<table class="report_table table table-striped w-100">
                        <thead>
                            <tr>
                                <th>كود الصنف</th>
                                <th>اسم الصنف</th>
                                <th>وحدة القياس</th>
                                <th>الكمية</th>
                                <th>التكلفة</th>
                                <th>الاجمالى</th>
                            </tr>
                        </thead>
                        <tbody>`;
                        newArr.forEach(item => {
                        html += `<tr>
                                <td>1</td>
                                <td>ibrahim</td>
                                <td>20</td>
                                <td>20</td>
                                <td>20</td>
                                <td>20</td>
                            </tr>`
                    });
                    html += `</tbody></table>`;
                    $('#report_content').html(html)
                    $(".report_table").DataTable({
                        scrollY: '405px',
                        paging: true,
                        dom: "<'.row'<'.col-md-6 mb-2'f><'.col-md-6 report_setting text-start mb-2'B><'.col-12 mt-2 text-center't><'.col-6'i><'.col-6'p>>",
                        buttons: [
                            "copy",
                            "csv",
                            "excel",
                            {
                                extend: "pdfHtml5",
                                download: "open",
                                title: title.split(' ').reverse().join('  '),
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
                                    doc.styles.tableHeader.fontSize = 12;
                                    doc.defaultStyle.fontSize = 12;
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
    });
    /*  ======================== End limit Min Report ============================== */
});
</script>

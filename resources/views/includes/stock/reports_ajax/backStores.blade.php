@include('includes.stock.Stock_Ajax.public_function')
<script>
    let branch = $('#branch');
    let section = $('#sections');
    let store = $('#store');
    let dateFrom = $('#date_from');
    let dateTo = $('#date_to');
    let showTransferReport = $('.showTransferReport');

    $(document).ready(function() {
        /*  ======================== Start Change Branch ============================== */
        branch.on('change', function() {
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
                    section.html(html)
                },
            });
        });
        /*  ======================== End Change Branch ============================== */
        showTransferReport.on('click', function() {
            let reportType = $(this).attr('data-request');
            $.ajax({
                url: "{{route('reports.backStores.report')}}",
                method: 'post',
                data: {
                    _token,
                    reportType,
                    branch: branch.val(),
                    section: section.val(),
                    store: store.val(),
                    dateFrom: dateFrom.val(),
                    dateTo: dateTo.val(),

                },
                success: function(data) {
                    if (data.status == true) {}
                },
            });
        });
    });
</script>
@include('includes.stock.Stock_Ajax.public_function')
<script>
    let supplier = $('#supplier');
    let dateFrom = $('#date_from');
    let dateTo = $('#date_to');
    let showTransferReport = $('.showTransferReport');

    $(document).ready(function() {
        showTransferReport.on('click', function() {
            let reportType = $(this).attr('data-request');
            $.ajax({
                url: "{{route('reports.suppliers.report')}}",
                method: 'post',
                data: {
                    _token,
                    type,
                    supplier: supplier.val(),
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
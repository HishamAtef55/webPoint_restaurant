<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    let branch = $('#branch');
    let section = $('#section');
    let store = $('#store');
    let dateFrom = $('#date_from');
    let dateTo = $('#date_to');
    let showTransferReport = $('.showTransferReport');
    $(document).ready(function() {
        /*  ======================== Start Change Purchases Method ============================== */
        $('input[name="halk_method"]').on('change', function() {
            let type = $(this).val()
            if (type === 'section') {
                $('.branch-sec').removeClass('d-none')
                $('.stores').addClass('d-none')
            } else if (type === 'store') {
                $('.branch-sec').addClass('d-none')
                $('.stores').removeClass('d-none')
            }
        });
        /*  ======================== End Change Purchases Method ============================== */
        /*  ======================== Start Change Branch ============================== */
        branch.on('change', function() {
            $.ajax({
                url: "<?php echo e(route('changePurchasesBranch')); ?>",
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
            let type = $('input[name="halk_method"]:checked').val();
            let reportType = $(this).attr('data-request');
            $.ajax({
                url: "<?php echo e(route('reports.halk.report')); ?>",
                method: 'post',
                data: {
                    _token,
                    type,
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
    })
</script><?php /**PATH C:\xampp\htdocs\web_point\resources\views/includes/stock/reports_ajax/halk.blade.php ENDPATH**/ ?>
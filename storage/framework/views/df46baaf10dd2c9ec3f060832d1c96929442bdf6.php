<script>
let _token = $('input[name="_token"]').val();

$(document).ready(function(){
    $('#save').on('click',function (e) {
        e.preventDefault();
        let category  = $('#search_main_table').val();
        let amount    = $('#amount').val();
        let note      = $('#note').val();
        let title     = $('#title').val();
        $.ajax({
            url:"<?php echo e(route('DailyExpenses.save')); ?>",
            method:'post',
            data:
            {
                _token,
                category,
                amount,
                note,
                title
            },
            success:function(data)
            {
                if(data.status == 'true') {
                    $('#alert_show').show();
                    setTimeout(function () {
                        $('#alert_show').hide();
                    }, 2500);
                    $(`.left ul li`).remove();
                        location.href = '/webpoint/menu/Show_Table'
                }

            }
        });
    });
    
    $('.delExpenses').on('click',function (e) {
        e.preventDefault();
        let id = $(this).attr("rowId");
        $.ajax({
            url:"<?php echo e(route('DailyExpenses.delete')); ?>",
            method:'post',
            data:
            {
                _token,
                id,
            },
            success:function(data)
            {
                if(data.status == true) {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Expenses has been deleted',
                        showConfirmButton: false,
                        timer: 1250
                    });
                }
            }
        });
    });
});
</script>
<?php /**PATH C:\xampp\htdocs\webpoint\resources\views/includes/menu/Expenses.blade.php ENDPATH**/ ?>
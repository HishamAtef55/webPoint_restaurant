<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    let id           = $('#group_id');
    let name         = $('#group_name');

    $('#save_group').on('click', function() {
        $.ajax({
            url:"<?php echo e(route('save.main_groups')); ?>",
            method:'post',
            data:{
                _token,
                name: name.val(),
            },
            success:function(data)
            {
                if(data.status == 'true') {
                    id.val(data.new_group);
                    name.val('');
                    Swal.fire({
                        icon: 'success',
                        title: data.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
                if(data.status == 'false'){
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ.....',
                        text: data.msg,
                    });
                }
            },
            error: function (reject) {
                let response  = $.parseJSON(reject.responseText);
                $.each(response.errors , function (key, val) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ.....',
                        text: val[0],
                    });
                });
            }
        });
    });

    $('#group_name').on('keyup', function () {
        let query = $(this).val()
        searchDb('search_main_groups' , query, $(this));
    });

    $(document).on('click', '.search-result li', function(e) {
        e.stopPropagation();
        getData('get_main_groups', $(this).attr('data-id'), function(data) {
            id.val(data.id);
            name.val(data.name);
            $('#save_group').addClass('d-none');
            $('#update_group').removeClass('d-none');
            $('.search-result').html('');
        });
    });

    $('#update_group').on('click', function() {
        $.ajax({
            url:"<?php echo e(route('update.main_groups')); ?>",
            method:'post',
            data:{
                _token,
                id:id.val(),
                name: name.val(),
            },
            success:function(data)
            {
                if(data.status == 'true') {
                    id.val(data.new_group);
                    name.val('');
                    Swal.fire({
                        icon: 'success',
                        title: data.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function (reject) {
                let response  = $.parseJSON(reject.responseText);
                $.each(response.errors , function (key, val) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: val[0],
                    });
                });
            }
        });
    });
</script>
<?php /**PATH C:\xampp\htdocs\webpoint\resources\views/includes/stock/Stock_Ajax/mainGroup.blade.php ENDPATH**/ ?>
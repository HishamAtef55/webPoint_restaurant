<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    let id = $('#supplier_id');
    let name = $('#supplier_name');
    let phone = $('#supplier_phone');
    let address = $('#supplier_address');

    $('#save_supplier').on('click', function() {
        $.ajax({
            url: "<?php echo e(route('save.suppliers')); ?>",
            method: 'post',
            data: {
                _token,
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
            },
            success: function(data) {
                if (data.status == 'true') {
                    id.val(data.new_supplier);
                    name.val('');
                    phone.val('');
                    address.val('');
                    successMsg(data.msg);
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0]);
                });
            }
        });
    });

    $('#supplier_name').on('keyup', function() {
        let query = $(this).val()
        searchDb('search_suppliers', query, $(this));
    });

    $(document).on('click', '.search-result li', function(e) {
        e.stopPropagation();
        getData('get_suppliers', $(this).attr('data-id'), function(data) {
            id.val(data.id);
            name.val(data.name);
            phone.val(data.phone);
            address.val(data.address);
            $('#save_supplier').addClass('d-none');
            $('#update_supplier').removeClass('d-none');
            $('.search-result').html('');
        });
    });

    $('#update_supplier').on('click', function() {
        $.ajax({
            url: "<?php echo e(route('update.suppliers')); ?>",
            method: 'post',
            data: {
                _token,
                id: id.val(),
                name: name.val(),
                phone: phone.val(),
                address: address.val(),
            },
            success: function(data) {
                if (data.status == 'true') {
                    $('#store_id').val(data.new_supplier);
                    name.val('');
                    phone.val('');
                    address.val('');
                    successMsg(data.msg);
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0]);
                });
            }
        });
    });
</script><?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/stock/Stock_Ajax/suppliers.blade.php ENDPATH**/ ?>
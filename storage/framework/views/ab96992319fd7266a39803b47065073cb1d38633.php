<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    let id = $('#group_id');
    let name = $('#group_name');
    let from = $('#group_from');
    let to = $('#group_to');
    let main_group = $('#main_group');

    $('#save_group').on('click', function() {
        $.ajax({
            url: "<?php echo e(route('save.groups')); ?>",
            method: 'post',
            data: {
                _token,
                name: name.val(),
                from: from.val(),
                to: to.val(),
                main_group: main_group.val(),
            },
            success: function(data) {
                if (data.status == 'true') {
                    let optionName = main_group.find(`option[value="${main_group.val()}"]`).text()
                    let html = $(`<tr><th>${id.val()}</th><td>${optionName}</td><td>${name.val()}</td><td>${from.val()}</td><td>${to.val()}</td></tr>`)
                    $('tbody').find('tr.not-found').length ? $('tr.not-found').remove() : '';
                    $('tbody').append(html)
                    id.val(data.new_group);
                    name.val('');
                    from.val('');
                    to.val('');
                    Swal.fire({
                        icon: 'success',
                        title: data.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    checkForm();
                }
                if (data.status == 'false') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ.....',
                        text: data.msg,
                    });
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ.....',
                        text: val[0],
                    });
                });
            }
        });
    });

    $('#group_name').on('keyup', function() {
        let query = $(this).val()
        let groupMain = $('#main_group').val()
        searchDb('search_groups', query, $(this), groupMain);
    });

    $(document).on('click', '.search-result li', function(e) {
        e.stopPropagation();
        getData('get_groups', $(this).attr('data-id'), function(data) {
            id.val(data.id);
            name.val(data.name);
            from.val(data.start_serial);
            to.val(data.end_serial);
            $('#save_group').addClass('d-none');
            $('#update_group').removeClass('d-none');
            $('.search-result').html('');
            checkForm();
        });
    });

    $('#update_group').on('click', function() {
        $.ajax({
            url: "<?php echo e(route('update.groups')); ?>",
            method: 'post',
            data: {
                _token,
                id: id.val(),
                name: name.val(),
                from: from.val(),
                to: to.val(),
                main_group: main_group.val(),
            },
            success: function(data) {
                if (data.status == 'true') {
                    id.val(data.new_group);
                    name.val('');
                    from.val('');
                    to.val('');
                    Swal.fire({
                        icon: 'success',
                        title: data.msg,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    checkForm();
                    $('#save_group').removeClass('d-none');
                    $('#update_group').addClass('d-none');
                }
                if (data.status == 'false') {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ.....',
                        text: data.msg,
                    });
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
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
<?php /**PATH C:\xampp\htdocs\web_point\resources\views/includes/stock/Stock_Ajax/groups.blade.php ENDPATH**/ ?>
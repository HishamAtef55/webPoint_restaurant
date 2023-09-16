<script>
    $('#save_res').on('click',function (e) {
        e.preventDefault();
        let allInputs = $(this).parents('form').find('.input-empty input');
        let textArea = $(this).parents('form').find('textarea');
        let allLabels = $(this).parents('form').find('.input-empty label');
        let allLines = $(this).parents('form').find('.input-empty span');

        let formData = new FormData($('#form_save_resturant')[0]);

        $.ajax({
            url:"<?php echo e(route('save.information')); ?>",
            method:'post',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data' : formData,
            success: function (data) {
                if(data.status == 'true') {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1250
                    });

                    // For Remove All Values After Save ====== Start
                    allInputs.each(function() {
                        $(this).val('')
                    });

                    textArea.val('');

                    $('i.reset').click();

                    allLabels.each(function() {
                        $(this).removeClass('focused')
                    });

                    allLines.each(function() {
                        $(this).removeClass('fill')
                    });
                }
            }
        });
    });

    $(document).ready(function() {
        $('#select').on('change', function() {
            let _token = $('input[name="_token"]').val();
            let branch = $(this).val();
            $.ajax({
                'type':'POST',
                'url':"<?php echo e(Route('get.information')); ?>",
                'data':
                {
                    'branch'  : branch,
                    '_token': "<?php echo e(csrf_token()); ?>",
                },
                success:function(data) {
                    $('#res-name').focus().val(data.information.name);
                    $('#res-phone').focus().val(data.information.phone);
                    $('#note').focus().val(data.information.note);
                    $('.res-image').html(`<label for="res-image" class="image" data-label="Resturant Image" style="background-image:url(<?php echo e(URL::asset('control/images/information')); ?>/${data.information.image})"></label>`);
                    $('.slogan-image').html(`<label for="slogan-image" class="image" data-label="Slogan Image" style="background-image:url(<?php echo e(URL::asset('control/images/information')); ?>/${data.information.slogan})"></label>`);
                    $('#save_res').addClass('d-none');
                    $('#update_res').removeClass('d-none');
                }
            });
        });
    });

    $('#update_res').on('click',function (e) {
        e.preventDefault();

        let formData = new FormData($('#form_save_resturant')[0]);

        $.ajax({
            url:"<?php echo e(route('update.information')); ?>",
            method:'post',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data' : formData,
            success: function (data) {
                if(data.status == 'true') {
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 1250
                    });
                }
            }
        });
    });
</script>
<?php /**PATH D:\MyWork\Res\ERP\webPoint\resources\views/includes/control/information.blade.php ENDPATH**/ ?>
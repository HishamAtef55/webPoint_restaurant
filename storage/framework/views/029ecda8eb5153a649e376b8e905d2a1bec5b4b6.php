<script>
    function createReservation(name, phone, time, cash, parent) {
        let reserveLength = parent.find('.resrvation-menu ul li').length;
        let ReserveIcon = $(`<span class="reservation-time" test='${reserveLength + 1}'></span>`);


        ReserveIcon.appendTo(parent.find('.table-properties'))

        let Item        = $(`<li class="reserved-item" resId="${phone}${time}" data-toggle="modal" data-target="#Reservation_modal" ></li>`);
        let userName    = $(`<span> <i class="fas fa-user-clock"></i> ${name} </span>`);
        let phoeNumber  = $(`<span> <i class="fas fa-phone-alt"></i> ${phone} </span>`);
        let ReserveTime = $(`<span> <i class="far fa-clock"></i> ${time} </span>`);
        let CashNumber  = $(`<span> <i class="fas fa-money-bill-wave"></i> ${cash} </span>`);

        Item.prependTo(parent.find('.resrvation-menu ul'));
        userName.appendTo(Item);
        phoeNumber.appendTo(Item);
        ReserveTime.appendTo(Item);
        CashNumber.appendTo(Item);
    }

    $(document).on('click','#reservation_table',function (e) {
        e.preventDefault();
        let formData      = new FormData($('#form_save_reservation')[0]);
        let CashNumber    = $('#cash-input').val();
        let userName      = $('#userName-input').val();
        let phoneNumber   = $('#phoneNumber-input').val();
        let reserveTime   = $('#time-input').val();
        let reserveDate   = $('#date-input').val();
        let ModalNumber   = $(this).parents('.modal').attr('table');
        let myTable       = $(`.table-name:contains('${ModalNumber}')`).parents('.table');
        let today         = new Date().toISOString().slice(0, 10);
        // let newDate       = new Date()
        // let time          = `${newDate.getHours()}:${newDate.getMinutes()}`;

        $.ajax({
            url:"<?php echo e(route('save.reservation')); ?>",
            method:'post',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data' : formData,
            success: function (data) {
                if(data.status == 'true') {
                    if (today === reserveDate) {
                        myTable.find('.reservation-time').remove();
                        createReservation(userName, phoneNumber, reserveTime, CashNumber, myTable);
                    }
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your Reservation has been saved',
                        showConfirmButton: false,
                        timer: 1250
                    });
                }
                if (data.msg) {
                    myTable.addClass('other-user')
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    });
                }
                $('#Reservation_modal').modal('hide');
            },
            error: function (reject) {
                var response  = $.parseJSON(reject.responseText);
                $.each(response.errors , function (key, val)
                {
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
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/includes/menu/reservation.blade.php ENDPATH**/ ?>
<?php
$title = 'Tabels';
?>



<?php $__env->startSection('content'); ?>
<?php echo $__env->make('includes.menu.sub_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div id="new_page"></div>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('open-tables')): ?>
<div id="check-per-user" value="true"></div>
<?php endif; ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('open-tables')): ?>
<div id="check-per-user" value="false"></div>
<?php endif; ?>

<section class='tables'>
    <audio src="<?php echo e(asset('menu/sound/notification.mp3')); ?>" loop="" id="noti_sound"></audio>
    <?php if(session()->has('data_back')): ?>
    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: 'Not Possible',
                text: "<?php echo e(session()->get('data_back')); ?>",
            });
        });
    </script>
    <?php endif; ?>
    <?php if(isset($transfers)): ?>
    <?php if($transfers->count() > 0): ?>
    <script>
        $(document).ready(function() {

            $('#noti_sound').get(0).play();
            Swal.fire({
                title: 'Transfer',
                text: "You Have Transfer For New Table ",
                backdrop: false,
                icon: 'info',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#noti_sound').get(0).pause();
                }
            })
        });
    </script>
    <?php endif; ?>
    <?php endif; ?>
    <!-- Table Merge Modal -->
    <div class="modal fade table-merge-modal" id="table-merge-modal" tabindex="-1" role="dialog" aria-labelledby="tableMergeModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"> Tables Merge </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> Close </button>
                    <button type="button" class="btn btn-success" id="save-merge"> Save changes </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table transfer Modal -->
    <div class="modal fade table-transfer-modal" id="transfer_modal" tabindex="-1" role="dialog" aria-labelledby="tableMergeModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"> Tables transfer </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"> &times; </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="captens d-flex flex-wrap"></div>
                    <div class="form-row w-100">
                        <div class="w-100 mt-3">
                            <textarea name="notes_transfer" class="form-control" placeholder="Write Your Note"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> Close </button>
                    <button type="button" class="btn btn-success" id="save-transfer"> Transfer </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Min Charge Modal -->
    <div class="modal fade" id="min_charge_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Min Charge</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="minchrage-input">Min-Charge</label>
                        <input type="number" class="form-control use-keyboard-input" id="minchrage-input" name="min_charge">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="save_mincharge_table">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation Modal -->
    <div class="modal fade Reservation_modal" id="Reservation_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ReservationLabel">Reservation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_save_reservation" action=" " method="POST" multiple enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <nav>
                            <div class="nav nav-tabs" role="tablist">
                                <a class="nav-item nav-link active" id="Reserving-tab" data-toggle="tab" href="#Reserving" role="tab" aria-controls="Reserving" aria-selected="true">Reserving</a>
                                <a class="nav-item nav-link" id="Reservation-tab" data-toggle="tab" href="#Reserved" role="tab" aria-controls="Reserved" aria-selected="false">Reserved</a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active px-3 py-2" id="Reserving" role="tabpanel" aria-labelledby="Reserving-tab">
                                <div class="form-group">
                                    <label for="userName-input">Name</label>
                                    <input type="text" class="form-control use-keyboard-input" id="userName-input" name="userName">
                                    <input type="hidden" name="table_id">
                                </div>
                                <div class="form-group">
                                    <label for="phoneNumber-input">Phone</label>
                                    <input type="number" class="form-control use-keyboard-input" id="phoneNumber-input" name="phone_number">
                                </div>
                                <div class="form-group">
                                    <label for="cash-input">Cash</label>
                                    <input type="number" class="form-control use-keyboard-input" id="cash-input" name="cash">
                                </div>
                                <div class="form-group">
                                    <label for="date-input">Date</label>
                                    <input type="date" class="form-control" id="date-input" dataformatas="dd/mm/yyyy" name="date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="d-flex">
                                    <div class="form-group flex-grow-1 mr-3">
                                        <label for="time-input">From</label>
                                        <input type="time" class="form-control" id="time-input" name="time_from" value="<?php echo date('H:i'); ?>">
                                    </div>
                                    <div class="form-group flex-grow-1">
                                        <label for="time-input">To</label>
                                        <input type="time" class="form-control" id="time-input_to" name="time_to">
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade px-3 py-2" id="Reserved" role="tabpanel" aria-labelledby="Reservation-tab">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Cash</th>
                                            <th>Date</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th></th>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="reservation_table">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Add Other Table Modal -->
    <div class="modal fade" id="add_other_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Table</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="add_other_input">Table Name</label>
                        <input type="text" class="form-control use-keyboard-input" id="add_other_input">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="save_other_table">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pay & Print Modal -->
    <div class="modal fade pay-modal" id="pay-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">PAY</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <section class='check-out'>
                        <div class='container'>
                            <div class='row'>
                                <div class="col-lg-4 col-md-5 col-6">
                                    <div class='summary'>
                                        <h2>Order Summary</h2>
                                        <ul class="list-unstyled">

                                            <li class='last-item'>
                                                <div>
                                                    <span>Items</span>
                                                    <span class="items-quant"></span>
                                                </div>
                                                <div class='total'>
                                                    <span>Sub Total</span>
                                                    <span class="summary-total"></span>
                                                </div>
                                                <div class='service'>
                                                    <span>Service</span>
                                                    <span class="summary-service"></span>
                                                </div>
                                                <div class='tax'>
                                                    <span>Tax</span>
                                                    <span class="summary-tax"></span>
                                                </div>
                                                <div class='bank'>
                                                    <span>Bank Value</span>
                                                    <span class="summary-bank">0.00</span>
                                                </div>
                                                <div class='min-charge'>
                                                    <span>Min-Charge</span>
                                                    <span class="summary-mincharge"></span>
                                                </div>
                                                <div class='discount'>
                                                    <span>Discount</span>
                                                    <span class="summary-discount"></span>
                                                </div>
                                                <div class='total'>
                                                    <span>Total</span>
                                                    <span class="all-total"></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class='col-lg-8 col-md-7 col-6'>
                                    <div class="checkout">
                                        <ul class="nav nav-pills  justify-content-center align-items-center" id="pay-tab" role="tablist">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("cash")): ?>
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link active" id="cash-tab" data-toggle="pill" href="#cash" role="tab" aria-controls="cash" aria-selected="true">

                                                    <i class="fas fa-money-bill-wave"></i>

                                                    <span>Cash</span>

                                                </a>

                                            </li>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("credit")): ?>
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="credit-tab" data-toggle="pill" href="#credit" role="tab" aria-controls="credit" aria-selected="false">

                                                    <i class="far fa-credit-card"></i>

                                                    <span>Credit</span>

                                                </a>

                                            </li>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("hospatility")): ?>
                                            <li class="nav-item" role="presentation">

                                                <a class="nav-link" id="hospitality-tab" data-toggle="pill" href="#hospitality" role="tab" aria-controls="hospitality" aria-selected="false">

                                                    <i class="fas fa-hotel"></i>

                                                    <span>Hospitality</span>

                                                </a>

                                            </li>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">

                                            <div class="tab-pane fade show active pay-method" id="cash" role="tabpanel" aria-labelledby="cash-tab">
                                                <div class="cash-content">
                                                    <div>
                                                        <span class='text-white'>Remainder</span>
                                                        <h2 class='price summary-price' id='cash-total-price'></h2>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cash-price" class='text-white'>Cash</label>
                                                        <input type="number" min="0" class="form-control price-value" id="cash-price">
                                                    </div>
                                                    <div>
                                                        <span class='text-white'>Rest</span>
                                                        <h3 class='price-rest'>0.00</h3>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="cash-services" class='text-white'>Services</label>
                                                    <input type="number" min="0" class="form-control input-ser" id="cash-services">
                                                </div>
                                            </div>

                                            <div class="tab-pane fade pay-method" id="credit" role="tabpanel" aria-labelledby="credit-tab">
                                                <div class="cash-content">
                                                    <div>
                                                        <span class='text-white'>Remainder</span>
                                                        <h2 class='price summary-price' id="credit-total-price"></h2>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="visa-price" class='text-white'>Visa</label>
                                                        <input type="number" min="0" class="form-control price-value" id="visa-price">
                                                    </div>
                                                    <div>
                                                        <span class='text-white'>Rest</span>
                                                        <h3 class='price-rest'>0.00</h3>
                                                    </div>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="bank-ratio" class='text-white'>bank ratio</label>
                                                    <input type="number" min="0" class="form-control" id="bank-ratio" data-bank="" disabled>
                                                </div>
                                                <div class="form-group mt-3">
                                                    <label for="visa-services" class='text-white'>Services</label>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("change service")): ?>
                                                    <input type="number" min="0" class="form-control input-ser" id="visa-services">
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="hospitality" role="tabpanel" aria-labelledby="hospitality-tab">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("print check")): ?>
                    <button class="btn btn-info" type="button" id="printcheck">Print</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <div class='tabs table-tabs'>
        <ul class="nav nav-tabs nav-hole" id="holes" role="tablist">
            <?php echo csrf_field(); ?>
            <?php $__currentLoopData = $holes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check($hole->name . '-hole')): ?>
            <?php if($hole->name == "Other"): ?>
            <li class="nav-item" style="order: 100">
                <a hole="<?php echo e($hole->number_holes); ?>" class="nav-link" data-toggle="tab" href="#hole<?php echo e($hole->number_holes); ?>" role="tab" aria-controls="first" aria-selected="true"><?php echo e($hole->name); ?></a>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a hole="<?php echo e($hole->number_holes); ?>" class="nav-link" data-toggle="tab" href="#hole<?php echo e($hole->number_holes); ?>" role="tab" aria-controls="first" aria-selected="true"><?php echo e($hole->name); ?></a>
            </li>
            <?php endif; ?>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        <div class="tab-content p-5 text-center" id="holesContent">
            <?php $__currentLoopData = $holes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tab-pane fade" id="hole<?php echo e($hole->number_holes); ?>" role="tabpanel" aria-labelledby="first-tab" data-hole='hole<?php echo e($hole->number_holes); ?>'>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

    </div>

</section>
<div id="id_branch" value="<?php echo e(Auth()->user()->branch_id); ?>"></div>

<script type='module'>
    let _token = $('input[name="_token"]').val();

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2)
            month = '0' + month;
        if (day.length < 2)
            day = '0' + day;

        return [year, month, day].join('-');
    }
    // ################################# Start search holes ###################
    $(".nav-hole a").click(function(e) {
        e.preventDefault();
        $('#holesContent').find('.tab-pane').each(function() {
            $(this).html('')
        });
        let holeName = $(this).text();
        let user = $('#id_user').attr('user')
        let hole_num = $(this).attr('hole');
        let _token = $('input[name="_token"]').val();
        $.ajax({
            url: "<?php echo e(route('search.holes')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                hole_num: hole_num
            },
            success: function(data) {
                let html = '';
                for (var count = 0; count < data.length; count++) {
                    let num_res = 0;
                    let today = 0;
                    let res = 0;
                    for (var i = 0; i < data[count].reservation.length; i++) {
                        let today = new Date().toLocaleString().slice(0, 10)
                        let res = data[count].reservation[i].date;
                        if (formatDate(today) == res) {
                            if (data[count].reservation[i].status == 0) {
                                num_res = num_res + 1
                            }
                        }
                    }

                    let tableNumber = data[count].number_table;
                    let merged = '';
                    let booked_up = '';
                    let circle = '';
                    let master = '';
                    let class_user = '';
                    let table_user = $('#check-per-user').attr('value');
                    let colortable = data[count].printcheck;
                    let merged_check = '';
                    if (data[count].follow > 0) {
                        merged_check = "checked";
                    }
                    if (data[count].merged == '1') {
                        merged = "merged";
                    }
                    if (data[count].circle == 1) {
                        circle = "circle";
                    }
                    if (data[count].booked_up == 1) {
                        booked_up = "busy";
                    }
                    if (data[count].master == 1) {
                        master = "master";
                    }
                    // if(data[count].user_id == user){
                    //     table_user = 'user';
                    // }
                    // else if(data[count].user_id == null || data[count].user_id == 0){
                    //     table_user = 'empty';
                    // }
                    if (data[count].user_id != 0) {
                        if (data[count].user_id != user) {
                            if (table_user == "false") {
                                class_user = "other-user"
                            }
                        }
                    }

                    // if(table_user == 'false' || ){
                    //     class_user = "other-user"
                    // }
                    html +=
                        `<div colorcheck="${colortable}" state="${data[count].state}"  booked="${data[count].booked_up}" follow="${data[count].follow}" style="width:${data[count].width}px;height:${data[count].height}px;top:${data[count].top}px;left:${data[count].left}px;background-image: url(<?php echo e(asset('global/image/logo-small.png')); ?>)" hole="${data[count].hole}" number_of_tables = "${data[count].number_table}" class="table ${class_user} ${merged} ${circle} ${booked_up} ${master} ${holeName === 'Other' ? 'other-table' : ''}">`;
                    let timeIndex = data[count].updated_at.indexOf('T');
                    let oldHour = data[count].updated_at.substr(timeIndex + 1, 2) * 60;
                    let oldMinuts = parseInt(data[count].updated_at.substr(timeIndex + 4, 2));

                    html +=
                        `<div time="${oldHour + oldMinuts}" class='table-body' min-charge="${data[count].min_charge}">`
                    html += `<span class="table-name">${tableNumber}</span>`
                    html +=
                        `<span class="table-Guest"> <i class="fas fa-chair"></i>  ${data[count].no_of_gest} </span>`
                    html += `</div>`
                    let follow = '';
                    let followed = ''
                    if (data[count].follow != 0) {
                        follow = data[count].follow;
                        followed = 'followed'
                    }
                    html += `<div class='table-properties'>`
                    html += `<span class="table-status"></span>`
                    html += `<span class="merge-table ${followed}" follow="${follow}" data-toggle="modal" data-target="#table-merge-modal"></span>`
                    if (num_res > 0) {
                        html += `<span class="reservation-time" test='${num_res}'></span>`
                    }
                    if (data[count].main_hole.name === "Other") {
                        if (data[count].state == 0) {
                            html += `<span class="delete-table" branch="${data[count].branch_id}" hole="${data[count].main_hole.number_holes}"></span>`
                        }
                    }
                    html += `</div>`
                    html += `<div class='table-menu'>`
                    html += `<ul>`
                    html += `
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("accupy")): ?>
                            <li class='occupy'>
                                <a href="#">
                                    <i class="fas fa-lock fa-fw"></i>
                                    <span> Occupy </span>
                                </a>`
                    html += `</li><?php endif; ?>`

                    html += `
                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("to order")): ?>
                            <li class='new-order'>
                                <a href="<?php echo e(url('menu/New_Order/Table-${tableNumber}')); ?>">
                                    <i class="fas fa-plus-circle fa-fw"></i>
                                    <span> To Order </span>
                                </a>`
                    html += `</li><?php endif; ?>`

                    html += `
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("reservation")): ?>
                            <li data-toggle="modal" data-target="#Reservation_modal">
                                <a href="#">
                                    <i class="fas fa-sticky-note fa-fw"></i>
                                    <span> Reservation </span>
                                </a>`
                    html += `</li> <?php endif; ?>`

                    html += `
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("min-charge")): ?>
                            <li data-toggle="modal" data-target="#min_charge_modal">
                                <a href="#">
                                    <i class="fas fa-hand-holding-usd fa-fw"></i>
                                    <span> Min-Charge </span>
                                </a>`
                    html += `</li><?php endif; ?>`

                    html += `</ul>`
                    html += `</div>`

                    html += `<div class='resrvation-menu'>`
                    html += `<ul class="list-unstyled resLength">`
                    for (var x = 0; x < data[count].reservation.length; x++) {
                        let today = new Date().toLocaleString().slice(0, 10);
                        let res = data[count].reservation[x].date;

                        if (formatDate(today) == res) {

                            if (data[count].reservation[x].status == 0) {
                                html += `<li class='reserved-item' resId="${data[count].reservation[x].phone}${data[count].reservation[x].time_from}" data-toggle="modal" data-target="#Reservation_modal">`
                                html += `<span> <i class="fas fa-user-clock"></i> ${data[count].reservation[x].customer} </span>`
                                html += `<span> <i class="fas fa-phone-alt"></i> ${data[count].reservation[x].phone}</span>`
                                html += `<span> <i class="far fa-clock"></i> ${data[count].reservation[x].time_from}</span>`
                                html += `<span> <i class="fas fa-money-bill-wave"></i> ${data[count].reservation[x].cash}</span>`
                                html += `</li>`;
                            }
                        }
                    }

                    html += `</ul> `;

                    html += `</div>`
                    html += `<div class='table-info' order=''>`
                    html += `<h4 class="info-title"> ${tableNumber} </h4>`
                    html += `<div class="info-body">`
                    html += `<div>
                                <h5>Captain</h5>
                                <p id="captaintable${tableNumber}"></p>
                            </div>`
                    let num_gest = 'Null';
                    if (data[count].guest > 0) {
                        num_gest = data[count].guest
                    }
                    html += `<div>
                                    <h5>Guests</h5>
                                    <p id="gust${tableNumber}">${num_gest}</p>
                                </div>`
                    html += `<div>
                                    <h5>Sum</h5>
                                    <p id="table_total${tableNumber}"></p>
                                </div>`
                    var today_to = new Date();
                    var time_now = today_to.getHours() + ":" + today_to.getMinutes();
                    var diff = Math.abs(data[count].table_open - time_now);
                    html += `
                                <div>
                                    <h5>Order</h5>
                                    <p id="order_get${tableNumber}"></p>
                                </div>`
                    html += `</div>`
                    html += `<div class="info-footer d-flex flex-wrap">
                                <div class="w-100 d-flex">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("to order")): ?>
                                    <a href="<?php echo e(url('menu/New_Order/Table-${tableNumber}')); ?>" class='flex-grow-1'> To Order </a>
                                    <?php endif; ?>`;

                                    if(data[count].printcheck >= 1){
                                        html +=`    
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("print check more than once")): ?>
                                            <button class="btn btn-info flex-grow-1" type="button" id="printcheck_info">Print</button>
                                            <?php endif; ?>
                                        </div>`; 
                                    }else{
                                        html +=`    
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("print check")): ?>
                                            <button class="btn btn-info flex-grow-1" type="button" id="printcheck_info">Print</button>
                                            <?php endif; ?>
                                        </div>`; 
                                    }  
                    html +=`
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("reservation")): ?>
                                <div class='w-50  reserve-btn' data-toggle="modal" data-target="#Reservation_modal">
                                    <span>Reservation</span>
                                </div>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("transfer")): ?>
                                <div class='w-50  transfer-btn' data-toggle="modal" data-target="#transfer_modal">
                                    <span>Transfarer</span>
                                </div>
                                <?php endif; ?>
                            </div>`
                    html += `</div>`
                    html += '</div>';
                }
                if (holeName === 'Other') {
                    html += `<div style="width:150px;height:150px" class="table add-table" data-toggle="modal" data-target="#add_other_modal"></div>`
                }
                $(`#hole${hole_num}`).html(html);
            }

        });

    });
    // ################################# End search holes ###################

    // ############################### Start Add Other Table ###########################
    $('#save_other_table').on('click', function() {
        let tableName = $('#add_other_input').val();
        let hole = $('.nav-hole .nav-link.active').attr('hole');
        let html = '';
        let type = 'other';
        let branch_id = $('#id_branch').attr('value');
        $.ajax({
            url: "<?php echo e(route('add.new.table')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token,
                branch_id,
                tableName,
                type,
                hole
            },
            success: function(data) {
                if (data.status == 'true') {
                    if (tableName.trim() !== '') {
                        let table = tableName.replace(' ', '-')
                        html +=
                            `<div colorcheck="0" state="0" booked="0" follow="0" style="width:150px;height:150px;background-image: url(<?php echo e(asset('global/image/logo-small.png')); ?>)" hole="${hole}" number_of_tables = "${table}" class="table other-table">`;
                        html +=
                            `<div class='table-body' min-charge="0">`
                        html += `<span class="table-name">${table}</span>`
                        html +=
                            `<span class="table-Guest"> <i class="fas fa-chair"></i>  1 </span>`
                        html += `</div>`
                        html += `<div class='table-properties'>`
                        html += `<span class="table-status"></span>`
                        html += `<span class="merge-table" follow="" data-toggle="modal" data-target="#table-merge-modal"></span>`
                        html += `<span class="delete-table" branch="${$('#id_branch').attr('value')}" hole="${hole}"></span>`
                        html += `</div>`
                        html += `<div class='table-menu'>`
                        html += `<ul>`
                        html += `
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("accupy")): ?>
                                <li class='occupy'>
                                    <a href="#">
                                        <i class="fas fa-lock fa-fw"></i>
                                        <span> Occupy </span>
                                    </a>`
                        html += `</li><?php endif; ?>`

                        html += `
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("to order")): ?>
                                <li class='new-order'>
                                    <a href="<?php echo e(url('menu/New_Order/Table-${table}')); ?>">
                                        <i class="fas fa-plus-circle fa-fw"></i>
                                        <span> To Order </span>
                                    </a>`
                        html += `</li><?php endif; ?>`

                        html += `
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("reservation")): ?>
                                <li data-toggle="modal" data-target="#Reservation_modal">
                                    <a href="#">
                                        <i class="fas fa-sticky-note fa-fw"></i>
                                        <span> Reservation </span>
                                    </a>`
                        html += `</li> <?php endif; ?>`

                        html += `
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("min-charge")): ?>
                                <li data-toggle="modal" data-target="#min_charge_modal">
                                    <a href="#">
                                        <i class="fas fa-hand-holding-usd fa-fw"></i>
                                        <span> Min-Charge </span>
                                    </a>`
                        html += `</li><?php endif; ?>`

                        html += `</ul>`
                        html += `</div>`

                        html += `<div class='table-info' order=''>`
                        html += `<h4 class="info-title"> ${table} </h4>`
                        html += `<div class="info-body">`
                        html += `<div>
                                    <h5>Captain</h5>
                                    <p id="captaintable${table}"></p>
                                </div>`
                        html += `<div>
                                        <h5>Guests</h5>
                                        <p id="gust${table}">1</p>
                                    </div>`
                        html += `<div>
                                        <h5>Sum</h5>
                                        <p id="table_total${table}"></p>
                                    </div>`
                        html += `
                                    <div>
                                        <h5>Order</h5>
                                        <p id="order_get${table}"></p>
                                    </div>`
                        html += `</div>`
                        html += `<div class="info-footer d-flex flex-wrap">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("to order")): ?>
                                    <a href="<?php echo e(url('menu/New_Order/Table-${table}')); ?>" class='w-100'> To Order </a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("reservation")): ?>
                                    <div class='flex-grow-1 reserve-btn' data-toggle="modal" data-target="#Reservation_modal">
                                        <span>Reservation</span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("transfer")): ?>
                                    <div class='flex-grow-1 transfer-btn' data-toggle="modal" data-target="#transfer_modal">
                                        <span>Transfarer</span>
                                    </div>
                                    <?php endif; ?>
                                </div>`
                        html += `</div>`
                        html += '</div>';
                        $(html).insertBefore('.add-table')
                    }
                    $('#add_other_modal').modal('hide');
                    $('#add_other_input').val('');
                    $('.keyboard').addClass('keyboard--hidden')
                } else if (data.status == 'false') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    });
                    $('#add_other_modal').modal('hide');
                    $('#add_other_input').val('');
                    $('.keyboard').addClass('keyboard--hidden')
                }
            }
        });
    });
    // ############################### End Add Other Table ###########################

    $(document).on('click', '.occupy', function(e) {
        e.preventDefault();

        let tableParent = $(this).parents('.table');

        if (tableParent.hasClass('busy')) {

            tableParent.attr('booked', '0')

        } else {

            tableParent.attr('booked', '1')

        }

        let occupy = tableParent.attr('booked');

        let _token = $('input[name="_token"]').val();

        let Branch_ID = $('#id_branch').attr('value');

        let number_of_tables = tableParent.find('.table-name').text();

        $.ajax({
            url: "<?php echo e(route('occupy.table')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                occupy: occupy,
                number_of_tables: number_of_tables,
                Branch_ID: Branch_ID
            },
            success: function(data) {}
        });

    });

    // ######################Strat change min_charge#####################################
    $(document).on('click', '#save_mincharge_table', function(e) {

        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let table = $(this).parents('#min_charge_modal').attr('table');
        let tableName = $('.table').find(`.table-name:contains('${table}')`);
        let min_charge = $('#minchrage-input').val();

        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('change.charge')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                table: table,
                min_charge: min_charge,
            },
            success: function(data) {
                $('#min_charge_modal').modal('hide')
                $('#minchrage-input').val(min_charge);
                tableName.parent().attr('min-charge', min_charge)
            },
        });
    });

    // --# # # # # # # # # # # # # # # # # # # # # #End change min_charge # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--


    $(document).on('click', '.delete-table', function(e) {
        let tableDiv = $(this).parents('.table')
        let _token = $('input[name="_token"]').val();
        let hole = $(this).attr('hole');
        let table = tableDiv.attr('number_of_tables');
        let branch = $(this).attr('branch');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't Delete This Table",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo e(route('del.table')); ?>",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data: {
                        _token: _token,
                        hole: hole,
                        table: table,
                        branch: branch
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            tableDiv.remove();
                        }
                        if (data.status == 'false') {
                            Swal.fire({
                                title: 'Table is Open !',
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1000
                            });
                        }
                    }
                });
            }
        });

    });



    // --# # # # # # # # # # # # # # # # # # # # # #Start starting Table Time # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    // When Click On Table Body Show All Menus
    $(document).on('click', '.table-body', function() {

        let btn = $(this);
        let tableMenuPop = $(this).siblings(".table-menu");
        let tableInfoPop = $(this).siblings(".table-info");


        let tableTime = $(this).attr('time')

        function getTime() {
            let newDate = new Date();
            let newHour = (newDate.getHours() - 2) * 60;
            let newMinuts = newDate.getMinutes();
            let subMinuts = (newHour + newMinuts) - tableTime;
            let getHours = Math.floor(subMinuts / 60);
            let getMinuts = subMinuts % 60;
            (getHours < 10) ? getHours = `0${getHours}`: getHours = getHours;
            (getMinuts < 10) ? getMinuts = `0${getMinuts}`: getMinuts = getMinuts;
            return `${getHours}:${getMinuts}`;
        }

        if ($(this).parents(".table").attr("state") >= 1) {

            tableInfoPop.slideDown();
            new Popper(btn, tableInfoPop, {
                placement: "auto",
                modifiers: [{
                        name: "offset", //offsets popper from the reference/button
                        options: {
                            offset: [0, 8]
                        }
                    },
                    {
                        name: "flip", //flips popper with allowed placements
                        options: {
                            allowedAutoPlacements: ["right", "left", "top", "bottom"],
                            rootBoundary: "viewport"
                        }
                    }
                ]
            });

            let _token = $('input[name="_token"]').val();
            let Branch_ID = $('#id_branch').attr('value');
            let tableNumber = $(this).children('.table-name').text();
            let tableTimeParent = $(this).parents('.table').find('.time');
            let alltotal = 0;

            $.ajax({
                type: 'POST',
                url: "<?php echo e(route('get.total.table')); ?>",
                method: 'post',
                enctype: "multipart/form-data",
                data: {
                    _token: _token,
                    tableNumber: tableNumber,
                    Branch_ID: Branch_ID,
                },
                success: function(data) {
                    alltotal = parseFloat(data.total).toFixed(2);
                    $(`#table_total${tableNumber}`).html(`${alltotal} &pound;`).attr('value', alltotal);
                    $(`#captaintable${tableNumber}`).html(`${data.captain}`);
                    $(`#order_get${tableNumber}`).html(`${data.order}`);
                    $(`#gust${tableNumber}`).html(`${data.gust}`);
                    tableTimeParent.text(getTime())
                    tableInfoPop.attr('order', data.order)
                },
            });


        } else {

            tableMenuPop.slideDown();

            new Popper(btn, tableMenuPop, {
                placement: "auto",
                modifiers: [{
                        name: "offset", //offsets popper from the reference/button
                        options: {
                            offset: [0, 8]
                        }
                    },
                    {
                        name: "flip", //flips popper with allowed placements
                        options: {
                            allowedAutoPlacements: ["right", "left", "top", "bottom"],
                            rootBoundary: "viewport"
                        }
                    }
                ]
            });


        }

    });
    // ######################End starting Table Time#######################################

    // ######################Start Save Merge#######################################
    $(document).on('click', '#save-merge', function() {

        let _token = $('input[name="_token"]').val();
        let Branch_ID = $('#id_branch').attr('value');

        let master_table = $(this).parents('.table-merge-modal').find('.form-group[master="true"] input').attr('id'); // Get Master table Number From Master CheckBox
        let tableChecked = $(this).parents('.table-merge-modal').find('input[type="checkbox"]:checked').not('#hole').not(':disabled'); // Get All Checkboxes Is Cheched
        let allTableCheckBox = $(this).parents('.table-merge-modal').find('input[type="checkbox"]').not('#hole'); // Get All CheckBoxes
        let matserTable = $('.table').find(`.table-name:contains(${master_table})`).first(); // Get Master Table

        let slave_tables = []

        tableChecked.each(function() {
            let obj = {}
            obj.id = $(this).attr('id');
            slave_tables.push(obj);
        });

        // Function To Merged Tables Is Ckecked
        function mergedTables() {

            tableChecked.each(function() {

                let myTable = $(`.table .table-name:contains(${$(this).attr('id')})`).first();
                console.log(myTable);

                if (myTable.parents(".table").hasClass('master') == false) {

                    myTable.parents(".table").addClass("merged");
                    myTable.parents(".table").find('.merge-table').addClass('followed').attr('follow', master_table);
                    myTable.parents(".table").attr('follow', master_table);
                }

            });
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('Save.merge')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                slave_tables: slave_tables,
                master_table: master_table,
                Branch_ID: Branch_ID
            },
            success: function(data) {
                if (data.status == 'false') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    });
                }

                // Convert Array Of Object (Merged Table) To Array Of String
                let tables = slave_tables.map(function(table) {
                    return table['id'];
                });

                // Convert Array To String
                let followdTable = tables.join('-');

                matserTable.parents('.table').addClass('master'); // Add Class Master On Table Master
                matserTable.parents('.table').attr("merged", followdTable); // Set Attribute Merged To Master Table

                let AllTablesMerged = $(`.table[follow=${master_table}]`); // get All Tables Merged With This Master Table

                // Remove All Classes And All Atributes From All Tables Merged
                AllTablesMerged.each(function() {

                    $(this).removeClass("merged");
                    $(this).find('.merge-table').removeClass('followed').attr('follow', '0')
                    $(this).attr('follow', '0');

                });

                mergedTables(); // Add All Classes And Atributes On Tables Checked

                // If Master Table is Not Has Follow Tables Remove Class Matser For It
                if (matserTable.parents('.table').attr("merged") == '') {
                    matserTable.parents('.table').removeClass('master');
                }

            },
        });
        $('.table-merge-modal').modal('hide');

    });
    $(document).on('click', '#Reservation-tab', function() {
        let _token = $('input[name="_token"]').val();
        let table = $(this).parents('.modal').attr('table');
        let row = '';
        $.ajax({
            url: "<?php echo e(route('get.res')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                table: table,
            },
            success: function(data) {
                for (let count = 0; count < data.length; count++) {
                    row += `<tr resID="${data[count].id}" resli="${data[count].phone}${data[count].time_from}">
                        <td>${data[count].customer}</td>
                        <td>${data[count].phone}</td>
                        <td>${data[count].cash}</td>
                        <td>${data[count].date}</td>
                        <td>${data[count].time_from}</td>
                        <td>${data[count].time_to || 'Open'}</td>
                        <td>
                            <button class="btn btn-danger trash-res">
                                <i class="fas fa-trash-alt text-white"></i>
                            </button>
                        </td>
                    </tr>`;
                }
                $('#Reserved tbody').html(row)
            },
        });
    });


    // --# # # # # # # # # # # # # # # # # # # # # #End Save Merge # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    // --# # # # # # # # # # # # # # # # # # # # # #Start Reservation Tables # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    $("#add_other_modal").on("shown.bs.modal", function(e) {
        $(this).find('input').focus();
    })
    $("#Reservation_modal").on("shown.bs.modal", function(e) {
        let ReserveButton = $(e.relatedTarget);
        let myTable = ReserveButton.parents(".table");
        $(this).attr('table', myTable.attr('number_of_tables'));
        $(this).find('input[name="table_id"]').val(myTable.attr('number_of_tables'));
        $('#Reserved tbody').empty();
        $('#Reserving').addClass('show active').siblings().removeClass('show active')
        $('#Reserving-tab').addClass('active').siblings().removeClass('active')
        if (ReserveButton.hasClass('reserved-item')) {
            $('a#Reservation-tab').click()
        }
    });

    $(document).on('click', '.trash-res', function(e) {
        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let resId = $(this).parents('tr').attr('resID');
        let resLiId = $(this).parents('tr').attr('resli');
        let thisBtn = $(this);
        let resList = $(this).parents('tr').siblings().length;
        let ModalNumber = $(this).parents('.modal').attr('table');
        let myTable = $(`.table-name:contains('${ModalNumber}')`).parents('.table');

        Swal.fire({
            title: 'Are you sure?',
            text: "You Want Delete This Reservation",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo e(route('del.res')); ?>",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data: {
                        _token: _token,
                        resid: resId,
                    },
                    success: function(data) {
                        console.log(resList)
                        thisBtn.parents('tr').remove();
                        $(`.resLength li[resid="${resLiId}"]`).remove();
                        myTable.find('.reservation-time').attr('test', resList)
                        if (resList == 0) {
                            myTable.find('.reservation-time').remove();
                        }
                    }
                });
            }
        });
    });

    // --# # # # # # # # # # # # # # # # # # # # # #End Reservation Tables # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    // --# # # # # # # # # # # # # # # # # # # # # #Start Transfer Tables # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    $("#transfer_modal").on("show.bs.modal", function(e) {
        let _token = $('input[name="_token"]').val();
        let modalButton = $(e.relatedTarget);
        let that = $(this);
        let order = modalButton.parents('.table-info').attr('order');
        let table = modalButton.parents('.table').attr('number_of_tables');

        $.ajax({
            url: "<?php echo e(route('get.users')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
            },
            success: function(data) {
                let html = ``;
                for (var count = 0; count < data.data.length; count++) {
                    if (data.user !== data.data[count].id) {
                        html += `
                        <div class="form-group">
                            <input type="radio" name="transfer" id="user-${data.data[count].id}" user='${data.data[count].id}'>
                            <label for="user-${data.data[count].id}"> ${data.data[count].name} </label>
                        </div>`;
                    }

                }
                that.find('.modal-body .captens').html(html);
                that.attr('order', order)
                that.attr('table', table)
            }
        });
    });

    $('#save-transfer').on('click', function() {
        let _token = $('input[name="_token"]').val();
        let radiButton = $(this).parents('.modal').find('input[name="transfer"]:checked');
        let userId = radiButton.attr('user');
        let userName = radiButton.next('label').text();
        let modal = $(this).parents('.modal');
        let note = modal.find('textarea')
        let today = new Date().toISOString().slice(0, 10);

        $.ajax({
            url: "<?php echo e(route('transfer.users')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                userId: userId,
                userName: userName,
                order: modal.attr('order'),
                table: modal.attr('table'),
                note: note.val()
            },
            success: function(data) {
                if (data.status == 'true') {
                    modal.modal('hide');
                    note.val('')
                }
                if (data.msg) {
                    $(`.table-name:contains('${modal.attr('table')}')`).parents('.table').addClass('other-user')
                    Swal.fire({
                        position: 'center-center',
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    });
                    $('#transfer_modal').modal('hide')
                }
            }
        });
    });

    // --# # # # # # # # # # # # # # # # # # # # # #End Transfer Tables # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    // --# # # # # # # # # # # # # # # # # # # # # #Start Transfer Notification # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    $('.notification').on('show.bs.dropdown', function() {
        let _token = $('input[name="_token"]').val();
        let user = $('#id_user').attr('user');
        $.ajax({
            url: "<?php echo e(route('get.wait.transfer')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
            },
            success: function(data) {
                let html = '';
                for (var count = 0; count < data.length; count++) {
                    if (user == data[count].c_user) {
                        html +=
                            `<li noti_id='${data[count].id}' status='${data[count].status}'>
                            <p class='m-0'>Your Transfer <span> table ${data[count].table}</span> To <span>${data[count].new_user.name}</span></p>
                            <span class='status'>${data[count].status}</span>

                        </li>`
                    } else {

                        html +=
                            `<li noti_id='${data[count].id}' order="${data[count].order}">
                            <p class='m-0'>You Have Transfer <span> table ${data[count].table}</span> From <span>${data[count].current_user.name}</span></p>
                            <div class="btns">`
                        if (data[count].note != null) {
                            html += `<button class="btn btn-secondary Mse-popover" onclick='event.stopPropagation();' content='${data[count].note}'><i class="fas fa-comment-dots"></i></button>`
                        }
                        html += `<button class='btn btn-success req-btn ml-2' id="accepte_noti"><i class="fas fa-check"></i> </button>
                                <button class='btn btn-danger ml-1 req-btn' id="reject_noti"><i class="fas fa-times"></i> </button>
                            </div>
                        </li>`
                    }

                }
                $('#show_noti').html(html);
            }
        });
    });

    $(document).on('mouseenter', '.Mse-popover', function(e) {
        let text = $(this).attr('content')
        $(this).popover({
            placement: 'bottom',
            content: text,
            trigger: 'focus'
        });

    });

    $(document).on('click', '.req-btn', function(e) {
        e.stopPropagation();
        let _token = $('input[name="_token"]').val();
        let status = $(this).attr('id');
        let parent = $(this).parents('li')
        let noti_id = parent.attr('noti_id');
        let order = parent.attr('order');

        $.ajax({
            url: "<?php echo e(route('opration.transfer')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                status: status,
                noti_id: noti_id,
                order: order
            },
            success: function(data) {
                parent.remove();
            }
        });
    });

    // --# # # # # # # # # # # # # # # # # # # # # #End Transfer Notification # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--


    // --# # # # # # # # # # # # # # # # # # # # # # Start Pay Modal # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--
    $(document).on('click', '#printcheck_info', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let _token = $('input[name="_token"]').val();
        let tableInfo = $(this).parents('.table-info');
        let table = tableInfo.find('.info-title').text();
        let order = tableInfo.attr('order');
        let myModal = $("#pay-model");
        let totalPrice = tableInfo.find(`#table_total${table}`).attr('value');
        let bankRatio = myModal.find('#bank-ratio');
        let tableData = {
            order,
            table
        }

        $.ajax({
            type: 'POST',
            url: "<?php echo e(route('Pay.check')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                table: table,
                totalPrice: totalPrice,
                order: order,
            },
            success: function(data) {
                let html = '';
                let allquan = 0;
                let alltotal = 0;
                let allsummary = 0;
                let allnew = 0;
                let bank = 0;
                if (data.status == true) {
                    if (data.type == 'credit') {
                        $('#credit-tab').click();
                        $('#visa-price').val(data.visa)
                    } else if (data.type == 'cash') {
                        $('#cash-tab').click();
                        $('#cash-price').val(data.visa)
                    } else if (data.type == 'hospitality') {
                        $('#hospitality-tab').click();
                    }
                    html += `<li>`
                    for (var count = 0; count < data.data.length; count++) {
                        allquan = +allquan + +data.data[count].quantity
                        alltotal += +data.data[count].total + +data.data[count].total_extra + +data.data[count].price_details - +data.data[count].total_discount

                        myModal.modal('show')

                        html += `<div>`
                        html += `<span>${data.data[count].quantity}<span style='color:var(--price-color)'> | </span>${data.data[count].name}</span>`
                        html += `<span>${+data.data[count].total + +data.data[count].total_extra + +data.data[count].price_details - +data.data[count].total_discount}</span>`
                        html += `</div>`

                    }
                    bank = data.bank_ratio
                    allsummary = parseFloat(+alltotal + +data.service[0].service + +data.service[0].tax - parseFloat(data.discount))
                    html += `</li>`
                    myModal.find('.summary ul').prepend(html);
                    myModal.find('.summary .last-item .items-quant').html(allquan);
                    myModal.find('.summary .last-item .summary-total').html(alltotal.toFixed(2));
                    myModal.find('.summary .last-item .summary-tax').html(parseFloat(data.service[0].tax).toFixed(2));
                    myModal.find('.summary .last-item .summary-service').html(parseFloat(data.service[0].service).toFixed(2));
                    myModal.find('.summary .last-item .summary-mincharge').html(parseFloat(data.min_charge).toFixed(2));
                    myModal.find('.summary .last-item .summary-discount').html(parseFloat(data.discount).toFixed(2));
                    myModal.find('.summary .summary-bank').html(data.value_bank);
                    allnew = allsummary
                    if (data.min_charge > allsummary) {
                        allnew = data.min_charge
                    }
                    bankRatio.val(parseFloat(data.bank_ratio).toFixed(2));
                    bankRatio.attr('data-allnew', parseFloat(allnew).toFixed(2));
                    myModal.find('.summary .last-item .all-total').html(parseFloat(allnew).toFixed(2));
                    myModal.find('.summary-price').each(function() {
                        $(this).html(parseFloat(allnew).toFixed(2));
                    })
                    myModal.find('.input-ser').each(function() {
                        if ($('#ser-check').is(':checked')) {
                            $(this).val(parseFloat(data.service[0].service_ratio));
                            $(this).prop('disabled', false)
                        } else {
                            $(this).val(0);
                            $(this).prop('disabled', true)
                        }

                    });
                    myModal.attr('totVal', $('#total-price').attr('totval'));
                    $('#ser-check').attr('dis') == '0' ? myModal.attr('status', 'without') : myModal.attr('status', 'with');
                    myModal.attr('data', JSON.stringify(tableData))
                } else if (data.status == "min") {
                    Swal.fire({
                        title: 'The operation is wrong',
                        html: `The MinCharge is ${data.min_charge} You Must Pay <span class='text-danger'>${data.rest}</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Pay',
                        cancelButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#total-price').text(data.min_charge);
                            $('#summary_check').click();
                        }
                    });
                } else if (data.status == "empty_order") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Order Not Found',
                    });
                }
            },
        });
    });
    // --# # # # # # # # # # # # # # # # # # # # # # End Pay Modal # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #--

    // ############################### Start Print Check #############################
    $('#printcheck').on('click', function() {
        let summaryTotal = $('.summary-total').text();
        let summaryService = $('.summary-service').text();
        let summaryTax = $('.summary-tax').text();
        let allTotal = $(this).parents('.modal').find('.tab-pane.active .summary-price').text();
        let summaryDelivery = $('.summary-delivery').text();
        let summaryDiscount = $('.summary-discount').text();
        let method_bay = $(this).parents('.modal').find('.tab-pane.active').attr('id');
        let Price = $(this).parents('.modal').find('.tab-pane.active .price-value').val();
        let Rest = $(this).parents('.modal').find('.tab-pane.active .price-rest').text();
        let order = JSON.parse($(this).parents('.modal').attr('data')).order;
        let serButton = $('#ser-check');
        let myModal = $(this).parents('.modal')
        let serVal = myModal.find('.tab-pane.active .input-ser').val();
        let device = localStorage.getItem('device_number');
        let table = JSON.parse($(this).parents('.modal').attr('data')).table;
        let operation = $('#operation').attr('value');
        let bank_value = $('.summary-bank').text();
        let type_method = $('#operation').attr('value');

        console.log(JSON.parse($(this).parents('.modal').attr('data')))

        $.ajax({
            url: "<?php echo e(route('print.check')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data: {
                _token: _token,
                order: order,
                bank_value: bank_value,
                service: summaryService,
                tax: summaryTax,
                subtotal: summaryTotal,
                discount: summaryDiscount,
                total: allTotal,
                method_bay: method_bay,
                price: Price,
                rest: Rest,
                device: device,
                table: table,
                serviceratio: serVal,
                Delivery: summaryDelivery,
                operation: 'Table'
            },
            success: function(data) {
                myModal.modal('hide')
                $(".nav-hole a.active").click();
            }
        });
    });
    // ############################### End Print Check #############################
</script>
<?php echo $__env->make('includes.menu.reservation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\BackEnd\htdocs\webpoint\resources\views/menu/tables.blade.php ENDPATH**/ ?>
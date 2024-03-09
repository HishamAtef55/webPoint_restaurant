    <!-- Start Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg">

        <div class='container-fluid'>

            <div class="date-time mr-lg-auto">
                <span class='time mr-3'></span>
                <span class='date'></span>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#Options" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                <i class="fas fa-bars text-white"></i>

            </button>

            <div class='claculator-btn'>

                <button class='btn' > <i class="fas fa-calculator"></i> </button>

            </div>

            <div class='check-btn'>

                <button class='btn ' > <span>Check</span> </button>

            </div>

            <div class="collapse navbar-collapse text-white" id="Options">

                    <ul class="navbar-nav mx-lg-auto">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('options')): ?>
                        <li class="nav-item" data-menu='options-menu'>
                            <a href='#' class='d-flex flex-column align-items-center'>
                                <i class="fas fa-ellipsis-v"></i>
                                <span>Options</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery')): ?>
                        <li class="nav-item nav-delivery" data-menu="delivery-menu">
                            <a href="<?php echo e(URL::to('menu/New_Order/Delivery')); ?>" class='d-flex flex-column align-items-center'>
                                <?php if($del_noti > 0): ?>
                                    <span class='notification-num'><?php echo e($del_noti); ?></span>
                                <?php else: ?>
                                    <span class='notification-num del'></span>
                                <?php endif; ?>
                                <i class="fas fa-truck-loading"></i>
                                <span>Delivery</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('to-go')): ?>
                        <li class="nav-item nav-togo" data-menu="takeaway-menu">
                            <a href="<?php echo e(URL::to('menu/New_Order/TO_GO')); ?>" class='d-flex flex-column align-items-center'>
                                <?php if($to_noti_hold > 0): ?>
                                <span class='notification-num'><?php echo e($to_noti_hold); ?></span>
                                <?php else: ?>
                                <span class='notification-num del'></span>
                                <?php endif; ?>
                                <i class="fas fa-shopping-bag"></i>
                                <span>TO GO</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dien-in')): ?>
                        <li class="nav-item dienIn">
                            <a href="<?php echo e(Route('view.table')); ?>" class='d-flex flex-column align-items-center'>
                                <i class="fas fa-utensils"></i>
                                <span>Dien IN</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('customer')): ?>
                        <li  data-toggle="modal" data-target="#Customer-model" class="nav-item">
                            <a  class='d-flex flex-column align-items-center'>
                                <i class="far fa-user"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('repoerts')): ?>
                        <li  data-toggle="modal" data-target="#Reports-model" class="nav-item">
                            <a  class='d-flex flex-column align-items-center'>
                                <i class="fas fa-file-alt"></i>
                                <span>Reports</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(Route('View.General')); ?>" class='d-flex flex-column align-items-center'>
                                <i class="far fa-user"></i>
                                <span>Admin</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                <div class='d-flex align-item-center '>
                    <div class="notification dropleft mr-1 d-flex align-items-center justify-content-center">
                        <div class="dropdown-toggle mr-2" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,20">
                            <?php if(isset($transfers)): ?>
                                <span class='notification-num'><?php echo e($transfers->count()); ?></span>
                            <?php endif; ?>
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="notification" id='alaa'>
                            <ul class='p-0 m-0' id="show_noti">

                            </ul>
                        </div>
                    </div>
                    <div class="user dropdown mr-1">
                        <div class="dropdown-toggle d-flex align-items-center flex-row-reverse" id="user_info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-offset="10,20">
                            <div class='user-img rounded-circle'>
                                <img src="<?php echo e(asset('control/images/users') . '/'); ?><?php echo e(Auth::user()->image); ?>" alt="">
                            </div>
                            <div class='user-name mr-2'>
                                <span><?php echo e(Auth::user()->name); ?></span>
                                <input id="id_user" user="<?php echo e(Auth::user()->id); ?>" type="text" class="d-none">
                            </div>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="user_info">
                            <a href="<?php echo e(Route('logout')); ?>">Logout</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </nav>

    <!-- Modal -->
    <div class="modal fade reports-model" id="Reports-model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Reports</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="<?php echo e(route('view_sales_current')); ?>" class='link-report'>Sales Current Reports</a>
                        <a href="<?php echo e(route('view_daily_report')); ?>" class='link-report'>Daily Reports</a>
                        <a href="<?php echo e(route('view_water_sales_report')); ?>" class='link-report'>Waiter Sales Reports</a>
                        <a href="<?php echo e(route('view_shift_sales_report')); ?>" class='link-report'>Shift Sales Reports</a>
                        <a href="<?php echo e(route('view_transfer_report')); ?>" class='link-report'>Transfers Reports</a>
                        <a href="<?php echo e(route('view_discount_report')); ?>" class='link-report'>Discount Reports</a>
                        <a href="<?php echo e(route('view_void_report')); ?>" class='link-report'>Void Reports</a>
                        <a href="<?php echo e(route('view_item_report')); ?>" class='link-report'>Item Not Sales Reports</a>
                        <a href="<?php echo e(route('view_cost_report')); ?>" class='link-report'>Cost Reports</a>
                        <a href="<?php echo e(route('view_cost_sold_report')); ?>" class='link-report'>Cost Sold Items</a>
                        <a href="<?php echo e(route('view_log_report')); ?>" class='link-report'>Log Report</a>
                        <a href="<?php echo e(route('view_expenses_report')); ?>" class='link-report'>Expenses Report</a>
                        <a href="<?php echo e(Route('View.getDays')); ?>" class='link-report'>Open Day</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

        <!--Model Of Device Numer-->
        <div class="modal fade device-model" id="device-model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Device Number</h5>
                        
                        
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Device No</h5>
                        <input class="form-control" id='number_dev_inut' type="number">
                        <h5>Invoice Printer</h5>
                        <select class="form-control mt-2" name="device_printer" id="device_printer">
                            <?php $__currentLoopData = \App\Models\Printers::where('branch_id',auth()->user()->branch_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($printer->printer); ?>"><?php echo e($printer->printer); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <h5 class="mt-2">Slip Printer</h5>
                        <div class="row">
                            <?php $__currentLoopData = \App\Models\Printers::where('branch_id',auth()->user()->branch_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-4 form-group">
                                <input type="checkbox" id="device-<?php echo e($printer->id); ?>"  class="devicePrinters" dataPrinterId="<?php echo e($printer->id); ?>" dataPrinterName="<?php echo e($printer->printer); ?>">
                                <label for="device-<?php echo e($printer->id); ?>" class="input-label"><?php echo e($printer->printer); ?></label>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save_dev_outadmin" class="btn btn-success" data-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- End Navbar -->
<?php /**PATH F:\BackEnd\htdocs\webpoint\resources\views/includes/menu/header.blade.php ENDPATH**/ ?>
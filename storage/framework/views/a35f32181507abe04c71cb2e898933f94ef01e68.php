<?php
    $title = 'TO-GO';
?>


<?php $__env->startSection('content'); ?>
    <!-- Start Delivery -->
    <section class='delivery'>
        <div id="check_page" value="to_pilot"></div>
        <div id="operation" value="TO_GO"></div>
        <div class='container'>
            <?php echo $__env->make('includes.menu.sub_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="modal-body d-flex flex-column-reverse flex-wrap togo-order">
                <div class='row w-100'>
                    <div class='col mb-5 flex-wrap' id="box_content">
                        <?php
                            $counter = 0;
                            $money = 0;
                        ?>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $counter ++;
                            $money = $money +=$order->total;
                        ?>
                            <?php if($order->total != null): ?>
                                <div class='box' box_id="<?php echo e($order->order_id); ?>" box_serial="<?php echo e($order->serial_shift); ?>">
                            <?php else: ?>
                                <div class='box' box_id="<?php echo e($order->order_id); ?>" box_serial="<?php echo e($order->serial_shift); ?>" style="background:var(--gray-dark)">
                            <?php endif; ?>
                            <ul class="list-unstyled box-list">

                                <li>
                                    <i class="fas fa-hashtag"></i>
                                    <span class="order_id"><?php echo e($order->order_id); ?></span>
                                </li>

                                <li>
                                    <i class="fas fa-fire"></i>
                                    <span class="serial_id"><?php echo e($order->serial_shift); ?></span>
                                </li>

                                <li>
                                    <i class="far fa-clock"></i>
                                    <span><?php echo e($order->t_order); ?></span>
                                </li>

                                <li>
                                    <i class="fas fa-user-tie"></i>
                                    <span><?php echo e($order->user); ?></span>
                                </li>

                                <li class="orderPrice">
                                     <i class="fas fa-money-bill-wave "></i>
                                     <span><?php echo e($order->total); ?></span>
                                </li>

                            </ul>

                            <div class='box-menu'>

                                <ul>
                                    <?php echo csrf_field(); ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("edite to-go")): ?>
                                    <li>
                                        <a href="<?php echo e(url('menu/Edit_Order/' . $order->order_id)); ?>">
                                            <i class="fas fa-edit fa-fw"></i>
                                            <span> Edit </span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("remove to-go")): ?>
                                    <li id="Remove_Delivery" class='remove'>
                                        <?php echo csrf_field(); ?>
                                        <a href="#">
                                            <i  class="fas fa-trash-alt fa-fw"></i>
                                            <span>Remove</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>

                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="row w-100 justify-content-center">
                    <div class="delivery-info d-flex justify-content-around">
                        <div class="col text-center">
                            <span>اجمالى الطلبات</span>
                            <span class="ordersNum info-num"><?php echo e($counter); ?></span>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('total to-go')): ?>
                        <div class="col text-center">
                            <span>اجمالى المبلغ</span>
                            <span class="ordersPrice info-num"><?php echo e($money); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center">
                            <input type="text" class='col form-control use-keyboard-input' placeholder="Search For Order...">
                            <button class="btn btn-warning" id='search_order'><i  class="fas fa-search fa-fw"></i></button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- End Delivery -->

    <?php echo $__env->make('includes.menu.delivery_order', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/menu/togo_order.blade.php ENDPATH**/ ?>
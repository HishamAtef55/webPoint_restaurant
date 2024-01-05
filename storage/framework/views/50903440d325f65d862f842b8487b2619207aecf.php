
<?php
    $title = 'MoveTO';
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('includes.menu.sub_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <input type="hidden" id="device_id" value="">
    <section class='moveTo'>
        <div class='container'>
            <div class="alert alert-success"  style="display: none;" id="alert_show" role="alert">
                Successful Move <script>setTimeout(function(){$('#alert_show').hide();}, 2500);</script>
            </div>
            <div class='row mb-4 table-num'>
                <div class="col-md-5 d-flex flex-row mt-3">
                    <div class="form-element w-100">
                        <h2 class='text-center text-white'>From</h2>
                        <?php echo csrf_field(); ?>
                        <select  class="main_table custom-select"  name="search_main_table" id="search_main_table">
                            <option selected disabled>Choose Table...</option>
                            <?php $__currentLoopData = $master_tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($table->number_table); ?>"><?php echo e($table->number_table); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        
                    </div>
                </div>
                <div class="col-md-5 offset-md-2 d-flex flex-row mt-3">
                    <div class="form-element w-100">
                        <h2 class='text-center text-white'>To</h2>
                        <select  class="new_table custom-select"  name="new_table" id="search_new_table">
                            <option selected disabled>Choose Table...</option>
                            <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($table->number_table); ?>"><?php echo e($table->number_table); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        
                    </div>
                </div>
            </div>

            <div class='row lists'>
                <div class='col-6 col-md-5'>
                    <div class="left">
                            <ul class="table-list list-unstyled">
                                <div id="new_order_view">

                                </div>
                            </ul>
                    </div>
                </div>
                <div class='col-6 col-md-5'>
                    <div class="right">
                        <ul class="table-list list-unstyled">
                            <div id="main_order_view">

                            </div>
                        </ul>
                    </div>
                </div>
                <div class='col col-md-2'>
                    <div class="arrow">
                        <button id="transfer" class='btn btn-block btn-primary py-5 trans-all'>Transfer</button>
                        <a  href="<?php echo e(Route('view.table')); ?>" class='btn btn-block btn-danger'>Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <?php echo $__env->make('includes.menu.move_to', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/menu/moveto.blade.php ENDPATH**/ ?>
<?php
    $title = 'Device';
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Add Device</h2>
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2">
                <div class="select-box">
                    <select class="select_Branch" name="branch" id="select">
                        <option value=""></option>
                        <?php $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='branch-input'>Choose Branch...</label>
                            <input autocomplete="off" type='text' class="search-input" id='branch-input' />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>
                    </div>
                </div>

                <form class="form-inline">
                    <?php echo csrf_field(); ?>
                    <div class="form-element">
                        <label for="device_id" class="input-label">Number Device</label>
                        <input type="text" class="mycustom-input" id="device_id" name="device">
                        <span class='under_line'></span>
                    </div>
                </form>

                <div class="select-box">
                    <select class="select_printers" id="printer">
                        <option></option>
                        <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($printer->printer); ?>"><?php echo e($printer->printer); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='printers-input'>Print Invoice...</label>
                            <input autocomplete="off" name="printer" type='text' class="search-input"
                                id='printers-input' />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-4">
                        <label for="device-<?php echo e($printer->id); ?>" class="input-label"><?php echo e($printer->printer); ?></label>
                        <input type="checkbox" id="device-<?php echo e($printer->id); ?>"  class="devicePrinters" dataPrinterId="<?php echo e($printer->id); ?>" dataPrinterName="<?php echo e($printer->printer); ?>">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button type="submit" id="add_device" class="btn btn-block btn-success mt-3"> Add </button>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.control.device', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webpoint\resources\views/control/add_device.blade.php ENDPATH**/ ?>
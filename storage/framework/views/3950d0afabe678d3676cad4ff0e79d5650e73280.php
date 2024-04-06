<?php $title='صرف مخازن';?>

<?php $__env->startSection('content'); ?>
<section class="purchases">
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
        <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-2 stores">
                    <label for="stores"  class="select-label">المخزن</label>
                    <select class="form-select" id="stores">
                        <option selected disabled>اختر المخزن</option>
                        <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($store->id); ?>"><?php echo e($store->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 branch-sec">
                    <label for="branch"  class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 branch-sec">
                    <label for="sections"  class="select-label">القسم</label>
                    <select class="form-select" id="sections">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date"  name="date_from" id="date_from" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date" >التاريخ من</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date"  name="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date" >التاريخ إلى</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary" id="showExchangeReport">عرض الصرف</button>
                </div>
            </div>
            <hr />
            <div id="report_content"></div>
        </div>



        <!-- <tfoot class="table-dark">
                            <th colspan="5"> الاجمالي </th>
                            <th class="sumPrice">  </th>
                            <th class="sumQty">  </th>
                            <th class="sumTotal">  </th>
                    </tfoot> -->

    </div>
</section>
<?php echo $__env->make('includes.stock.reports_ajax.exchange', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/reports/exchange.blade.php ENDPATH**/ ?>
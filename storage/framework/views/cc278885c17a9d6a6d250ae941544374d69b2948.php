<?php $title=' تقرير الهالك ';?>

<?php $__env->startSection('content'); ?>
<section class="purchases">
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            <?php echo csrf_field(); ?>
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input purchases-method" type="radio" value="section" id="sections_method" name="halk_method">
                        <label class="form-check-label" for="sections_method">
                            اقسام
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input purchases-method" type="radio" value="store" id="stores_method" name="halk_method" checked>
                        <label class="form-check-label" for="stores_method">
                            مخازن
                        </label>
                    </div>
                </div>
                <div class="col-md-3 stores">
                    <label for="store" class="select-label">مخزن</label>
                    <select class="form-select" id="store">
                        <option selected disabled>اختر المخزن</option>
                        <option value="all">All</option>

                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="branch" class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        <option value="1">Branch1</option>
                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="section" class="select-label">القسم</label>
                    <select class="form-select" id="section">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date" name="date_from" id="date_from" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date_from">التاريخ من</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date" name="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date_to">التاريخ إلى</label>
                    </div>
                </div>
                <div class="col">
                    <button class="btn btn-primary showTransferReport" data-request="details">عرض الهالك</button>
                    <button class="btn btn-warning showTransferReport" data-request="total">عرض اجمالي الهالك</button>
                </div>
            </div>
            <hr />
            <div id="report_content"></div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.reports_ajax.halk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/reports/halk.blade.php ENDPATH**/ ?>
<?php $title='الجرد';?>

<?php $__env->startSection('content'); ?>
<section class="inventory">
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            <?php echo csrf_field(); ?>
            <div class="row align-items-end">
                <div class="col-md-3">
                    <div class="custom-form">
                        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                        <label for="date">التاريخ</label>
                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input inventory-method" type="radio" value="section"
                                id="sections_method" name="inventory_method">
                        <label class="form-check-label" for="sections_method">
                            اقسام
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input inventory-method" type="radio" value="store"
                                id="stores_method" name="inventory_method" checked>
                        <label class="form-check-label" for="stores_method">
                            مخازن
                        </label>
                    </div>
                </div>

                <div class="col-md-3 stores">
                    <label for="stores"  class="select-label">المخزن</label>
                    <select class="form-select" id="stores">
                        <option selected disabled>اختر المخزن</option>
                        <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($store->id); ?>"><?php echo e($store->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="branch"  class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branche): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branche->id); ?>"><?php echo e($branche->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3 branch-sec d-none">
                    <label for="sections"  class="select-label">القسم</label>
                    <select class="form-select" id="sections">
                        <option selected disabled>اختر القسم</option>
                    </select>
                </div>
                <div class="col">
                    <button style="width: 150px"class='btn btn-danger fs-6' id="reset_values" disabled> تصفير الرصيد</button>
                </div>
            </div>
            <hr />
            <div class="table-responsive" id="printableArea">
                <table class="table table-light table-striped text-center table-inventory">
                    <thead>
                    <tr>
                        <th>كود الصنف</th>
                        <th>اسم الصنف</th>
                        <th>سعر الوحدة</th>
                        <th>الرصيد</th>
                        <th>الرصيد الفعلي</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="not-found">
                            <td colspan="5">لا يوجد بيانات</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr />
            <div class="d-flex gap-2 mb-1">
                <button style="width: 150px"class='btn btn-success fs-6' id="save_inventory"> تسوية </button>
                <button style="width: 150px"class='btn btn-primary fs-6' id="actual_qty"> الكمية الفعلية </button>
                <button style="width: 150px"class='btn btn-secondary fs-6' id="print"> طباعة </button>
            </div>
        </div>
    </div>
</section>
    <?php echo $__env->make('includes.stock.reports_ajax.inventory', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/stock/reports/openBalance.blade.php ENDPATH**/ ?>
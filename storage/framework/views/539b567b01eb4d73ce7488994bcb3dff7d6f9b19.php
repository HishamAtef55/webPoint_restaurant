<?php $title='المشتريات';?>

<?php $__env->startSection('content'); ?>
<section>
    <div id="permissionId" value="<?php echo e($serial); ?>"></div>
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <div class="bg-light p-4 mb-2 rounded shadow">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-2">
                    <div class="custom-form">
                        <input type="number" value="<?php echo e($serial); ?>" name="permission" id="permission">
                        <label for="permission">رقم الاذن</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="custom-form">
                        <input type="number"  name="series_number" id="series_number">
                        <label for="series_number" >رقم المسلسل</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="custom-form">
                        <input type="date"  name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d',strtotime("-1 days")); ?>">
                        <label for="date" >التاريخ</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="file" class="form-control"  name="permission_file" id="permission_file">
                </div>
                <div class="col-md-3">
                    <div class="custom-form">
                        <textarea  name="notes" id="notes"></textarea>
                        <label for="notes" >الملاحظات</label>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input purchases-method" type="radio" value="section"
                            id="sections_method" name="purchases_method">
                        <label class="form-check-label" for="sections_method">
                            اقسام
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input purchases-method" type="radio" value="store"
                            id="stores_method" name="purchases_method" checked>
                        <label class="form-check-label" for="stores_method">
                            مخازن
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="supplier" class="select-label">الموردين</label>
                    <select class="form-select supplier" id="supplier">
                        <option selected disabled>اختر المورد</option>
                        <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
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
                <div class="col-md-2 branch-sec d-none">
                    <label for="branch"  class="select-label">الفرع</label>
                    <select class="form-select" id="branch">
                        <option selected disabled>اختر الفرع</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 branch-sec d-none">
                    <label for="sections"  class="select-label">القسم</label>
                    <select class="form-select" id="sections">
                    </select>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number"  name="tax" id="tax">
                        <label for="tax" > (%) الضريبة</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number"  name="discount" id="discount">
                        <label for="discount" >الخصم</label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input pay-method" type="radio" value="installment"
                            id="installment_method" name="pay_method">
                        <label class="form-check-label" for="installment_method">
                            اجل
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input pay-method" type="radio" value="cash"
                            id="cash_method" name="pay_method" checked>
                        <label class="form-check-label" for="cash_method">
                            نقدى
                        </label>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row align-items-end" style="margin-top: -1rem;">
                <div class="col-md-2">
                    <div class="custom-form">
                        <input type="date"  name="Expire" id="Expire" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                        <label for="Expire" >تاريخ الصلاحية</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="items"  class="select-label"> الصنف</label>
                    <select id="items">
                        <option selected disabled>اختر الصنف</option>
                    </select>
                </div>
                <div class="col">
                    <label for="unit" class="select-label" >الوحدة</label>
                    <select id="unit">
                        <option selected disabled>اختر الوحدة</option>
                    </select>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number"  name="price_unit" id="price_unit">
                        <label for="price_unit" >السعر</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number"  name="quantity" id="quantity">
                        <label for="quantity" >الكمية</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="number"  name="total_unit" id="total_unit">
                        <label for="total_unit" >الاجمالى</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="text"  name="last_price" id="last_price" disabled>
                        <label for="last_price" >اخر سعر</label>
                    </div>
                </div>
                <div class="col">
                    <div class="custom-form">
                        <input type="text"  name="current_balance" id="current_balance" disabled>
                        <label for="current_balance"> الرصيد الحالى</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive materials-responsive rounded">
            <table class="table table-light table-striped text-center table-purchases">
                <thead>
                    <tr>
                        <th> كود الصنف </th>
                        <th> اسم الصنف </th>
                        <th> تاريخ الصلاحية </th>
                        <th> الوحدة </th>
                        <th> السعر </th>
                        <th> الكمية </th>
                        <th> التكلفة </th>
                        <th> الضريبة </th>
                        <th> الخصم </th>
                        <th> الاجمالى </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="not-found">
                        <td colspan="11">لا يوجد بيانات</td>
                    </tr>
                </tbody>
                <tfoot class="table-dark">
                        <th colspan="6"> الاجمالي </th>
                        <th class="sumTotal">0</th>
                        <th class="sumTax">0</th>
                        <th class="sumDiscount">0</th>
                        <th class="sumFinal">0</th>
                        <th></th>
                </tfoot>
            </table>
        </div>
        <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
            <button class='btn btn-success fs-6' id="save_purchases">Save</button>
            <div class="d-flex gap-2">
                <button class='btn btn-primary fs-6 d-none col' id="update_purchases">Update</button>
                <button class='btn btn-danger fs-6 d-none col' id="delete_purchases">Delete</button>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.Stock_Ajax.purchases', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/stock/stock/purchases.blade.php ENDPATH**/ ?>
<?php $title='الموردين';?>

<?php $__env->startSection('content'); ?>
<section>
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class='row justify-content-center'>
            <div class="col-md-4">
                <div class="bg-light p-2 rounded shadow">
                    <div class="custom-form mt-3">
                        <input type="text" name="supplier_id" id="supplier_id" value="<?php echo e($new_supplier); ?>" disabled>
                        <label for="supplier_id">رقم المورد</label>
                    </div>
                    <div class="custom-form mt-3 position-relative">
                        <input type="text" name="supplier_name" id="supplier_name">
                        <label for="supplier_name">اسم المورد</label>
                        <ul class="search-result"></ul>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" name="supplier_phone" id="supplier_phone">
                        <label for="supplier_phone">رقم الهاتف</label>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" name="supplier_address" id="supplier_address">
                        <label for="supplier_address">عنوان المورد</label>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button class='btn btn-success' id="save_supplier">Save</button>
                    <button class='btn btn-primary d-none' id="update_supplier">Update</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-responsive rounded" style="min-height: 420px">
                    <table class="table table-light text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">الهاتف</th>
                                <th scope="col">العنوان</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($supplires) > 0): ?>
                            <?php $__currentLoopData = $supplires; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplire): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th scope="row"><?php echo e($supplire->id); ?></th>
                                <td><?php echo e($supplire->name); ?></td>
                                <td><?php echo e($supplire->phone); ?></td>
                                <td><?php echo e($supplire->address); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="2">لا يوجد موردين</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.Stock_Ajax.suppliers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/suppliers.blade.php ENDPATH**/ ?>
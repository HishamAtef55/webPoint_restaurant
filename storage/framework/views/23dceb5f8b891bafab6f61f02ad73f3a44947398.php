<?php $title='تسعير اصناف';?>

<?php $__env->startSection('content'); ?>

<section class="expenses">
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class="bg-light p-4 rounded shadow mb-3">
            <div class="row align-items-end">
                <div class="col">
                    <label for="branch" class="select-label">Branch</label>
                    <select id="branch">
                        <option value="all" disabled selected>All Branch</option>
                        <?php $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col">
                    <label for="group" class="select-label">Group</label>
                    <select id="group">
                        <option disabled selected>Select Your Group</option>
                    </select>
                </div>
                <div class="col">
                    <label for="subGroup" class="select-label">Sub Group</label>
                    <select id="subGroup">
                        <option disabled selected>Select Your SubGroup</option>
                    </select>
                </div>
                <div class="col">
                    <label for="material" class="select-label">Material</label>
                    <select id="material">
                        <option value="all" selected>All Materials</option>
                        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($row->code); ?>"><?php echo e($row->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col">
                    <button class='btn btn-success fs-6 w-100' id="save_item_price">Search</button>
                </div>
                <div class="row mt-3">
                    <div class="col mt-2">
                        <div class="custom-form">
                            <input type="number" value="<?php echo e($inDirectCostsSum); ?>" id="total">
                            <label for="total">Total</label>
                        </div>
                    </div>
                    <div class="col mt-2">
                        <div class="custom-form">
                            <input type="number" value="755811" id="expected_sale">
                            <label for="expected_sale">Expected sale</label>
                        </div>
                    </div>
                    <div class="col mt-2">
                        <div class="custom-form">
                            <input type="text" value="50" id="indirect">
                            <label for="indirect">Indirect %</label>
                        </div>
                    </div>
                    <div class="col mt-2">
                        <div class="custom-form">
                            <input type="text" value="10" id="safe">
                            <label for="safe">safe %</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive rounded" dir="ltr">
            <table class="table table-light table-striped text-center">
                <thead>
                    <tr>
                        <th> Item Name </th>
                        <th> Price </th>
                        <th> Price Details </th>
                        <th> Main Cost </th>
                        <th> Details Cost </th>
                        <th> Sauce Cost </th>
                        <th> Packing Cost </th>
                        <th> Undirect </th>
                        <th> Final Cost </th>
                        <th> Net Table </th>
                        <th> Net Take Away </th>
                        <th> Net Delivery </th>
                        <th> Cost % </th>
                        <th> Profit % </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="not-found">
                        <td colspan="14">لا يوجد بيانات</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.reports_ajax.itemsPricing', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/stock/reports/itemsPricing.blade.php ENDPATH**/ ?>
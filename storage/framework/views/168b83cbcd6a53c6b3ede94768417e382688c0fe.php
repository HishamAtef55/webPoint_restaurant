<?php $title='المصاريف الغير مباشرة';?>

<?php $__env->startSection('content'); ?>
<section class="expenses">
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="bg-light p-4 mb-2 rounded shadow mb-3">
                    <div class="d-flex gap-3">
                        <div class="custom-form flex-grow-1">
                            <input type="text" value="" name="expenses_name" id="expenses_name">
                            <label for="expenses_name">اسم المصروف</label>
                        </div>
                        <button class='btn btn-success fs-6' id="save_expenses">Save</button>
                    </div>
                </div>
                <div class="table-responsive expenses-name-responsive rounded">
                    <table class="table table-light table-striped text-center expenses_name_table table-purchases">
                        <thead>
                            <tr>
                                <th> اسم المصروف </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($data) > 0): ?>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr rowId="<?php echo e($one->id); ?>">
                                        <td>
                                            <input type="text" class="form-control" value="<?php echo e($one->name); ?>"/>
                                            <span><?php echo e($one->name); ?></span>
                                        </td>
                                        <td>
                                            <div class="del-edit">
                                                <button class="btn btn-danger delete_expenses"><i class="fa-regular fa-trash-can"></i></button>
                                                <button class="btn btn-warning edit_expenses"><i class="fa-regular fa-pen-to-square"></i></button>
                                            </div>
                                            <button class="btn btn-primary update_expenses update">Update</button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr class="not-found">
                                <td colspan="2">لا يوجد بيانات</td>
                            </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-light p-4 pt-2 mb-2 rounded shadow mb-3">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3">
                            <div class="custom-form">
                                <input type="date"  name="date" id="date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d',strtotime("-1 days")); ?>">
                                <label for="date" >التاريخ</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expenses" class="select-label">المصروف</label>
                            <select class="form-select" id="expenses">
                                <option selected disabled>المصروف</option>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $one): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($one->id); ?>"><?php echo e($one->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="custom-form">
                                <input type="number"  name="expenses_price" id="expenses_price">
                                <label for="expenses_price" >المبلغ</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class='btn btn-block btn-success fs-6' id="add_expenses">Add</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive expenses-responsive rounded">
                    <table class="table table-light table-striped text-center expenses_table table-purchases">
                        <thead>
                            <tr>
                                <th> اسم المصروف </th>
                                <th> التاريخ </th>
                                <th> المبلغ </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(count($inDirectCosts) > 0): ?>
                            <?php $__currentLoopData = $inDirectCosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr rowId="<?php echo e($row->id); ?>">
                                    <td><?php echo e($row->cost->name); ?></td>
                                    <td><?php echo e($row->date); ?></td>
                                    <td><?php echo e($row->value); ?></td>
                                    <td>
                                        <div class="del-edit">
                                            <button class="btn btn-danger delete_expenses"><i class="fa-regular fa-trash-can"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <tr class="not-found">
                            <td colspan="4">لا يوجد بيانات</td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.Stock_Ajax.indirect_cost', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/indirect_cost.blade.php ENDPATH**/ ?>
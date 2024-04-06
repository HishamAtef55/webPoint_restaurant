<?php $title='المجموعات';?>

<?php $__env->startSection('content'); ?>
<section >
    <h2 class="page-title"><?php echo e($title); ?></h2>
        <div class="container">
            <?php echo csrf_field(); ?>
            <div class='row justify-content-center'>
                <div class="col-md-4">
                    <div class="bg-light p-2 rounded shadow">
                        <div class="custom-form mt-3">
                            <input type="text" name="group_id" id="group_id" value="<?php echo e($new_id); ?>" disabled>
                            <label for="group_id">رقم المجموعة </label>
                        </div>
                        <div class="custom-form mt-3 position-relative">
                            <input type="text" name="group_name" id="group_name">
                            <label for="group_name" >اسم المجموعة </label>
                            <ul class="search-result"></ul>
                        </div>
                    </div>
                    <div class="d-grid gap-2  mt-3">
                        <button class='btn btn-success' id="save_group">Save</button>
                        <button class='btn btn-primary d-none' id="update_group">Update</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="table-responsive rounded" style="min-height: 420px">
                        <table class="table table-light text-center">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المجموعة</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(count($groups) > 0): ?>
                                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($group->id); ?></th>
                                        <td><?php echo e($group->name); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">لا يوجد مجموعات</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php echo $__env->make('includes.stock.Stock_Ajax.mainGroup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/mainGroup.blade.php ENDPATH**/ ?>
<?php $title='المجموعات الفرعية';?>

<?php $__env->startSection('content'); ?>
<section>
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class='row justify-content-center'>
            <div class="col-md-5">
                <div class="bg-light p-2 rounded shadow">
                    <div class="custom-form mt-3">
                        <input type="text" name="group_id" id="group_id" value="<?php echo e($new_group); ?>" disabled>
                        <label for="group_id">رقم المجموعة الفرعية</label>
                    </div>
                    <div>
                        <label for="main_group"  class="select-label">المجموعه الرئيسية</label>
                        <select class="form-select" id="main_group">
                            <option selected disabled>اختر المجموعه الرئيسية</option>
                            <?php $__currentLoopData = $mainGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="custom-form mt-3 position-relative">
                        <input type="text" name="group_name" id="group_name">
                        <label for="group_name">اسم المجموعة الفرعية</label>
                        <ul class="search-result"></ul>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" name="group_from" id="group_from">
                        <label for="group_from">بداية الترقيم</label>
                    </div>
                    <div class="custom-form mt-3">
                        <input type="text" name="group_to" id="group_to">
                        <label for="group_to">نهاية الترقيم</label>
                    </div>
                </div>

                <div class="d-grid gap-2  mt-3">
                    <button class='btn btn-success' id="save_group">Save</button>
                    <button class='btn btn-primary d-none' id="update_group">Update</button>
                </div>
            </div>
            <div class="col-md-7">
                <div class="table-responsive rounded"  style="min-height: 420px">
                    <table class="table table-light text-center">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المجموعة الرئيسية</th>
                                <th scope="col">المجموعة الفرعية </th>
                                <th scope="col">بداية الترقيم</th>
                                <th scope="col">نهاية الترقيم</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(count($groups) > 0): ?>
                            <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($group->id); ?></th>
                                    <td><?php echo e($group->maingroup->name); ?></td>
                                    <td><?php echo e($group->name); ?></td>
                                    <td><?php echo e($group->start_serial); ?></td>
                                    <td><?php echo e($group->end_serial); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr class="not-found">
                                <td colspan="5">لا يوجد مجموعات</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
    <?php echo $__env->make('includes.stock.Stock_Ajax.groups', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/groups.blade.php ENDPATH**/ ?>
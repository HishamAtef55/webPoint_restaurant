<?php $title='الاقسام';?>

<?php $__env->startSection('content'); ?>
<section>
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class='row justify-content-center'>
            <div class="col-md-4">
                <div class="bg-light p-2 rounded shadow">
                    <div class="custom-form mt-3">
                        <input type="text" name="section_id" id="section_id" value="<?php echo e($new_section); ?>" disabled>
                        <label for="section_id" >رقم القسم</label>
                    </div>
                    <div>
                        <label for="store" class="select-label">اسم المخزن</label>
                        <select class="form-select unit" id="store">
                            <option selected disabled>اختر المخزن</option>
                            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($store->id); ?>"><?php echo e($store->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="branch" class="select-label">اسم الفرع</label>
                        <select class="form-select unit" id="branch">
                            <option selected disabled>اختر الفرع</option>
                            <?php $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="custom-form mt-3 position-relative">
                        <input type="text"  name="section_name" id="section_name">
                        <label for="section_name" >اسم القسم</label>
                        <ul class="search-result"></ul>
                    </div>
                </div>
                <div class="bg-light p-2 rounded shadow mt-2">
                    <h3>المجموعات</h3>
                    <div class="groups">
                    </div>
                </div>

                <div class="d-grid gap-2  mt-3">
                    <button class='btn btn-success' id="save_section">Save</button>
                    <button class='btn btn-primary d-none' id="update_section">Update</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-responsive rounded"  style="min-height: 420px">
                    <table class="table table-light text-center">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">الفرع</th>
                            <th scope="col">الاسم</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(count($sections) > 0): ?>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($section->id); ?></th>
                                    <td><?php echo e($section->sectionsBranch->name); ?></td>
                                    <td><?php echo e($section->name); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">لا يوجد أقسام</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.Stock_Ajax.sections', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/sections.blade.php ENDPATH**/ ?>
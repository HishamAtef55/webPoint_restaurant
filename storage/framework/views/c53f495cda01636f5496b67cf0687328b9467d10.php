<?php $title='الخامات';?>

<?php $__env->startSection('content'); ?>
<div id="pageStatus" status="new"></div>
<section>
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class='row'>
            <div class="col-lg-7 col-md-8">
                <div class="bg-light p-2 rounded shadow">
                    <div class="row align-items-end">
                        <div class="col">
                            <div class="custom-form position-relative">
                                <input type="text" name="material_id" id="material_id" value="1" disabled>
                                <label for="material_id">كود الخامة</label>
                            </div>
                        </div>
                        <div class="col flex-grow-1">
                            <div>
                                <label for="main_group" class="select-label">المجموعة الرئيسية</label>
                                <select id="main_group">
                                    <option selected disabled>اختر المجموعة الرئيسية</option>
                                        <?php $__currentLoopData = $mainGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col flex-grow-1">
                            <div>
                                <label for="sub_group" class="select-label">المجموعة الفرعية</label>
                                <select class="form-select" id="sub_group">
                                    <option selected disabled>اختر المجموعة الفرعية</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0" />
                    <div class="row align-items-end">
                        <div class="col">
                            <div class="custom-form position-relative">
                                <input type="text" name="material_name" id="material_name">
                                <label for="material_name">اسم الخامة</label>
                                <ul class="search-result"></ul>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-form">
                                <input type="number" name="standard_cost" id="standard_cost">
                                <label for="standard_cost">التكلفة المعيارية</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-form ">
                                <input type="number" name="price" id="price">
                                <label for="price">السعر</label>
                            </div>
                        </div>
                        <div class="col">
                            <div>
                                <label for="unit" class="select-label">وحدة القياس</label>
                                <select id="unit">
                                    <option selected disabled>اختر وحدة القياس</option>
                                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($unit->name); ?>"><?php echo e($unit->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0" />
                    <div class="row">
                        <div class="col">
                            <div class="custom-form mt-3">
                                <input type="number" name="store_limit_min" id="store_limit_min">
                                <label for="store_limit_min"> طلب المخزن (min)</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-form mt-3">
                                <input type="number" name="store_limit_max" id="store_limit_max">
                                <label for="store_limit_max"> طلب المخزن (max)</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-form mt-3">
                                <input type="number" name="section_limit_min" id="section_limit_min">
                                <label for="section_limit_min"> طلب القسم (min)</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-form mt-3">
                                <input type="number" name="section_limit_max" id="section_limit_max">
                                <label for="section_limit_max"> طلب القسم (max)</label>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0" />
                    <div class="row align-items-end">
                        <div class="col">
                            <div class="custom-form mt-3">
                                <input type="number" name="loss_ratio" id="loss_ratio">
                                <label for="loss_ratio">نسبة الفقد</label>
                            </div>
                        </div>
                        <div class="col">
                            <div >
                                <label for="store_method" class="select-label">نوع التخزين</label>
                                <select id="store_method">
                                    <option selected disabled>اختر نوع التخزين</option>
                                        <option value="تجميد">تجميد</option>
                                        <option value="تبريد">تبريد</option>
                                        <option value="أرضية">أرضية</option>
                                        <option value="أرفف">أرفف</option>
                                        <option value="اخري">اخري</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="custom-form mt-3">
                                <input type="number" name="expire" id="expire">
                                <label for="expire">مدة الصلاحية</label>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0" />
                    <div class="row">
                        <div class="col d-flex align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1"
                                    id="daily_inventory" name="daily_inventory">
                                <label class="form-check-label" for="daily_inventory">
                                    جرد يومى
                                </label>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="packing" name="packing">
                                <label class="form-check-label" for="packing"> باكدج </label>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1"
                                    id="all_group" name="all_group">
                                <label class="form-check-label" for="all_group">
                                    جميع المجموعات
                                </label>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="manfu" name="manfu">
                                <label class="form-check-label" for="manfu"> خامة مصنعة </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-light p-2 mt-2 rounded shadow">
                    <h5>الفروع</h5>
                    <?php $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check my-2">
                            <input class="form-check-input" type="checkbox" value="<?php echo e($branch->id); ?>" id="branch_<?php echo e($branch->id); ?>" name="branch">
                            <label class="form-check-label" for="branch_<?php echo e($branch->id); ?>"> <?php echo e($branch->name); ?> </label>
                        </div>
                        <div class="branch-sections bg-success bg-opacity-25 rounded-3 d-none"></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="col-lg-5 col-md-4">
                <div class="table-responsive rounded"  style="min-height: 420px">
                    <table class="table table-light text-center">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">المجموعة الفرعية</th>
                            <th scope="col">الخامة</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if(count($materials) > 0): ?>
                                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($group->code); ?></th>
                                        <td><?php echo e($group->group->name); ?></td>
                                        <td><?php echo e($group->name); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">لا يوجد خامات</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button class='btn btn-success' id="save_material">Save</button>
                    <button class='btn btn-primary d-none' id="update_material">Update</button>
                </div>
            </div>

        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.Stock_Ajax.material', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/stock/stock/material.blade.php ENDPATH**/ ?>
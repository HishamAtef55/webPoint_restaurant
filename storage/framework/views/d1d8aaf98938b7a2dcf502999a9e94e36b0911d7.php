<?php $title='مكونات الخامات';?>

<?php $__env->startSection('content'); ?>
<section >
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-5">
                <div class="bg-light p-2 rounded shadow">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <label class="select-label">اسم الخامة</label>
                                <select id="main_material">
                                    <option disabled selected>اختر الخامه</option>
                                    <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option data-price="<?php echo e($material->cost); ?>" value="<?php echo e($material->code); ?>"><?php echo e($material->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="custom-form mt-3">
                                <input type="text" name="item_price" id="item_price" disabled>
                                <label for="item_price" >سعر التكلفة</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="custom-form mt-3">
                                <input type="number" min='1' value="1" name="product_qty" id="product_qty" >
                                <label for="product_qty">الكمية المنتجة</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="select-box">
                                <label class="select-label"> المجموعة الرئيسية </label>
                                <select id="main_group">
                                    <option disabled selected> اختر المجموعة الرئيسية </option>
                                    <option value="all">all</option>
                                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($group->id); ?>"><?php echo e($group->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="custom-form mt-3">
                                <label for="materials" class="select-label">الخامة</label>
                                <select id="materials">
                                    <option disabled selected>اختر الخامة</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="custom-form mt-3">
                                <input type="number" name="unit" id="unit">
                                <label for="unit">الكمية ( <span id="unit_label"></span> )</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="custom-form mt-3">
                                <input type="number" name="unit_price" id="unit_price" disabled>
                                <label for="unit_price">سعر الوحدة</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-grid gap-2 mt-4">
                    <button class='btn btn-success' id="save_component">Save</button>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="table-responsive materials-responsive rounded" style="min-height: 420px">
                    <table class="table table-light table-striped text-center table-materials">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>كود المكون</th>
                                <th>اسم المكون</th>
                                <th>الكمية</th>
                                <th>التكلفة</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="not-found">
                                <td colspan="6">لا يوجد بيانات</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td>0</td>
                                <td>النسبة</td>
                                <td><input type="number" class="percentage" value="0" disabled> <span class="fs-5">%</span> </td>
                                <td>الاجمالى</td>
                                <td><input type="number" class="total-price" value="0" disabled></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex flex-wrap gap-2 material-buttons justify-content-center mt-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#transferModal">
                        تكرار مكونات الخامات
                    </button>
                    <button id="print_components" class="btn btn-warning">طباعة المكونات</button>
                    <button id="print_component" class="btn btn-warning">طباعة مكون</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade transferModal" id="transferModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fw-bold" id="exampleModalLabel">تحويل المكونات</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="custom-form mt-3">
                            <label for="fromItems">بيانات الخامات</label>
                            <select class="form-control select2" id="fromItems">
                                <option disabled selected></option>
                                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option data-price="<?php echo e($material->price); ?>" value="<?php echo e($material->code); ?>"><?php echo e($material->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <ul class='fromComponents'></ul>
                    </div>
                    <div class="col-md-2 text-center align-self-center">
                        <button class="btn dark-btn transAll">TransAll</button>
                    </div>
                    <div class="col-md-5">
                        <div class="custom-form mt-3">
                            <label for="toItems"> اسم الخامات </label>
                            <select class="form-control select2" id="toItems">
                                <option disabled selected></option>
                                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option data-price="<?php echo e($material->price); ?>" value="<?php echo e($material->code); ?>"><?php echo e($material->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <ul class='toComponents'></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save_transfer">حفظ</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade reportModal" id="reportModal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="fw-bold"  id="labelModel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="report_content" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('includes.stock.Stock_Ajax.material_recipe', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/material_recipe.blade.php ENDPATH**/ ?>
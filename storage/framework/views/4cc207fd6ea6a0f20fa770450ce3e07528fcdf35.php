<?php $title='المخازن';?>

<?php $__env->startSection('content'); ?>
<section class='store'>
    <h2 class="page-title"><?php echo e($title); ?></h2>
    <div class="container">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-6">
                <div class="bg-light p-2 rounded shadow">
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <div class="custom-form">
                                <input type="text" name="store_id" id="store_id" value="<?php echo e($new_store); ?>" disabled>
                                <label for="store_id">رقم المخزن</label>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="custom-form position-relative">
                                <input type="text" name="store_name" id="store_name">
                                <label for="store_name">اسم المخزن</label>
                                <ul class="search-result"></ul>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="custom-form">
                                <input type="text" name="store_phone" id="store_phone">
                                <label for="store_phone">تليفون</label>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="custom-form">
                                <input type="text" name="store_address" id="store_address">
                                <label for="store_address">العنوان</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-light p-2 rounded shadow mt-4">
                    <!-- <h4>طريقة التخزين</h4> -->
                    <table class="store-table">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input method-check" type="checkbox" value="تجميد" id="freeze_method" name="storage_method">
                                        <label class="form-check-label" for="freeze_method">
                                            تجميد
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <label for="freeze" class="select-label">الوحدة</label>
                                    <select class="form-select unit" id="freeze">
                                        <option selected disabled>اختر نوع الوحدة</option>
                                        <option value="كيلو">كيلو</option>
                                        <option value="لتر">لتر</option>
                                        <option value="عدد">عدد</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="custom-form">
                                        <input type="text" class="form-control" name="capacity" id="freeze_capacity">
                                        <label for="freeze_capacity"> السعة</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input method-check" type="checkbox" value="تبريد" id="cool_method" name="storage_method">
                                        <label class="form-check-label" for="cool_method">
                                            تبريد
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <label for="cool" class="select-label">الوحدة</label>
                                    <select class="form-select unit" id="cool">
                                        <option selected disabled>اختر نوع الوحدة</option>
                                        <option value="كيلو">كيلو</option>
                                        <option value="لتر">لتر</option>
                                        <option value="عدد">عدد</option>
                                    </select>

                                </td>
                                <td>
                                    <div class="custom-form">
                                        <input type="text" class="form-control" name="capacity" id="cool_capacity">
                                        <label for="cool_capacity"> السعة</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input method-check" type="checkbox" value="أرضية" id="floor_method" name="storage_method">
                                        <label class="form-check-label" for="floor_method">
                                            أرضية
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <label for="floor" class="select-label">الوحدة</label>
                                    <select class="form-select unit" id="floor">
                                        <option selected disabled>اختر نوع الوحدة</option>
                                        <option value="كيلو">كيلو</option>
                                        <option value="لتر">لتر</option>
                                        <option value="عدد">عدد</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="custom-form">
                                        <input type="text" class="form-control" name="capacity" id="floor_capacity">
                                        <label for="floor_capacity"> السعة</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input method-check" type="checkbox" value="أرفف" id="shelf_method" name="storage_method">
                                        <label class="form-check-label" for="shelf_method">
                                            أرفف
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <label for="shelf" class="select-label">الوحدة</label>
                                    <select class="form-select unit" id="shelf">
                                        <option selected disabled>اختر نوع الوحدة</option>
                                        <option value="كيلو">كيلو</option>
                                        <option value="لتر">لتر</option>
                                        <option value="عدد">عدد</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="custom-form">
                                        <input type="text" class="form-control" name="capacity" id="shelf_capacity">
                                        <label for="shelf_capacity"> السعة</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input method-check" type="checkbox" value="اخري" id="other_method" name="storage_method">
                                        <label class="form-check-label" for="other_method">
                                            اخري
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <label for="other" class="select-label">الوحدة</label>
                                    <select class="form-select unit" id="other">
                                        <option selected disabled>اختر نوع الوحدة</option>
                                        <option value="كيلو">كيلو</option>
                                        <option value="لتر">لتر</option>
                                        <option value="عدد">عدد</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="custom-form">
                                        <input type="text" class="form-control" name="capacity" id="other_capacity">
                                        <label for="other_capacity"> السعة</label>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-grid gap-2 mx-auto mt-4">
                    <button class='btn btn-success' id="save_store">Save</button>
                    <button class='btn btn-primary d-none' id="update_store">Update</button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="table-responsive rounded">
                    <table class="table table-light text-center table-data">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">name</th>
                                <th scope="col">phone</th>
                                <th scope="col">address</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if(count($stores) > 0): ?>
                            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <th><?php echo e($store->id); ?></th>
                                <td><?php echo e($store->name); ?></td>
                                <td><?php echo e($store->phone ?? "-"); ?></td>
                                <td><?php echo e($store->address ?? "-"); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>

                                <td colspan="2"> لا يوجد مخازن </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('includes.stock.Stock_Ajax.stores', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.stock.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\web_point\resources\views/stock/stock/stores.blade.php ENDPATH**/ ?>
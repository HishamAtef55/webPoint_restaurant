<?php
$title = 'Extra';
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section>
    <div class='container'>
        <h2 class="section-title"> Items Extra </h2>

        <div class='row'>
            <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>
                <?php echo csrf_field(); ?>
                <div class='row'>
                    <div class='col-md-6'>

                        <div class="select-box">
                            <select class="select_Branch" name="branch" id="select_branch">
                                <option value=""></option>
                                <?php $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='branch-input'>Chose Branch...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='branch-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>
                            </div>
                        </div>

                        <div class="select-box">

                            <select class="select_menu" name="menu" id="select_menu">

                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='menu-input'>Chose Menu...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='menu-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class="select-box">

                            <select class="select_group" name="group" id="select_group">

                            </select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='group-input'>Chose Group...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='group-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class="select-box">

                            <select class="select_subgroup" name="subgroup" id="select_subgroup"></select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='subgroup-input'>Chose SubGroup...</label>
                                    <input autocomplete="off" type='text' class="search-input" id='subgroup-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                        <div class="select-box">

                            <select class="select_items_sub" name="select_items" id="select_items_sub"></select>

                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='select_items-input'>Chose Item...</label>
                                    <input autocomplete="off" type='text' class="search-input"
                                        id='select_items-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class='col-md-6'>

                        <div class="form-element">
                            <label class='input-label' for="extra_search">Search Extra</label>
                            <input name="extra_search" class='mycustom-input' type="text" id="extra_search" />
                            <span class='under_line'></span>
                        </div>

                        <select id="view_extra" class='w-100 multi-section' name="extra" multiple></select>
                    </div>

                    <div class='col-md-6 offset-md-3 my-4'>
                        <button class='btn btn-block btn-success' id="export_extra">Export</button>
                    </div>

                    <div class="table-responsive">
                        <?php echo csrf_field(); ?>
                        <table id="editable" class="table table-bordered table-striped" font_Size="15">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Operation</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<!--     <?php echo $__env->make('includes.control.add_genral_ajax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;

 -->
<?php echo $__env->make('includes.control.add_item_ajax', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes.control.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/control/add_Items_extra.blade.php ENDPATH**/ ?>
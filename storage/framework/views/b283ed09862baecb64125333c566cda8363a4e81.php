<?php
$title = 'Group';
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class='accordions-sec'>
    <div class="container">

        <h2 class="section-title">Group</h2>


        <div class="row">
            <div class="col-lg-10 offset-lg-1 col-md-12">

                <div class="select-box">

                    <select class="select_Branch" name="branch" id="select_branch">
                        <option value=""></option>
                        <?php $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($branch->id); ?>"><?php echo e($branch->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='branch-input'>Choose Branch...</label>
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

                    <select class="select_menu" name="branch" id="select_menu">

                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='menu-input'>Choose Menu...</label>
                            <input autocomplete="off" type='text' class="search-input" id='menu-input' />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>

                    </div>

                </div>

                <div class="d-flex my-4">
                    <div class="form-element m-0">
                        <label for="search" class="input-label"><i class="fas fa-search"></i> Search for
                            Group</label>
                        <input autocomplete="off" type="text" name="search" id="search" class="mycustom-input">
                        <span class='under_line'></span>
                    </div>
                    <button id="save_group" class="btn btn-primary ml-2" type="button"><i
                            class="fas fa-plus"></i></button>
                </div>

                <?php echo csrf_field(); ?>
                <div class="table-responsive">
                    <table id="editable" class="table table-bordered table-striped" font_Size="15">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Menu</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</section>
<?php echo $__env->make('includes.control.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('includes.control.group', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\MyWork\Res\ERP\webPoint\resources\views/control/update_group.blade.php ENDPATH**/ ?>
<?php
$title = 'Min-charge';
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section>
    <div class='container'>
        <div class='row'>

            <div class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1'>

                <h2 class="section-title"> Min-Charge </h2>

                <form id="form_save_mincharge" action=" " method="POST" multiple enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
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

                    <div class='form-element'>
                        <label class='input-label' for="all_holes">Min-Charge</label>
                        <input type="number" class="mycustom-input" id="all_holes" name="all_holes" />
                        <span class='under_line'></span>
                    </div>

                    <div class="table-responsive">

                        <table id="editable" class="table table-bordered table-striped" font_Size="15">
                            <thead class="thead-dark">

                                <tr>
                                    <th scope="col" class='check-column'>
                                        <input type="checkbox" value="1" class="form-check-input" id="check-all" name="fast_check">
                                    </th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Min-Chrage</th>
                                </tr>

                            </thead>

                            <tbody id="tbody">

                            </tbody>
                        </table>

                    </div>

                    <div class='col-md-6 offset-md-3'>
                        <button id="save_mincharge" class="btn btn-block btn-success">Save</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</section>
<?php echo $__env->make('includes.control.mincharge', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\MyWork\Res\webPoint\resources\views/control/mincharge.blade.php ENDPATH**/ ?>
<?php
    $title = 'Information';
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section class='accordions-sec'>
    <div class="container">
        <h2 class="section-title">Information</h2>
        <form id="form_save_resturant" action="" method="POST" multiple enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="select-box">
                        <select class="select_Branch" name="branch" id="select">
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-element input-empty">
                                <label class='input-label' for="res-name"> Resturant Name </label>
                                <input autocomplete="off" name="name" class='mycustom-input' type="text" id="res-name" />
                                <span class='under_line'></span>
                            </div>

                            <div class="form-element input-empty">
                                <label class='input-label' for="res-phone"> Resturant Phone </label>
                                <input autocomplete="off" name="phone" class='mycustom-input' type="number" id="res-phone" />
                                <span class='under_line'></span>
                            </div>

                            <div class="form-element h-auto input-empty">
                                <label class='input-label' for="note">Note</label>
                                <textarea class='mycustom-input' id="note" name="note" type="text"></textarea>
                                <span class='under_line'></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Upload Image -->
                            <div class='text-center'>
                                <i class="file-image">
                                    <input autocomplete="off" id="res-image" name="image" type="file" onchange="readImage(this)" title="" />
                                    <i class="reset" onclick="resetImage(this.previousElementSibling)"></i>
                                    <div class="res-image">
                                        <label for="res-image" class="image" data-label="Resturant Image"></label>
                                    </div>
                                </i>
                            </div>

                            <div class='text-center'>
                                <i class="file-image">
                                    <input autocomplete="off" id="slogan-image" name="slogan" type="file" onchange="readImage(this)" title="" />
                                    <i class="reset" onclick="resetImage(this.previousElementSibling)"></i>
                                    <div class="slogan-image">
                                        <label for="slogan-image" class="image" data-label="Slogan Image"></label>
                                    </div>
                                </i>
                            </div>
                        </div>
                        <div class="col-md-6 offset-md-3">
                            <button id="save_res" type="submit" class="btn-block btn btn-success"> Save </button>
                            <button id="update_res" type="submit" class="btn-block btn btn-primary d-none"> Update </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?php echo $__env->make('includes.control.information', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\MyWork\Res\ERP\webPoint\resources\views/control/informations.blade.php ENDPATH**/ ?>
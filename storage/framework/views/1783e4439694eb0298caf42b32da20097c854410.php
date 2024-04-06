<?php
$title = 'Services-Tables';
?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section>
    <div class='container'>
        <div class='row'>

            <div class='col-xl-10 offset-xl-1 col-lg-12'>
                <h2 class='section-title'> Tabels </h2>
                <form id="form_save_services_tables" action=" " method="POST" multiple enctype="multipart/form-data">
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

                    <div class='custom-grid-delivery'>
                        <div class='form-element'>
                            <label class='input-label' for="tax">Tax</label>
                            <input type="number" class="mycustom-input" id="tax" name="tax" />
                            <span class='under_line'></span>
                        </div>

                        <div class="form-group">
                            <input type="radio" value="1" id="With_tax_service" name="discount_tax_service">
                            <label class="ml-1" for="With_tax_service">Without Discount</label>
                        </div>

                        <div class="form-group">
                            <input type="radio" value="0" id="Without_tax_service" name="discount_tax_service">
                            <label class="ml-1" for="Without_tax_service">With Discount</label>
                        </div>

                        <div class='form-element'>
                            <label class='input-label' for="service_ratio">Service Ratio</label>
                            <input type="number" class="mycustom-input" id="service_ratio" name="service_ratio" />
                            <span class='under_line'></span>
                        </div>

                        <div class='form-element'>
                            <label class='input-label' for="invoic_copies">Invoice Copies No</label>
                            <input type="number" class="mycustom-input" id="invoic_copies" name="invoic_copies" />
                            <span class='under_line'></span>
                        </div>



                        <div class='form-element'>
                            <label class='input-label' for="bank-ratio">Bank Ratio</label>
                            <input type="number" class="mycustom-input" id="bank-ratio" name="bank_ratio" />
                            <span class='under_line'></span>
                        </div>

                        <div class="select-box">
                            <select class="select_printers" id="select">
                                <option></option>
                                <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($printer->printer); ?>"><?php echo e($printer->printer); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='printers-input'>Print Invoice...</label>
                                    <input autocomplete="off" name="printer" type='text' class="search-input"
                                        id='printers-input' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>
                            </div>
                        </div>

                        <div class="select-box">
                            <select class="printer_shift" id="printer_shift">
                                <option></option>
                                <?php $__currentLoopData = $printers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $printer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($printer->printer); ?>"><?php echo e($printer->printer); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class='search-select'>
                                <div class='label-Select'>
                                    <label for='printers-input-shift'>Print Close Shift...</label>
                                    <input autocomplete="off" name="printer_shift" type='text' class="search-input"
                                        id='printers-input-shift' />
                                    <i class='arrow'></i>
                                    <span class='line'></span>
                                </div>

                                <div class='input-options'>
                                    <ul></ul>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <input type="checkbox" value="1" id="tax_service" name="tax_service">
                            <label class="ml-1" for="tax_service">With Tax + Service</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="print_slip" name="print_slip">
                            <label class="ml-1" for="print_slip">Print Slip</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="car_receipt" name="car_receipt">
                            <label class="ml-1" for="car_receipt">Print Car Service Receipt</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="pr_reservation" name="pr_reservation">
                            <label class="ml-1" for="pr_reservation">Print Reservation Receipt</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="slip_copy" name="slip_copy">
                            <label class="ml-1" for="slip_copy">Print Slip Copy</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="slip_all" name="slip_all">
                            <label class="ml-1" for="slip_all">Print Slip All</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="receipt_send" name="receipt_send">
                            <label class="ml-1" for="receipt_send">Print Table Receipt After Send</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="receipt_checkout" name="receipt_checkout">
                            <label class="ml-1" for="receipt_checkout">Print Table Receipt After Checkout</label>
                        </div>

                        <div class="form-group ">
                            <input type="checkbox" value="1" id="display_table" name="display_table">
                            <label class="ml-1" for="display_table">Display Table</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="mincharge_screen" name="mincharge_screen">
                            <label class="ml-1" for="mincharge_screen">Display MinCharge Screen</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="vou_copon" name="vou_copon">
                            <label class="ml-1" for="vou_copon">Vouvher Copon</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="end_teble" name="end_teble">
                            <label class="ml-1" for="end_teble">End Table</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="invoic_teble" name="invoic_teble">
                            <label class="ml-1" for="invoic_teble">Print Invoice After End Table</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="payment_teble" name="payment_teble">
                            <label class="ml-1" for="payment_teble">Display Payment After End Table</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="invoice_payment" name="invoice_payment">
                            <label class="ml-1" for="invoice_payment">Print Invoice After Check Payment</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="reser_recipt" name="reser_recipt">
                            <label class="ml-1" for="reser_recipt">Print Reservation Receipt</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="print_invoic" name="print_invoic">
                            <label class="ml-1" for="print_invoic">Print Invoice</label>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" value="1" id="fast_checkout" name="fast_checkout">
                            <label class="ml-1" for="fast_checkout">Fast Checkout</label>
                        </div>
                    </div>

                    <div class='col-md-6 offset-md-3 mb-5 pb-3'>
                        <button type="submit" id="save_services_tables" class="btn btn-block btn-success">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
<?php echo $__env->make('includes.control.services_tables', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webpoint\resources\views/control/services_tables.blade.php ENDPATH**/ ?>
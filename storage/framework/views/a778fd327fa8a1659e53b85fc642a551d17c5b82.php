<?php
$title = 'Add Other';
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('layouts.nav_left', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<section>
    <div class='container'>
        <h2 class='section-title'> Others </h2>
        <form id="form_save_other" action=" " method="POST" multiple enctype="multipart/form-data">

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
                <div class="select-box">
                    <select class="select_printers_Invoice" id="select_printers_Invoice">
                        <option></option>
                        <option value="One">One</option>
                        <option value="Two">Two</option>
                        <option value="Three">Three</option>
                        <option value="Four">Four</option>
                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='select_printers_Invoice-input'>Print Invoice...</label>
                            <input autocomplete="off" id='select_printers_Invoice-input' name="printers_Invoice"
                                type='text' class="search-input" />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>

                    </div>
                </div>

                <div class="select-box">
                    <select class="def-transaction" id="select-def-transaction">
                        <option></option>
                        <option value="One">One</option>
                        <option value="Two">Two</option>
                        <option value="Three">Three</option>
                        <option value="Four">Four</option>
                    </select>
                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='transaction'>Select Your Option</label>
                            <input autocomplete="off" type='text' class="search-input" id='transaction'
                                name="transaction_printer" />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>
                    </div>
                </div>

                <div class="select-box">
                    <select class="drawer-printer" id="select-drawer-printer">
                        <option></option>
                        <option value="One">One</option>
                        <option value="Two">Two</option>
                        <option value="Three">Three</option>
                        <option value="Four">Four</option>
                    </select>
                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='Drawer'>Select Your Option</label>
                            <input autocomplete="off" type='text' class="search-input" id='Drawer'
                                name="drawer_printer" />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>
                    </div>
                </div>

                <div class="select-box">
                    <select class="select_printers" id="select">
                        <option></option>
                        <option value="One">One</option>
                        <option value="Two">Two</option>
                        <option value="Three">Three</option>
                        <option value="Four">Four</option>
                    </select>

                    <div class='search-select'>
                        <div class='label-Select'>
                            <label for='printers-input'>Print Invoice...</label>
                            <input autocomplete="off" id='printers-input' name="printer" type='text'
                                class="search-input" />
                            <i class='arrow'></i>
                            <span class='line'></span>
                        </div>

                        <div class='input-options'>
                            <ul></ul>
                        </div>

                    </div>
                </div>

                <div class='form-element'>
                    <label class='input-label' for="reser-copies">Reservation Copies No</label>
                    <input type="number" class="mycustom-input" id="reser-copies" name="reservation_copies" />
                    <span class='under_line'></span>
                </div>

                <div class='form-element'>
                    <label class='input-label' for="transaction-copies">Disable Transaction Types On</label>
                    <input type="number" class="mycustom-input" id="transaction-copies" name="transaction_copies" />
                    <span class='under_line'></span>
                </div>

                <div class='form-element'>
                    <label class='input-label' for="fast-checkout">Fast Checkout On</label>
                    <input type="number" class="mycustom-input" id="fast_checkout" name="fast_checkout" />
                    <span class='under_line'></span>
                </div>

                <div class='form-element'>
                    <label class='input-label' for="fast-checkout">Time Close Day</label>
                    <input type="time" class="mycustom-input" id="close_day" name="close_day" />
                    <span class='under_line'></span>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="allow-void" name="allow_void">
                    <label class="ml-1" for="allow-void">Discount(Item + Details)</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="allow-update" name="allow_update">
                    <label class="ml-1" for="allow-update">Allow Update</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="void-priming" name="void_priming">
                    <label class="ml-1" for="void-priming">Dauy void Befora
                        Priming</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-modify" name="display_modify">
                    <label class="ml-1" for="dis-modify">Direct Hold List</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-totals" name="display_total">
                    <label class="ml-1" for="dis-totals">Display Totals</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-waiter" name="display_waiter">
                    <label class="ml-1" for="dis-waiter">Display Waiter</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="item-tax" name="item_tax">
                    <label class="ml-1" for="item-tax">Use ltem tax</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="item-service" name="item_service">
                    <label class="ml-1" for="item-service">Use ltem Service</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-addition" name="display_addition">
                    <label class="ml-1" for="dis-addition">Display Addition</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="Employees-shift"
                        name="employees_shift">
                    <label class="ml-1" for="Employees-shift">Employees When clothing shift</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="time-attendance"
                        name="time_attendance">
                    <label class="ml-1" for="time-attendance">Use Time
                        Attendance</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="close-day-auto" name="close_day_auto">
                    <label class="ml-1" for="close-day-auto">Close Day Automotic</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="close-day-table"
                        name="close_day_table">
                    <label class="ml-1" for="close-day-table">Close Day Aftar End
                        All tables</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="Combo" name="compo">
                    <label class="ml-1" for="Combo">use Combo</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="promotions" name="promotions">
                    <label class="ml-1" for="promotions">Use Promotions</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="malt-pass-security"
                        name="malt_pass_security">
                    <label class="ml-1" for="malt-pass-security">malt password
                        Security</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="over-sub" name="over_sub">
                    <label class="ml-1" for="over-sub">tax Over Only Server Name</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-visa" name="display_visa">
                    <label class="ml-1" for="dis-visa">Display visa</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-ledge" name="display_ledge">
                    <label class="ml-1" for="dis-ledge">Display Ledge</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-officer"
                        name="display_officer">
                    <label class="ml-1" for="dis-officer">Display Officer</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-hospitality"
                        name="dis_hospitality">
                    <label class="ml-1" for="dis-hospitality">Display
                        Hospitality</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-save" name="dis_save">
                    <label class="ml-1" for="dis-save">Display Save</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-save-print"
                        name="dis_save_print">
                    <label class="ml-1" for="dis-save-print">Display Save &
                        Print</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-keyboard" name="dis_keyboard">
                    <label class="ml-1" for="dis-keyboard">Display Keyboard</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="dis-tip-cash" name="dis_tip_cash">
                    <label class="ml-1" for="dis-tip-cash">Display Tip Cash</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="del-data" name="del_data">
                    <label class="ml-1" for="del-data">Delete Data After Close
                        Day</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="print-reports"
                        name="print_reports">
                    <label class="ml-1" for="print-reports">Print Reports After
                        Close</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="print-void-slip"
                        name="print_void_slip">
                    <label class="ml-1" for="print-void-slip">Print Void
                        Slip</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="collect-items-check"
                        name="collect_items_check">
                    <label class="ml-1" for="collect-items-check">Dont Collect Items
                        In Check</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="collect-items-slip"
                        name="collect_items_slip">
                    <label class="ml-1" for="collect-items-slip">Dont Collect Items
                        In Slip</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="items-qty" name="items_qty">
                    <label class="ml-1" for="items-qty">Decrease Items Qty</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="decimal-qty" name="decimal_qty">
                    <label class="ml-1" for="decimal-qty">Disable Decimal
                        Qty</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="delivery-reciving-customer"
                        name="delivery_reciving_customer">
                    <label class="ml-1" for="delivery-reciving-customer"> Delivery Orders By Customer</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="check-balance"
                        name="check_balance">
                    <label class="ml-1" for="check-balance">Check Balance</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="drawer-printer"
                        name="drawer_printer_check">
                    <label class="ml-1" for="drawer-printer">Cash Drawer Printer</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="flash-reports"
                        name="flash_reports">
                    <label class="ml-1" for="flash-reports">Print Flash Reports</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="def-transaction"
                        name="def_transaction">
                    <label class="ml-1" for="def-transaction">Default
                        Transaction</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="expeneses" name="expeneses">
                    <label class="ml-1" for="expeneses">Print Expeneses</label>
                </div>

                <div class="form-group">
                    <input type="checkbox" value="1" id="copy-invoice" name="copy_invoice">
                    <label class="ml-1" for="copy-invoice">Copy Invoice Printer</label>
                </div>

            </div>

            <div class='col-md-6 offset-md-3 mb-5 pb-3'>
                <button id="save_other" type="submit" class="btn btn-block btn-success">Save</button>
            </div>
        </form>
    </div>
</section>
<?php echo $__env->make('includes.control.other', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webpoint\resources\views/control/add_Other.blade.php ENDPATH**/ ?>
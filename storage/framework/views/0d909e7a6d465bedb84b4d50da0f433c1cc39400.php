<?php
    $title = 'Log Report';
?>


<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('includes.menu.sub_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class='check-out px-4 py-5'>
        <?php echo csrf_field(); ?>

        <div class="container">
            <button type="button" class="btn btn-primary filter" data-toggle="modal" data-target="#report-filter">
                Filters
            </button>
            <div id="report-output"></div>
        </div>

    </section>
    <div class="modal report-filter fade" id="report-filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body row">
                <div class="col-md-9">
                    <form>
                        <div class="select-container">
                            <label for="time-input" class="mb-0">From</label>
                            <input type="date" class="form-control select-report" id="from" value="<?php echo date('Y-m-d',strtotime("0 days"));?>"  max="<?php echo date('Y-m-d',strtotime("0 days"));?>" dataformatas="dd/mm/yyyy">
                        </div>
                        <div class="select-container">
                            <label for="time-input" class="mb-0">To</label>
                            <input type="date" class="form-control  select-report" id="to" value="<?php echo date('Y-m-d',strtotime("0 days"));?>"  max="<?php echo date('Y-m-d',strtotime("0 days"));?>" dataformatas="dd/mm/yyyy">
                        </div>
                        <hr>
                        <input type="reset" value="Reset" class="btn btn-primary mt-2">
                    </form>
                </div>
                <div class="col-md-3 d-flex flex-column">
                    <button class="btn-report-in-day btn btn-success my-2" id="logReport">View Report</button>
                </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal report-filter fade bd-example-modal-xl" id="report-log" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            
            <div class="modal-body row">
                <div class="col-md-12" id="repViewLog">

                </div>
            </div>
          </div>
        </div>
    </div>

    <?php echo $__env->make('includes.reports.general_reports', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webpoint\resources\views/Reports/log_report.blade.php ENDPATH**/ ?>
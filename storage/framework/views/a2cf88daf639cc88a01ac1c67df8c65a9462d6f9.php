
<?php
    $title = 'Expenses';
?>


<?php $__env->startSection('content'); ?>
<?php echo $__env->make('includes.menu.sub_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <input type="hidden" id="device_id" value="">
    <section class='moveTo'>
        <div class='container'>
            <div class="alert alert-success"  style="display: none;" id="alert_show" role="alert">
                Successful Move <script>setTimeout(function(){$('#alert_show').hide();}, 2500);</script>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#exampleModal">
                Create Expenses
            </button>
            <div class="row mt-3">
                <table id="myTable" class="table" style="color:white">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Time</th>
                            <th scope="col">Category</th>
                            <th scope="col">Title</th>
                            <th scope="col">Amount</th>
                            <th scope="col">User</th>
                            <th scope="col">Note</th>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Expenses')): ?>
                                <th scope="col">Action</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($row->id); ?></td>
                                <td><?php echo e($row->time); ?></td>
                                <td><?php echo e($row->category->title); ?></td>
                                <td><?php echo e($row->title); ?></td>
                                <td><?php echo e($row->amount); ?></td>
                                <td><?php echo e($row->user->email); ?></td>
                                <td><?php echo e($row->note); ?></td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Expenses')): ?>
                                    <td>
                                        <button rowId ="<?php echo e($row->id); ?>" class="btn btn-danger delExpenses">Delete</button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Create Expenses</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>category</h6>
                                <?php echo csrf_field(); ?>
                                <select  class="main_table custom-select"  name="search_main_table" id="search_main_table">
                                    <option selected disabled>Choose category...</option>
                                    <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($row->id); ?>"><?php echo e($row->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>Title</h6>
                                <input id="title" name="title" type="text" class="form-control use-keyboard-input">
                            </div>
                        </div>
                    </div>
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>Amount</h6>
                                <input id="amount" name="amount" type="number" class="form-control use-keyboard-input">
                            </div>
                        </div>
                    </div>
                    <div class='row mb-4'>
                        <div class="col-md-12 d-flex flex-row mt-3">
                            <div class="form-element w-100">
                                <h6 class='text-center text-white'>Note</h6>
                                <textarea id="note" name="note" class="form-control use-keyboard-input"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="save">Create</button>
                </div>
              </div>
            </div>
          </div>

    </section>
    <?php echo $__env->make('includes.menu.Expenses', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\BackEnd\htdocs\webpoint\resources\views/menu/Expenses.blade.php ENDPATH**/ ?>
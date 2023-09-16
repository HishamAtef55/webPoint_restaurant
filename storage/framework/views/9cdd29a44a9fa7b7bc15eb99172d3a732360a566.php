<?php
    $title = 'Login';
?>

<?php $__env->startSection('content'); ?>
    <style>
        body {
            background-color: rgb(66, 68, 117) !important;
        }
    </style>
<div class="container container-login ibrahim">
    <section class='login'>
        <div class='pass-div'>
            <img src="<?php echo e(asset('global/image/logo.png')); ?>" width="160px" style="margin-bottom: 25px"  alt="Logo" >

            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <select id="email" class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> form-control email-select"  name="email" placeholder="UserName" required autocomplete="off">
                    <?php $__currentLoopData = \App\Models\User::select(['email'])->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->email); ?>"><?php echo e($user->email); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="invalid-feedback" role="alert"> <strong><?php echo e($message); ?></strong> </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <input id="password" type="password" class="d-none <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="off">

                <p class='star-pass'></p>

                <button type="submit" class="btn btn-block login-btn">
                    <?php echo e(__('Login')); ?>

                </button>
            </form>

        </div>
        <div class='key-numbers'>
            <span class='number' data-number='1'>1</span>
            <span class='number' data-number='2'>2</span>
            <span class='number' data-number='3'>3</span>
            <span class='number' data-number='4'>4</span>
            <span class='number' data-number='5'>5</span>
            <span class='number' data-number='6'>6</span>
            <span class='number' data-number='7'>7</span>
            <span class='number' data-number='8'>8</span>
            <span class='number' data-number='9'>9</span>
            <span class='number' data-number='0'>0</span>
            <span class='remove' data-number='c'><i class="fas fa-backspace"></i></span>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\MyWork\Res\ERP\webPoint\resources\views/auth/login.blade.php ENDPATH**/ ?>
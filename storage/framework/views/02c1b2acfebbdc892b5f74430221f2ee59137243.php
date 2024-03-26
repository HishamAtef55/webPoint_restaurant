<?php
    $title = 'Login';
?>

<?php $__env->startSection('content'); ?>
    <style>
        body {
            background-color: rgb(66, 68, 117) !important;
            overflow-y:hidden
        }
    </style>
<div class="container-login">
    <?php if($errors->any()): ?>
    <script>
        Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?php echo e($errors->first()); ?>',
        })
    </script>
    <?php endif; ?>
    <section class="welcome-page open">
        <img src="<?php echo e(asset('global/image/logo.png')); ?>" alt="Logo" >
        <ul>
            <li class="menu-item" data-value="pos">

                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="100%" id="blobSvg" filter="blur(0px)" style="opacity: 1;">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color: rgb(82, 112, 232);"></stop>
                            <stop offset="100%" style="stop-color: rgb(50, 71, 154);"></stop>
                        </linearGradient>
                    </defs>
                    <path id="blob" fill="url(#gradient)" style="opacity: 1;">
                        <animate attributeName="d" dur="10000ms" repeatCount="indefinite" values="M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z;M408.24461,332.63257Q415.26513,415.26513,332.63257,434.71568Q250,454.16622,179.33614,422.74697Q108.67228,391.32772,65.87585,320.66386Q23.07942,250,63.27221,176.73251Q103.46501,103.46501,176.73251,63.02288Q250,22.58075,311.86507,74.4253Q373.73015,126.26985,387.47712,188.13493Q401.22409,250,408.24461,332.63257Z;M423.42552,332.41134Q414.82268,414.82268,332.41134,424.30554Q250,433.78841,170.96572,420.92848Q91.93144,408.06856,46.07152,329.03428Q0.21159,250,66.88003,191.77423Q133.54846,133.54846,191.77423,102.82861Q250,72.10876,305.00592,106.04846Q360.01185,139.98815,396.0201,194.99408Q432.02836,250,423.42552,332.41134Z;M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z;M449.05134,329.9003Q409.80059,409.80059,329.9003,451.15995Q250,492.5193,162.89881,458.36084Q75.79762,424.20238,65.04837,337.10119Q54.29911,250,85.74629,183.59673Q117.19347,117.19347,183.59673,88.1905Q250,59.18753,328.8549,75.73886Q407.7098,92.2902,448.00594,171.1451Q488.30208,250,449.05134,329.9003Z;M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z"></animate>
                    </path>
                </svg>
                <span>pos</span>
            </li>
            <li class="menu-item" data-value="stock">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="100%" id="blobSvg" filter="blur(0px)" style="opacity: 1;">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color: rgb(82, 112, 232);"></stop>
                            <stop offset="100%" style="stop-color: rgb(50, 71, 154);"></stop>
                        </linearGradient>
                    </defs>
                    <path id="blob" fill="url(#gradient)" style="opacity: 1;">
                        <animate attributeName="d" dur="10000ms" repeatCount="indefinite" values="M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z;M408.24461,332.63257Q415.26513,415.26513,332.63257,434.71568Q250,454.16622,179.33614,422.74697Q108.67228,391.32772,65.87585,320.66386Q23.07942,250,63.27221,176.73251Q103.46501,103.46501,176.73251,63.02288Q250,22.58075,311.86507,74.4253Q373.73015,126.26985,387.47712,188.13493Q401.22409,250,408.24461,332.63257Z;M423.42552,332.41134Q414.82268,414.82268,332.41134,424.30554Q250,433.78841,170.96572,420.92848Q91.93144,408.06856,46.07152,329.03428Q0.21159,250,66.88003,191.77423Q133.54846,133.54846,191.77423,102.82861Q250,72.10876,305.00592,106.04846Q360.01185,139.98815,396.0201,194.99408Q432.02836,250,423.42552,332.41134Z;M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z;M449.05134,329.9003Q409.80059,409.80059,329.9003,451.15995Q250,492.5193,162.89881,458.36084Q75.79762,424.20238,65.04837,337.10119Q54.29911,250,85.74629,183.59673Q117.19347,117.19347,183.59673,88.1905Q250,59.18753,328.8549,75.73886Q407.7098,92.2902,448.00594,171.1451Q488.30208,250,449.05134,329.9003Z;M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z"></animate>
                    </path>
                </svg>
                <span>Stock</span>
            </li>
            <li class="menu-item" data-value="hr">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="100%" id="blobSvg" filter="blur(0px)" style="opacity: 1;">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color: rgb(82, 112, 232);"></stop>
                            <stop offset="100%" style="stop-color: rgb(50, 71, 154);"></stop>
                        </linearGradient>
                    </defs>
                    <path id="blob" fill="url(#gradient)" style="opacity: 1;">
                        <animate attributeName="d" dur="10000ms" repeatCount="indefinite" values="M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z;M408.24461,332.63257Q415.26513,415.26513,332.63257,434.71568Q250,454.16622,179.33614,422.74697Q108.67228,391.32772,65.87585,320.66386Q23.07942,250,63.27221,176.73251Q103.46501,103.46501,176.73251,63.02288Q250,22.58075,311.86507,74.4253Q373.73015,126.26985,387.47712,188.13493Q401.22409,250,408.24461,332.63257Z;M423.42552,332.41134Q414.82268,414.82268,332.41134,424.30554Q250,433.78841,170.96572,420.92848Q91.93144,408.06856,46.07152,329.03428Q0.21159,250,66.88003,191.77423Q133.54846,133.54846,191.77423,102.82861Q250,72.10876,305.00592,106.04846Q360.01185,139.98815,396.0201,194.99408Q432.02836,250,423.42552,332.41134Z;M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z;M449.05134,329.9003Q409.80059,409.80059,329.9003,451.15995Q250,492.5193,162.89881,458.36084Q75.79762,424.20238,65.04837,337.10119Q54.29911,250,85.74629,183.59673Q117.19347,117.19347,183.59673,88.1905Q250,59.18753,328.8549,75.73886Q407.7098,92.2902,448.00594,171.1451Q488.30208,250,449.05134,329.9003Z;M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z"></animate>
                    </path>
                </svg>
                <span>HR</span>
            </li>
            <li class="menu-item" data-value="accounting">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="100%" id="blobSvg" filter="blur(0px)" style="opacity: 1;">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color: rgb(82, 112, 232);"></stop>
                            <stop offset="100%" style="stop-color: rgb(50, 71, 154);"></stop>
                        </linearGradient>
                    </defs>
                    <path id="blob" fill="url(#gradient)" style="opacity: 1;">
                        <animate attributeName="d" dur="10000ms" repeatCount="indefinite" values="M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z;M408.24461,332.63257Q415.26513,415.26513,332.63257,434.71568Q250,454.16622,179.33614,422.74697Q108.67228,391.32772,65.87585,320.66386Q23.07942,250,63.27221,176.73251Q103.46501,103.46501,176.73251,63.02288Q250,22.58075,311.86507,74.4253Q373.73015,126.26985,387.47712,188.13493Q401.22409,250,408.24461,332.63257Z;M423.42552,332.41134Q414.82268,414.82268,332.41134,424.30554Q250,433.78841,170.96572,420.92848Q91.93144,408.06856,46.07152,329.03428Q0.21159,250,66.88003,191.77423Q133.54846,133.54846,191.77423,102.82861Q250,72.10876,305.00592,106.04846Q360.01185,139.98815,396.0201,194.99408Q432.02836,250,423.42552,332.41134Z;M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z;M449.05134,329.9003Q409.80059,409.80059,329.9003,451.15995Q250,492.5193,162.89881,458.36084Q75.79762,424.20238,65.04837,337.10119Q54.29911,250,85.74629,183.59673Q117.19347,117.19347,183.59673,88.1905Q250,59.18753,328.8549,75.73886Q407.7098,92.2902,448.00594,171.1451Q488.30208,250,449.05134,329.9003Z;M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z"></animate>
                    </path>
                </svg>
                <span style="font-size: 14px;">Accounting</span>
            </li>
            <li class="menu-item"  data-value="dashboard">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" width="100%" id="blobSvg" filter="blur(0px)" style="opacity: 1;">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color: rgb(82, 112, 232);"></stop>
                            <stop offset="100%" style="stop-color: rgb(50, 71, 154);"></stop>
                        </linearGradient>
                    </defs>
                    <path id="blob" fill="url(#gradient)" style="opacity: 1;">
                        <animate attributeName="d" dur="10000ms" repeatCount="indefinite" values="M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z;M408.24461,332.63257Q415.26513,415.26513,332.63257,434.71568Q250,454.16622,179.33614,422.74697Q108.67228,391.32772,65.87585,320.66386Q23.07942,250,63.27221,176.73251Q103.46501,103.46501,176.73251,63.02288Q250,22.58075,311.86507,74.4253Q373.73015,126.26985,387.47712,188.13493Q401.22409,250,408.24461,332.63257Z;M423.42552,332.41134Q414.82268,414.82268,332.41134,424.30554Q250,433.78841,170.96572,420.92848Q91.93144,408.06856,46.07152,329.03428Q0.21159,250,66.88003,191.77423Q133.54846,133.54846,191.77423,102.82861Q250,72.10876,305.00592,106.04846Q360.01185,139.98815,396.0201,194.99408Q432.02836,250,423.42552,332.41134Z;M409.06419,322.5266Q395.0532,395.0532,322.5266,445.11739Q250,495.18159,163.51944,459.07135Q77.03888,422.96112,82.39949,336.48056Q87.7601,250,115.64271,196.76266Q143.52532,143.52532,196.76266,76.83657Q250,10.14783,323.24578,56.82813Q396.49156,103.50844,409.78338,176.75422Q423.07519,250,409.06419,322.5266Z;M449.05134,329.9003Q409.80059,409.80059,329.9003,451.15995Q250,492.5193,162.89881,458.36084Q75.79762,424.20238,65.04837,337.10119Q54.29911,250,85.74629,183.59673Q117.19347,117.19347,183.59673,88.1905Q250,59.18753,328.8549,75.73886Q407.7098,92.2902,448.00594,171.1451Q488.30208,250,449.05134,329.9003Z;M395.5,320Q390,390,320,400Q250,410,172,408Q94,406,59,328Q24,250,70.5,183.5Q117,117,183.5,108Q250,99,335,89.5Q420,80,410.5,165Q401,250,395.5,320Z"></animate>
                    </path>
                </svg>
                <span style="font-size: 14px;">Dashboard</span>
            </li>
        </ul>

    </section>

    <section class='login close'>
        <div class='pass-div'>
            <div class="back-btn text-white">
                <i class="fas fa-long-arrow-alt-left"></i>
                <span>Back</span>
            </div>
            <h2 class="login-section text-white"></h2>

            <form method="POST" action="<?php echo e(route('check_admin')); ?>">
                <input type="hidden" id="login_method" name="type"/>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Xampp\htdocs\webpoint\resources\views/auth/login.blade.php ENDPATH**/ ?>
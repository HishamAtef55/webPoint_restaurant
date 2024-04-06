<nav class="navbar sticky-top shadow-sm">
    <div class="container d-flex gap-4">
        <div class="navbar-brand">
            <a href="<?php echo e(route('costControl')); ?>" class="text-white">
                Web <span>Point</span>
            </a>
        </div>

        <ul class="navbar-nav ms-auto flex-row gap-3">
            <li class="nav-item" data-target="menu">
                <span class="nav-link">القائمة</span>
            </li>
            <li class="nav-item" data-target="reports">
                <span class="nav-link">تقارير</span>
            </li>
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav me-auto">
            <!-- Authentication Links -->
            <?php if(auth()->guard()->guest()): ?>
            <?php if(Route::has('login')): ?>
            <li class="nav-item">
                <a class="nav-link  text-white" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
            </li>
            <?php endif; ?>

            <?php if(Route::has('register')): ?>
            <li class="nav-item">
                <a class="nav-link  text-white" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
            </li>
            <?php endif; ?>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <?php echo e(__('Logout')); ?>

                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>
            </li>
            <?php endif; ?>
        </ul>

        <div class="sub-menu menu">
            <div class="container">
                <ul class="list-unstyled p-3">
                    <li class="<?php if(Route::current()->getName() == 'view.stores'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('view.stores')); ?>"> المخازن </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'view.section'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('view.section')); ?>"> الاقسام </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'view.suppliers'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('view.suppliers')); ?>"> الموردين </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'view.main_groups'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('view.main_groups')); ?>"> المجموعات الرئيسية </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'view.groups'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('view.groups')); ?>"> المجموعات الفرعية </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'view.material'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(Route('view.material')); ?>"> الخامات </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'view_components_items'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(Route('view_components_items')); ?>"> مكونات الاصناف </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'componentDetailsItem'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('componentDetailsItem')); ?>"> مكونات تفاصيل الاصناف </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'materialRecipe'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('materialRecipe')); ?>"> مكونات الخامات </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'purchases'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('purchases')); ?>"> المشتريات </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'exchange'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('exchange')); ?>"> إذن صرف </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'transfers'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('transfers')); ?>"> إذن تحويل </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'halk'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('halk')); ?>"> إذن هالك </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'halkItem'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('halkItem')); ?>"> إذن هالك صنف </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'back_to_suppliers'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('back_to_suppliers')); ?>"> مرتجع الي مورد </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'back_to_stores'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('back_to_stores')); ?>"> مرتجع الي مخزن </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'materialOperations'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('materialOperations')); ?>"> التشغيل </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'materialManufacturing'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('materialManufacturing')); ?>"> التصنيع </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'inDirectCost.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('inDirectCost.index')); ?>"> المصاريف الغير مباشرة </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'stock.orders.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('stock.orders.index')); ?>"> الطلبيات </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sub-menu reports">
            <div class="container">
                <ul class="list-unstyled p-3">
                    <li class="<?php if(Route::current()->getName() == 'reports.items-pricing.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.items-pricing.index')); ?>"> تسعير اصناف </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'reports.store_balance'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.store_balance')); ?>"> رصيد المخزن </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'inventoryDaily.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('inventoryDaily.index')); ?>"> الجرد الشهري </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'inventory.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('inventory.index')); ?>"> الجرد اليومي </a>
                    </li>
                    <li class="<?php if(Route::current()->getName() == 'reports.exchange.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.exchange.index')); ?>"> تقرير الصرف </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.transfer.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.transfer.index')); ?>"> تقرير التحويل </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.purchases.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.purchases.index')); ?>"> تقرير المشتريات </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.backStores.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.backStores.index')); ?>"> تقرير مرتجع مخازن </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.backSuppliers.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.backSuppliers.index')); ?>"> تقرير مرتج موردين </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.cardItem.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.cardItem.index')); ?>"> كارت صنف </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.halkItem.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.halkItem.index')); ?>"> تقرير هالك منتجات </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.halk.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.halk.index')); ?>"> تقرير الهالك </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.suppliers.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.suppliers.index')); ?>"> كشف حساب مورد </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.manufacturing.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.manufacturing.index')); ?>"> تقرير التصنيع </a>
                    </li>

                    <li class="<?php if(Route::current()->getName() == 'reports.operations.index'): ?> active <?php endif; ?>">
                        <a class="text-muted py-1 d-block" href="<?php echo e(route('reports.operations.index')); ?>"> تقرير التشغيل </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav><?php /**PATH C:\xampp\htdocs\webpoint\resources\views/layouts/stock/header.blade.php ENDPATH**/ ?>
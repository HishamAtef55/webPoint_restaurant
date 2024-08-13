<nav class="navbar sticky-top shadow-sm">
    <div class="container d-flex gap-4">
        <div class="navbar-brand">
            <a href="{{ route('costControl') }}" class="text-white">
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
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                @endif

                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link  text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endguest
        </ul>

        <div class="sub-menu menu">
            <div class="container">
                <ul class="list-unstyled p-3">
                    <li class="@if (Route::current()->getName() == 'stock.stores.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.stores.index') }}"> المخازن </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.sections.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.sections.index') }}"> الاقسام </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.suppliers.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.suppliers.index') }}"> الموردين </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.main.groups.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.main.groups.index') }}"> المجموعات
                            الرئيسية
                        </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.sub.groups.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.sub.groups.index') }}"> المجموعات
                            الفرعية </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.materials.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ Route('stock.materials.index') }}"> الخامات </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'view_components_items') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ Route('view_components_items') }}"> مكونات الاصناف
                        </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'componentDetailsItem') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('componentDetailsItem') }}"> مكونات تفاصيل
                            الاصناف </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.material.recipe.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.material.recipe.index') }}"> مكونات
                            الخامات </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'stock.purchases.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.purchases.index') }}"> المشتريات </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'exchange') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('exchange') }}"> إذن صرف </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'transfers') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('transfers') }}"> إذن تحويل </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'halk') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('halk') }}"> إذن هالك </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'halkItem') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('halkItem') }}"> إذن هالك صنف </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'back_to_suppliers') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('back_to_suppliers') }}"> مرتجع الي مورد </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'back_to_stores') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('back_to_stores') }}"> مرتجع الي مخزن </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'materialOperations') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('materialOperations') }}"> التشغيل </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'materialManufacturing') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('materialManufacturing') }}"> التصنيع </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'inDirectCost.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('inDirectCost.index') }}"> المصاريف الغير
                            مباشرة </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'stock.orders.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('stock.orders.index') }}"> الطلبيات </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sub-menu reports">
            <div class="container">
                <ul class="list-unstyled p-3">
                    <li class="@if (Route::current()->getName() == 'reports.items-pricing.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.items-pricing.index') }}"> تسعير
                            اصناف </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'reports.store_balance') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.store_balance') }}"> رصيد المخزن
                        </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'inventoryDaily.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('inventoryDaily.index') }}"> الجرد الشهري
                        </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'inventory.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('inventory.index') }}"> الجرد اليومي </a>
                    </li>
                    <li class="@if (Route::current()->getName() == 'reports.exchange.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.exchange.index') }}"> تقرير الصرف
                        </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.transfer.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.transfer.index') }}"> تقرير
                            التحويل </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.purchases.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.purchases.index') }}"> تقرير
                            المشتريات </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.backStores.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.backStores.index') }}"> تقرير
                            مرتجع مخازن </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.backSuppliers.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.backSuppliers.index') }}"> تقرير
                            مرتج موردين </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.cardItem.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.cardItem.index') }}"> كارت صنف
                        </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.halkItem.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.halkItem.index') }}"> تقرير هالك
                            منتجات </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.halk.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.halk.index') }}"> تقرير الهالك
                        </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.suppliers.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.suppliers.index') }}"> كشف حساب
                            مورد </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.manufacturing.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.manufacturing.index') }}"> تقرير
                            التصنيع </a>
                    </li>

                    <li class="@if (Route::current()->getName() == 'reports.operations.index') active @endif">
                        <a class="text-muted py-1 d-block" href="{{ route('reports.operations.index') }}"> تقرير
                            التشغيل </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

    <!-- Start Sidebar -->
    <aside>
      <span id="close_menu" class="close rounded-3  d-block d-sm-none"> <i class="fa-solid fa-xmark"></i> </span>
      <ul>
        <li class="@if(Route::current()->getName() == 'view.stores') active @endif">
          <a href="{{route('view.stores')}}">
              <i class="fa-solid fa-store fa-xl"></i>
              <span>المخازن</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'view.section') active @endif">
          <a href="{{route('view.section')}}">
            <i class="fa-solid fa-lightbulb fa-xl"></i>
            <span>الاقسام</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'view.suppliers') active @endif">
          <a href="{{route('view.suppliers')}}">
              <i class="fa-solid fa-users fa-xl"></i>
            <span>الموردين</span>
          </a>
        </li>
{{--        <li>--}}
{{--          <a href="product-services.html">--}}
{{--              <i class="fa-solid fa-weight-scale fa-xl"></i>--}}
{{--            <span>الوحدات</span>--}}
{{--          </a>--}}
{{--        </li>--}}

          <li class="@if(Route::current()->getName() == 'view.main_groups') active @endif">
              <a href="{{route('view.main_groups')}}">
                  <i class="fa-solid fa-folder-open fa-xl"></i>
                  <span>المجموعات الرئيسية</span>
              </a>
          </li>
        <li class="@if(Route::current()->getName() == 'view.groups') active @endif">
          <a href="{{route('view.groups')}}">
            <i class="fa-solid fa-folder-open fa-xl"></i>
            <span>المجموعات الفرعية</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'view.material') active @endif">
          <a href="{{Route('view.material')}}">
            <i class="fa-solid fa-file-lines fa-xl"></i>
            <span>الخامات</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'view_components_items') active @endif">
          <a href="{{Route('view_components_items')}}">
            <i class="fa-solid fa-address-book fa-xl"></i>
            <span>مكونات الاصناف</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'componentDetailsItem') active @endif">
          <a href="{{route('componentDetailsItem')}}">
            <i class="fa-solid fa-briefcase fa-xl"></i>
            <span>مكونات تفاصيل الاصناف</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'materialRecipe') active @endif">
          <a href="{{route('materialRecipe')}}">
            <i class="fa-solid fa-file-signature fa-xl"></i>
            <span>مكونات الخامات</span>
          </a>
        </li>
        <li class="@if(Route::current()->getName() == 'purchases') active @endif">
          <a href="{{route('purchases')}}">
            <i class="fa-solid fa-ticket fa-xl"></i>
            <span>المشتريات</span>
          </a>
        </li>
          <li class="@if(Route::current()->getName() == 'exchange') active @endif">
          <a href="{{route('exchange')}}">
            <i class="fa-solid fa-users fa-xl"></i>
            <span>إذن صرف</span>
          </a>
        </li>
          <li class="@if(Route::current()->getName() == 'transfers') active @endif">
              <a href="{{route('transfers')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>إذن تحويل</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'halk') active @endif">
              <a href="{{route('halk')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>إذن هالك</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'halkItem') active @endif">
              <a href="{{route('halkItem')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>إذن هالك صنف</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'back_to_suppliers') active @endif">
              <a href="{{route('back_to_suppliers')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>مرتجع الي مورد</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'back_to_stores') active @endif">
              <a href="{{route('back_to_stores')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>مرتجع الي مخزن</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'materialOperations') active @endif">
              <a href="{{route('materialOperations')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>التشغيل</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'materialManufacturing') active @endif">
              <a href="{{route('materialManufacturing')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>التصنيع</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'reports.store_balance') active @endif">
              <a href="{{route('reports.store_balance')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>رصيد المخزن</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'inventoryDaily.index') active @endif">
              <a href="{{route('inventoryDaily.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>الجرد الشهري</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'inventory.index') active @endif">
              <a href="{{route('inventory.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>الجرد اليومي</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'inDirectCost.index') active @endif">
              <a href="{{route('inDirectCost.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>المصاريف الغير مباشرة</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'reports.items-pricing.index') active @endif">
              <a href="{{route('reports.items-pricing.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>تسعير اصناف</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'stock.orders.index') active @endif">
              <a href="{{route('stock.orders.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>الطلبيات</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'reports.exchange.index') active @endif">
              <a href="{{route('reports.exchange.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>تقرير الصرف</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'reports.transfer.index') active @endif">
              <a href="{{route('reports.transfer.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>تقرير التحويل</span>
              </a>
          </li>

          <li class="@if(Route::current()->getName() == 'reports.purchases.index') active @endif">
              <a href="{{route('reports.purchases.index')}}">
                  <i class="fa-solid fa-users fa-xl"></i>
                  <span>تقرير المشتريات</span>
              </a>
          </li>
<!-- 
          <li class="@if(Route::current()->getName() == 'users.index') active @endif">
              <a href="{{route('users.index')}}">
                  <i class="fa-solid fa-user-lock fa-xl"></i>
                  <span>صلاحيات المستخدمين</span>
              </a>
          </li>
          <li class="@if(Route::current()->getName() == 'roles.index') active @endif">
              <a href="{{route('roles.index')}}">
                  <i class="fa-solid fa-list-check fa-xl"></i>
                  <span>الصلاحيات الرأيسية</span>
              </a>
          </li> -->
      </ul>
    </aside>
    <!-- End Sidebar -->

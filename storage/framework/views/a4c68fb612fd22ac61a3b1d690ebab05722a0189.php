        <!-- Start Asidebar -->
        <nav id='sidebar'>
            <ul>
                <li>
                    <a href="#">Point Of Sale</a>
                    <ul>
                        <li> <a href="<?php echo e(Route('view.information')); ?>">  Information  </a> </li>
                        <li> <a href="<?php echo e(Route('View.device')); ?>">  Device  </a> </li>
                        <li> <a href="<?php echo e(Route('View.update.branch')); ?>">  Branch   </a> </li>
                        <li> <a href="<?php echo e(Route('view.update.menu')); ?>">    Menu     </a> </li>
                        <li> <a href="<?php echo e(Route('View.update.group')); ?>">   Group    </a> </li>
                        <li> <a href="<?php echo e(Route('View.update.subgroup')); ?>">Sub Group</a> </li>
                        <li> <a href="<?php echo e(Route('View.update.item')); ?>">    Item     </a> </li>
                        <li> <a href="<?php echo e(Route('View.update.user')); ?>">    User     </a> </li>
                        <li> <a href="<?php echo e(Route('View.AddDetails')); ?>">    Item Details     </a> </li>
                        <li> <a href="<?php echo e(Route('View.ItemsDetails')); ?>">    Extra     </a> </li>
                        <li> <a href="<?php echo e(Route('View.Add.shift')); ?>">    Shift     </a> </li>

                        <li> <a href="<?php echo e(Route('view.update.discount')); ?>">    Discount     </a> </li>
                        <li> <a href="<?php echo e(Route('View.Add.Tables')); ?>">    Tables     </a> </li>
                        <li> <a href="<?php echo e(Route('view.del')); ?>">    Delivery     </a> </li>
                        <li> <a href="<?php echo e(Route('to.go')); ?>">    To-Go     </a> </li>
                        <li> <a href="<?php echo e(Route('view.ser.table')); ?>">    Services-Tables    </a> </li>
                        <li> <a href="<?php echo e(Route('view.car.cervices')); ?>">    Car-Services    </a> </li>
                        <li> <a href="<?php echo e(Route('view.mincharge')); ?>">    Min-Charge    </a> </li>
                        <li> <a href="<?php echo e(Route('View.Add.Location')); ?>">    Locations    </a> </li>
                        <li> <a href="<?php echo e(Route('view.printers')); ?>">    Printers    </a> </li>
                        <li> <a href="<?php echo e(url('/' . ($page = 'users'))); ?>">    Users Permission   </a> </li>
                        <li> <a href="<?php echo e(url('/' . ($page = 'roles'))); ?>">    Permission    </a> </li>
                        <li> <a href="<?php echo e(Route('view.other')); ?>">    Others    </a> </li>
                        <li> <a href="<?php echo e(Route('View.getDays')); ?>">    Open Day    </a> </li>
                        <li> <a href="<?php echo e(Route('reset_data')); ?>">    Reset    </a> </li>

                        <li data-nav-custom-content></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Accounting</a>
                    <!-- <ul>
                        <li><a href="#">National Geographic</a></li>
                        <li><a href="#">Scientific American</a></li>
                        <li><a href="#">The Spectator</a></li>
                        <li><a href="#">The Rambler</a></li>
                        <li><a href="#">Physics World</a></li>
                        <li><a href="#">The New Scientist</a></li>
                        <li data-nav-custom-content></li>
                    </ul> -->
                </li>
                <li>
                    <a href="#">HR</a>
                </li>
                <li> <a href="<?php echo e(Route('view.table')); ?>">    Dien IN    </a> </li>
            </li>
                <li data-nav-custom-content></li>
            </ul>
        </nav>
        <!-- End Asidebar -->

        <!-- Start Asidebar Button -->
        <button class="sidebar-btn">
            <i class="fas fa-chevron-right"></i>
        </button>
        <!-- End Asidebar Button -->
<?php /**PATH E:\MyWork\Res\webPoint\resources\views/layouts/nav_left.blade.php ENDPATH**/ ?>

        <!-- Start Asidebar -->
        <nav id="main-nav">
            <ul>
                <li>
                    <a href="#">Point Of Sale</a>
                    <ul>
                        <li> <a href="{{Route('View.device')}}">  Device  </a> </li>
                        <li> <a href="{{Route('View.update.branch')}}">  Branch   </a> </li>
                        <li> <a href="{{Route('view.update.menu')}}">    Menu     </a> </li>
                        <li> <a href="{{Route('View.update.group')}}">   Group    </a> </li>
                        <li> <a href="{{Route('View.update.subgroup')}}">Sub Group</a> </li>
                        <li> <a href="{{Route('View.update.item')}}">    Item     </a> </li>
                        <li> <a href="{{Route('View.update.user')}}">    User     </a> </li>
                        <li> <a href="{{Route('View.AddDetails')}}">    Details     </a> </li>
                        <li> <a href="{{Route('View.ItemsDetails')}}">    Extra     </a> </li>

                        <li> <a href="{{Route('view.update.discount')}}">    Discount     </a> </li>
                        <li> <a href="{{Route('View.Add.Tables')}}">    Tables     </a> </li>
                        <li> <a href="{{Route('view.del')}}">    Delivery     </a> </li>
                        <li> <a href="{{Route('to.go')}}">    To-Go     </a> </li>
                        <li> <a href="{{Route('view.ser.table')}}">    Services-Tables    </a> </li>
                        <li> <a href="{{Route('view.car.cervices')}}">    Car-Services    </a> </li>
                        <li> <a href="{{Route('view.mincharge')}}">    Min-Charge    </a> </li>
                        <li> <a href="{{Route('view.other')}}">    Others    </a> </li>
                        <li data-nav-custom-content></li>
                    </ul>
                </li>
                <li>
                    <a>Stok and Coast</a>
                    <ul>
                        <li>
                            <a href="#">Mobile Phones</a>
                            <ul>
                                <li><a href="#">Super Smart Phone</a></li>
                                <li><a href="#">Thin Magic Mobile</a></li>
                                <li><a href="#">Performance Crusher</a></li>
                                <li><a href="#">Futuristic Experience</a></li>
                                <li data-nav-custom-content></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Televisions</a>
                            <ul>
                                <li><a href="#">Flat Superscreen</a></li>
                                <li><a href="#">Gigantic LED</a></li>
                                <li><a href="#">Power Eater</a></li>
                                <li><a href="#">Classic Comfort</a></li>
                                <li data-nav-custom-content></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Cameras</a>
                            <ul>
                                <li><a href="#">Smart Shot</a></li>
                                <li><a href="#">Power Shooter</a></li>
                                <li><a href="#">Easy Photo Maker</a></li>
                                <li><a href="#">Super Pixel</a></li>
                                <li data-nav-custom-content></li>
                            </ul>
                        </li>
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
                    <!-- <ul>
                        <li>
                            <a href="#">Clothes</a>
                            <ul>
                                <li>
                                    <a href="#">Women's Clothing</a>
                                    <ul>
                                        <li><a href="#">Tops</a></li>
                                        <li><a href="#">Dresses</a></li>
                                        <li><a href="#">Trousers</a></li>
                                        <li><a href="#">Shoes</a></li>
                                        <li><a href="#">Sale</a></li>
                                        <li data-nav-custom-content></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">Men's Clothing</a>
                                    <ul>
                                        <li><a href="#">Shirts</a></li>
                                        <li><a href="#">Trousers</a></li>
                                        <li><a href="#">Shoes</a></li>
                                        <li><a href="#">Sale</a></li>
                                        <li data-nav-custom-content></li>
                                    </ul>
                                </li>
                                <li data-nav-custom-content></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Jewelry</a>
                        </li>
                        <li>
                            <a href="#">Music</a>
                        </li>
                        <li>
                            <a href="#">Grocery</a>
                        </li>
                        <li data-nav-custom-content></li>
                    </ul> -->
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

        <!-- Start NavBar -->
        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ Route('View.General') }}">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Start NavBar -->

        <!-- Start Section -->
        <main id='Viewer'>


    <!-- <main class="col">


        <div class='position-fixed rounded-circle darkbtn text-center'>
            <img src='https://image.flaticon.com/icons/svg/3127/3127309.svg' class='img-fluid' />
        </div>


        <nav class="navbar navbar-expand-md navbar-dark bg-primary">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>



            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{Route('view.table')}}">Home<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                </ul>
            </div>
        </nav>


        <button href="#" data-toggle="sidebar-colapse" class="sidebar-btn opend">
            <span> <i class='fa fa-angle-right fa-lg'></i> </span>
        </button> -->

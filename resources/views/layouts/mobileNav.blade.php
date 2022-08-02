<header class="header-mobile d-block d-lg-none" id="mobileNavBar">
            <div class="header-mobile__bar" id="mobile-btn">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                    <div class="account-item dropright">
                        <button class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" arial-expanded="false">
                            <div class="image">
                                <img src="{{asset('assets/img/user.png')}}" alt="John Doe" />
                            </div>
                            <div class="content">
                                <a class="js-acc-btn" style="color:black;" href="#">{{ \Auth::user()->name  }}</a>
                            </div>
                        </button>
                        <div class="dropdown-menu js-dropdown">
                            <button class="info clearfix">
                                <div class="image">
                                    <a href="#">
                                        <img src="{{asset('assets/img/user.png')}}" alt="{{ \Auth::user()->name  }}" />
                                    </a>
                                </div>
                                <div class="content">
                                    <h5 class="name">
                                        <a href="#">{{ \Auth::user()->name  }}</a>
                                    </h5>
                                    <span class="email"><p>{{ \Auth::user()->email}}</p></span>
                                </div>
                            </button>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="#">
                                        <i class="zmdi zmdi-account"></i>Account</a>
                                </div>
                            </div>
                            <div class="account-dropdown__footer">
                            <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                                </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            </div>
                        </div>
                    </div>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            
            <nav class="navbar-mobile" id="mobile-sidebar">
                        
                        <div class="container-fluid">
                        <!-- <div class="navbar-nav"></div> -->
                            <ul class="navbar-mobile__list list-unstyled" id="mobile-nav" style="margin-left:5px;">
                            <style>
                                #mobile-nav li ul{
                                    margin:15px;
                                    display:none;
                                }
                                #mobile-nav li ul{
                                    margin:15px;
                                }
                            </style>
                                @if(\Auth::user()->role=='Superadministrator')
                                <li class="active has-sub">
                                    <a class="js-arrow nav-item nav-link"  href="/home">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                                    <!-- <ul class="list-unstyled navbar__sub-list js-sub-list">
                                                <li>
                                                    <a href="index.html">Dashboard 1</a>
                                                </li>
                                                <li>
                                                    <a href="index2.html">Dashboard 2</a>
                                                </li>
                                                <li>
                                                    <a href="index3.html">Dashboard 3</a>
                                                </li>
                                                <li>
                                                    <a href="index4.html">Dashboard 4</a>
                                                </li>
                                            </ul> -->
                                </li>
                                <li class="has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-cog"></i>Setting</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list" style="margin-left:15px;">
                                        <li class=""><a href="{{ url('/user') }}"><i class="fa fa-users"></i>
                                                <span>Users</span></a></li>
                                    </ul>
                                </li>
                                <li class=" has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-money"></i>Payroll</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <li class="active">
                                            <a href="{{ url('/department') }}"><i class="fa fa-list"></i>Department</a>
                                        </li>
                                        <li class="active">
                                            <a href="{{ url('/position') }}"><i class="fa fa-list"></i>Position</a>
                                        </li>
                                        <li class="active">
                                            <a href="{{ url('/employee') }}"><i class="fa fa-users"></i>Employees</a>
                                        </li>
                                        <li class="active">
                                            <a href="{{ url('/cash_advance') }}"><i class="fa fa-money"></i>Cash in Advance</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="/categories" class="nav-item nav-link" >
                                        <i class="fa fa-list"></i>Categories</a>
                                </li>
                                <li class="active has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-bank"></i>Production</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <li class="active">
                                            <a href="/measurements">
                                                <i class="fas fa-table"></i>Measurements</a>
                                        </li>
                                        <li class="active">

                                            <a href="/materials">
                                                <i class="fas fa-history"></i>Material</a>
                                        </li>
                                        <li class="active">

                                            <a href="/materialCategories">
                                                <i class="fas fa-history"></i>Material Categories</a>
                                        </li>
                                        <li class="active">

                                            <a href="/intoStore">
                                                <i class="fas fa-history"></i>Store</a>
                                        </li>
                                        <li class="active">

                                            <a href="/productionSessions">
                                                <i class="fas fa-history"></i>Production Sessions</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a class="nav-item nav-link" href="/get_audit">
                                        <i class="fas fa-history"></i>Log activity</a>
                                </li>
                                </li>
                                <li class="active has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-list"></i>Manage Stock</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <li class="active">
                                            <a href="{{ url('/products') }}"><i class="fa fa-list"></i>Stock</a>
                                        </li>
                                        <li class="active">
                                            <a href="/productsIn">
                                                <i class="fa fa-plus"></i>Product In</a>
                                        </li>
                                        <li class="active">
                                            <a href="{{ url('/demage_products') }}"><i class="fa fa-minus"></i>Demage Products</a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="active">
                                    <a href="/productsOut" class="nav-item nav-link" >
                                        <i class="fa fa-minus"></i>Product Out</a>
                                </li>

                                <li class="active"><a class="nav-item nav-link"  href="{{ url('/suppliers') }}"><i class="fa fa-truck"></i>
                                        <span>Suppliers</span></a></li>

                                <li class="active"><a href="{{ url('/task') }}"><i class="fa fa-list"></i> <span>Tasks</span></a></li>
                                <li class="active"><a href="{{ url('/payment_history') }}"><i class="fa fa-history"></i> <span>Sales
                                            Payment hist</span></a></li>
                                <li class="active">
                                    <a href="/Expensive">
                                        <i class="fas fa-table"></i>Expenses</a>
                                </li>
                                <li class="active has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-briefcase"></i>Accounting</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <li class="active">
                                            <a href="/Account">
                                                <i class="fas fa-table"></i>Account Chart</a>
                                        </li>
                                        <!-- <li class="active">
                                                <a href="/account_group">
                                                <i class="fas fa-table"></i>Account Group</a>
                                            </li> -->



                                        <li class="active">

                                            <a href="/cash_flow" style="color:#F23810">
                                                <i class="fas fa-file" style="color:red"></i>Cash Flow Report</a>
                                        </li>

                                        <li class="active">

                                            <a href="/profit_loss" style="color:#F23810 ">
                                                <i class="fas fa-file" style="color:red"></i>Income and Expenditure</a>
                                        </li>
                                    </ul>
                                </li>


                                <li class="active has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-bank"></i>Banking</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <li class="active">
                                            <a href="/transfer">
                                                <i class="fas fa-table"></i>Transfer Fund</a>
                                        </li>
                                        <li class="active">

                                            <a href="/deposite">
                                                <i class="fas fa-history"></i>Deposite Fund</a>
                                        </li>
                                        <li class="active">
                                            <a href="/get_audit">
                                                <i class="fas fa-history"></i>Log activity</a>
                                        </li>
                                    </ul>
                                </li>
                                @endif

                                @if(\Auth::user()->role=='Manager')
                                <li class="active has-sub">
                                    <a class="js-arrow" href="/home">
                                        <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                                </li>
                                <li class="active">
                                    <a href="/categories">
                                        <i class="fa fa-list"></i>Categories</a>
                                </li>
                                <li class="active has-sub">
                                    <a class="js-arrow" href="#">
                                        <i class="fa fa-list"></i>Manage Stock</a>
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        <li class="active">
                                            <a href="{{ url('/products') }}"><i class="fa fa-list"></i>Stock</a>
                                        </li>
                                        <li class="active">
                                            <a href="/productsIn">
                                                <i class="fa fa-plus"></i>Product In</a>
                                        </li>
                                        <li class="active">
                                            <a href="{{ url('/demage_products') }}"><i class="fa fa-minus"></i>Demage Products</a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="active">
                                    <a href="/productsOut ">
                                        <i class="fa fa-minus"></i>Product Out</a>
                                </li>

                                <li class="active"><a href="{{ url('/suppliers') }}"><i class="fa fa-truck"></i>
                                        <span>Suppliers</span></a></li>

                                <li class="active"><a href="{{ url('/task') }}"><i class="fa fa-list"></i> <span>Tasks</span></a></li>
                                <li class="active"><a href="{{ url('/payment_history') }}"><i class="fa fa-history"></i> <span>Sales
                                            Payment hist</span></a></li>

                                <li class="active">
                                    <a href="/Expensive">
                                        <i class="fas fa-table"></i>Expenses</a>
                                </li>
                            @endif
                            </ul>

                        </div>
                        
                </nav>
        </header>
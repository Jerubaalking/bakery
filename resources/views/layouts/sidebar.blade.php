<aside id="sidebar" class="menu-sidebar d-none d-lg-block">
    <div class="sidebar-header logo" style="background-color:#494a4a;">
        <a href="#">
            <img src="{{asset('assets/img/user.png')}}" alt="misana home bakery" height="60" width="60" />
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1 ">
        <nav class="navbar-sidebar">
            <div class="collapse navbar-collapse">
            <!-- <div class="navbar-nav"></div> -->
            <ul class="list-unstyled navbar__list">
                @if(\Auth::user()->role=='Superadministrator')
                <li class="active has-sub ">
                    <a class="js-arrow"  href="/home">
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
                <li class="active has-sub">
                    <a class="js-arrow" href="#">
                        <i class="fa fa-cog"></i>Setting</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li class="active"><a href="{{ url('/user') }}"><i class="fa fa-users"></i>
                                <span>Users</span></a></li>
                    </ul>
                </li>
                <li class="active has-sub">
                    <a class="js-arrow " href="#">
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
                    <a href="/categories" class="js-arrow" >
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
                    <a class="js-arrow" href="/get_audit">
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
                    <a href="/productsOut" class="js-arrow" >
                        <i class="fa fa-minus"></i>Product Out</a>
                </li>

                <li class="active"><a class="js-arrow"  href="{{ url('/suppliers') }}"><i class="fa fa-truck"></i>
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
    </div>
</aside>
@include('layouts.header') 


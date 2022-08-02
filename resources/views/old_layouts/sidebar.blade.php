<aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
                    <img src="{{asset('assets/img/user.png')}}" alt="misana home bakery"  height="60" width="60"/>
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                    @if(\Auth::user()->role=='Superadministrator')
                        <li class="active has-sub">
                            <a class="js-arrow" href="/home">
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
                            
                        <li class="active"><a href="{{ url('/suppliers') }}"><i class="fa fa-truck"></i> <span>Suppliers</span></a></li>
                    
                        <li class="active"><a href="{{ url('/task') }}"><i class="fa fa-list"></i> <span>Tasks</span></a></li>
                          <li class="active"><a href="{{ url('/payment_history') }}"><i class="fa fa-history"></i> <span>Sales Payment hist</span></a></li>
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
                      
                               <a href="/cash_flow"  style="color:#F23810">
                                <i class="fas fa-file" style="color:red"></i>Cash Flow Report</a>  
                               </li>

                              <li class="active">
                      
                              <a href="/profit_loss"  style="color:#F23810 ">
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
                            </ul>
                            <li class="active">
                               <a href="/get_audit">
                                <i class="fas fa-history"></i>Log activity</a>
                              </li>
                        </li>
                        @endif
                     
                        @if(\Auth::user()->role=='Manager')
                       <li class="active has-sub">
                            <a class="js-arrow" href="/home">
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
                    
                        <!-- <li class="active has-sub">
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
                        </li> -->
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
                            
                        <li class="active"><a href="{{ url('/suppliers') }}"><i class="fa fa-truck"></i> <span>Suppliers</span></a></li>
                    
                        <li class="active"><a href="{{ url('/task') }}"><i class="fa fa-list"></i> <span>Tasks</span></a></li>
                         <li class="active"><a href="{{ url('/payment_history') }}"><i class="fa fa-history"></i> <span>Sales Payment hist</span></a></li>
                        <!-- <li class="active has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fa fa-briefcase"></i>Accounting</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li class="active">
                                 <a href="/Account">
                                <i class="fas fa-table"></i>Account Chart</a>
                               </li> -->
                               <!-- <li class="active">
                                 <a href="/account_group">
                                <i class="fas fa-table"></i>Account Group</a>
                               </li> -->
                           
                               <li class="active">
                               <a href="/Expensive">
                                <i class="fas fa-table"></i>Expenses</a>
                              </li>
                               
                                <!-- <li class="active">
                      
                               <a href="/cash_flow"  style="color:#F23810">
                                <i class="fas fa-file" style="color:red"></i>Cash Flow Report</a>  
                               </li>
                              <li class="active">
                      
                              <a href="/income_expenditure"  style="color:#F23810 ">
                              <i class="fas fa-file" style="color:red"></i>Income and Expenditure</a>  
                                </li> -->
                            </ul>
                        </li>

                 
                            </ul>
                        </li>
                        @endif

                        
                        
                    </ul>
                </nav>
            </div>
        </aside>
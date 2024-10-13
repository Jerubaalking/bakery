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
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
            <ul class="navbar-mobile__list list-unstyled" id="mobile-nav" style="margin-left:5px;">
                @php
                    $menuItems = [
                        'Superadministrator' => [
                            ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/home'],
                            ['icon' => 'fa fa-cog', 'label' => 'Setting', 'subItems' => [['url' => '/user', 'icon' => 'fa fa-users', 'label' => 'Users']]],
                            ['icon' => 'fa fa-money', 'label' => 'Payroll', 'subItems' => [
                                ['url' => '/department', 'icon' => 'fa fa-list', 'label' => 'Department'],
                                ['url' => '/position', 'icon' => 'fa fa-list', 'label' => 'Position'],
                                ['url' => '/employee', 'icon' => 'fa fa-users', 'label' => 'Employees'],
                                ['url' => '/cash_advance', 'icon' => 'fa fa-money', 'label' => 'Cash in Advance'],
                            ]],
                            ['icon' => 'fa fa-list', 'label' => 'Categories', 'url' => '/categories'],
                            ['icon' => 'fa fa-bank', 'label' => 'Production', 'subItems' => [
                                ['url' => '/measurements', 'icon' => 'fas fa-table', 'label' => 'Measurements'],
                                ['url' => '/materials', 'icon' => 'fas fa-history', 'label' => 'Material'],
                                ['url' => '/materialCategories', 'icon' => 'fas fa-history', 'label' => 'Material Categories'],
                                ['url' => '/intoStore', 'icon' => 'fas fa-history', 'label' => 'Store'],
                                ['url' => '/productionSessions', 'icon' => 'fas fa-history', 'label' => 'Production Sessions'],
                            ]],
                            ['icon' => 'fas fa-history', 'label' => 'Log activity', 'url' => '/get_audit'],
                            ['icon' => 'fa fa-list', 'label' => 'Manage Stock', 'subItems' => [
                                ['url' => '/products', 'icon' => 'fa fa-list', 'label' => 'Stock'],
                                ['url' => '/productsIn', 'icon' => 'fa fa-plus', 'label' => 'Product In'],
                                ['url' => '/demage_products', 'icon' => 'fa fa-minus', 'label' => 'Demage Products'],
                            ]],
                            ['icon' => 'fa fa-minus', 'label' => 'Product Out', 'url' => '/productsOut'],
                            ['icon' => 'fa fa-truck', 'label' => 'Designation', 'url' => '/designation'],
                            ['icon' => 'fa fa-list', 'label' => 'Tasks', 'url' => '/task'],
                            ['icon' => 'fa fa-history', 'label' => 'Sales Payment hist', 'url' => '/payment_history'],
                            ['icon' => 'fas fa-table', 'label' => 'Expenses', 'url' => '/Expensive'],
                            ['icon' => 'fa fa-briefcase', 'label' => 'Accounting', 'subItems' => [
                                ['url' => '/Account', 'icon' => 'fas fa-table', 'label' => 'Account Chart'],
                                ['url' => '/cash_flow', 'icon' => 'fas fa-file', 'label' => 'Cash Flow Report', 'style' => 'color:#F23810'],
                                ['url' => '/profit_loss', 'icon' => 'fas fa-file', 'label' => 'Income and Expenditure', 'style' => 'color:#F23810'],
                            ]],
                            ['icon' => 'fa fa-bank', 'label' => 'Banking', 'subItems' => [
                                ['url' => '/transfer', 'icon' => 'fas fa-table', 'label' => 'Transfer Fund'],
                                ['url' => '/deposite', 'icon' => 'fas fa-history', 'label' => 'Deposite Fund'],
                                ['url' => '/get_audit', 'icon' => 'fas fa-history', 'label' => 'Log activity'],
                            ]],
                        ],
                        'Manager' => [
                            ['icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard', 'url' => '/home'],
                            ['icon' => 'fa fa-list', 'label' => 'Categories', 'url' => '/categories'],
                            ['icon' => 'fa fa-list', 'label' => 'Manage Stock', 'subItems' => [
                                ['url' => '/products', 'icon' => 'fa fa-list', 'label' => 'Stock'],
                                ['url' => '/productsIn', 'icon' => 'fa fa-plus', 'label' => 'Product In'],
                                ['url' => '/demage_products', 'icon' => 'fa fa-minus', 'label' => 'Demage Products'],
                            ]],
                            ['icon' => 'fa fa-minus', 'label' => 'Product Out', 'url' => '/productsOut'],
                            ['icon' => 'fa fa-truck', 'label' => 'Suppliers', 'url' => '/suppliers'],
                            ['icon' => 'fa fa-list', 'label' => 'Tasks', 'url' => '/task'],
                            ['icon' => 'fa fa-history', 'label' => 'Sales Payment hist', 'url' => '/payment_history'],
                            ['icon' => 'fas fa-table', 'label' => 'Expenses', 'url' => '/Expensive'],
                        ],
                    ];
                @endphp

                @foreach($menuItems[\Auth::user()->role] as $item)
                    <li class="{{ isset($item['subItems']) ? 'has-sub' : '' }} {{ isset($item['active']) ? 'active' : '' }}">
                        <a class="js-arrow" href="{{ $item['url'] ?? '#' }}">
                            <i class="{{ $item['icon'] }}"></i>{{ $item['label'] }}</a>
                        @if(isset($item['subItems']))
                            <ul class="list-unstyled navbar__sub-list js-sub-list" style="margin-left:15px;">
                                @foreach($item['subItems'] as $subItem)
                                    <li class="active">
                                        <a href="{{ $subItem['url'] }}">
                                            <i class="{{ $subItem['icon'] }}" style="{{ $subItem['style'] ?? '' }}"></i>
                                            {{ $subItem['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </nav>
</header>

<aside id="sidebar" class="menu-sidebar d-none d-lg-block">
    <div class="sidebar-header logo" style="background-color:#494a4a;">
        <a href="#">
            <img src="{{ asset('assets/img/user.png') }}" alt="misana home bakery" height="60" width="60" />
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1 ">
        <nav class="navbar-sidebar">
            <div class="collapse navbar-collapse">
                <ul class="list-unstyled navbar__list">
                    @php
                        $menuItems = [
                            'Dashboard' => [
                                'icon' => 'fas fa-tachometer-alt',
                                'url' => '/home',
                                'roles' => ['Superadministrator', 'Manager'],
                            ],
                            'Product Stock' => [  // Changed from "Manage Stock" to "Product Stock"
                                'icon' => 'fa fa-list',
                                'subItems' => [
                                    'Categories' => [
                                        'url' => '/categories',
                                        'icon' => 'fa fa-list',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Products' => [
                                        'url' => '/products',
                                        'icon' => 'fa fa-list',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Product In' => [
                                        'url' => '/productsIn',
                                        'icon' => 'fa fa-plus',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Damage Products' => [
                                        'url' => '/demage_products',
                                        'icon' => 'fa fa-minus',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                ],
                                'roles' => ['Superadministrator', 'Manager'],
                            ],
                            'Production' => [
                                'icon' => 'fa fa-bank',
                                'subItems' => [
                                    'Measurements' => [
                                        'url' => '/measurements',
                                        'icon' => 'fas fa-table',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Material' => [
                                        'url' => '/materials',
                                        'icon' => 'fas fa-history',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Store' => [
                                        'url' => '/intoStore',
                                        'icon' => 'fas fa-history',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                ],
                                'roles' => ['Superadministrator', 'Manager'],
                            ],
                            'Sales' => [
                                'icon' => 'fas fa-shopping-cart',
                                'subItems' => [
                                    'Sales Accounts' => [
                                        'url' => '/task',
                                        'icon' => 'fa fa-list',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Sales Payment Hist' => [
                                        'url' => '/payment_history',
                                        'icon' => 'fa fa-history',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Expenses' => [
                                        'url' => '/Expensive',
                                        'icon' => 'fas fa-table',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                ],
                                'roles' => ['Superadministrator', 'Manager'],
                            ],
                            'Place of Work' => [ // New menu item for Place of Work
                                'icon' => 'fa fa-building',
                                'subItems' => [
                                    'Designation' => [
                                        'url' => '/designation',
                                        'icon' => 'fa fa-map-marker',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                    'Departments' => [
                                        'url' => '/department',
                                        'icon' => 'fa fa-sitemap',
                                        'roles' => ['Superadministrator', 'Manager'],
                                    ],
                                ],
                                'roles' => ['Superadministrator', 'Manager'],
                            ],
                            'Settings' => [
                                'icon' => 'fa fa-cog',
                                'subItems' => [
                                    'Users' => [
                                        'url' => url('/user'),
                                        'icon' => 'fa fa-users',
                                        'roles' => ['Superadministrator'],
                                    ],
                                ],
                                'roles' => ['Superadministrator'],
                            ],
                        ];
                    @endphp

                    @foreach ($menuItems as $item => $data)
                        @if (in_array(\Auth::user()->role, $data['roles']))
                            <li class="active {{ isset($data['subItems']) ? 'has-sub' : '' }}">
                                <a class="js-arrow" href="{{ $data['url'] ?? '#' }}">
                                    <i class="{{ $data['icon'] }}"></i>{{ $item }}</a>
                                @if (isset($data['subItems']))
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        @foreach ($data['subItems'] as $subItem => $subData)
                                            @if (in_array(\Auth::user()->role, $subData['roles']))
                                                <li class="active">
                                                    <a href="{{ $subData['url'] }}"><i class="{{ $subData['icon'] }}"></i>{{ $subItem }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</aside>
@include('layouts.header')

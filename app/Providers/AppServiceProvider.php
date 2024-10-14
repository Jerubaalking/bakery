<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Define the menu items based on user roles
        $menuItems = [
            'Dashboard' => [
                'icon' => 'fas fa-tachometer-alt',
                'url' => '/home',
                'roles' => ['Superadministrator', 'Manager'],
            ],
            'Product Stock' => [
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
                    'Production Sessions' => [
                        'url' => '/productionSessions',
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
            'Place of Work' => [
                'icon' => 'fa fa-building',
                'subItems' => [
                    'Locations' => [
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
                    // 'Activities' => [
                    //     'url' => url('/activities'),
                    //     'icon' => 'fa fa-list',
                    //     'roles' => ['Superadministrator'],
                    // ],
                ],
                'roles' => ['Superadministrator'],
            ],
        ];

        // Share the menu items with both views
        View::composer(['layouts.mobileNav', 'layouts.sidebar'], function ($view) use ($menuItems) {
            $view->with('menuItems', $menuItems);
        });
    }
}

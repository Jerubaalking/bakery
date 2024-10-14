<header class="header-mobile d-block d-lg-none" id="mobileNavBar">
    <div class="header-mobile__bar" id="mobile-btn">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <div class="account-item dropright">
                    <button class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="image">
                            <img src="{{ asset('assets/img/user.png') }}" alt="{{ \Auth::user()->name }}" />
                        </div>
                        <div class="content">
                            <a class="js-acc-btn" style="color:black;" href="#">{{ \Auth::user()->name }}</a>
                        </div>
                    </button>
                    <div class="dropdown-menu js-dropdown">
                        <button class="info clearfix">
                            <div class="image">
                                <a href="#">
                                    <img src="{{ asset('assets/img/user.png') }}" alt="{{ \Auth::user()->name }}" />
                                </a>
                            </div>
                            <div class="content">
                                <h5 class="name">
                                    <a href="#">{{ \Auth::user()->name }}</a>
                                </h5>
                                <span class="email"><p>{{ \Auth::user()->email }}</p></span>
                            </div>
                        </button>
                        <div class="account-dropdown__body">
                            <div class="account-dropdown__item">
                                <a href="#">
                                    <i class="zmdi zmdi-account"></i>Account
                                </a>
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
                <button class="hamburger hamburger--slider" type="button" aria-label="Toggle navigation">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <!-- @include('partials.menu_items') -->
    <nav class="navbar-mobile" id="mobile-sidebar">
    <div class="container-fluid">
        <ul class="navbar-mobile__list list-unstyled" id="mobile-nav" style="margin-left:5px;">
            @if (\Auth::check())
                @foreach ($menuItems as $item => $data)
                    @if (in_array(\Auth::user()->role, $data['roles']))
                        <li class="{{ isset($data['subItems']) ? 'has-sub' : '' }}">
                            <a class="js-arrow" href="{{ $data['url'] ?? '#' }}">
                                <i class="{{ $data['icon'] }}"></i> {{ $item }}
                            </a>
                            @if (isset($data['subItems']))
                                <ul class="list-unstyled navbar__sub-list js-sub-list" style="margin-left:15px;">
                                    @foreach ($data['subItems'] as $subItem => $subData)
                                        @if (in_array(\Auth::user()->role, $subData['roles']))
                                            <li>
                                                <a href="{{ $subData['url'] }}">
                                                    <i class="{{ $subData['icon'] }}" style="{{ $subData['style'] ?? '' }}"></i>
                                                    {{ $subItem }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</nav>

</header>

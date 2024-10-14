<aside id="sidebar" class="menu-sidebar d-none d-lg-block">
    <div class="sidebar-header logo" style="background-color:#494a4a;">
        <a href="#">
            <img src="{{ asset('assets/img/misana.png') }}" alt="misana home bakery" height="60" width="60" />
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <div class="collapse navbar-collapse">
                <ul class="list-unstyled navbar__list">
                    @include('partials.menu_items')
                    @foreach ($menuItems as $item => $data)
                        @if (in_array(\Auth::user()->role, $data['roles']))
                            <li class="{{ isset($data['subItems']) ? 'has-sub' : '' }}">
                                <a class="js-arrow" href="{{ $data['url'] ?? '#' }}">
                                    <i class="{{ $data['icon'] }}"></i> {{ $item }}
                                </a>
                                @if (isset($data['subItems']))
                                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                                        @foreach ($data['subItems'] as $subItem => $subData)
                                            @if (in_array(\Auth::user()->role, $subData['roles']))
                                                <li>
                                                    <a href="{{ $subData['url'] }}">
                                                        <i class="{{ $subData['icon'] }}"></i> {{ $subItem }}
                                                    </a>
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

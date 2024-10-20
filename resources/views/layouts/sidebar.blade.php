<aside id="sidebar" class="menu-sidebar d-none d-lg-block">
    <div class="sidebar-header logo" style="background-color:#494a4a;">
        <a href="#">
            <img src="{{ asset('assets/img/misana.png') }}" alt="misana home bakery" height="60" width="60" />
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <div class="collapse navbar-collapse">
                <!-- Session Dropdown -->
                <form action="{{ route('change.session') }}" method="POST">
                    @csrf
                    <div class="form-group text-sm">
                        <label for="session-select" style="color: #fff; font-size:smaller;">Select Session</label>
                        <select name="session_id" id="session-select" class="form-control">
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}" {{ auth()->user()->session_id == $session->id ? 'selected' : '' }}>
                                    {{ $session->name }} 
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if(auth()->user()->role === 'Superadministrator')
                    <button type="submit" class="btn btn-sm btn-primary mt-2">Switch Session</button>
                    @endif
                </form>
                <!-- End Session Dropdown -->

                <ul class="list-unstyled navbar__list">
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

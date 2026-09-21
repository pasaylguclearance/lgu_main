{{-- Console header (MediQ pattern) — replaces the sidebar.
     Desktop (>=1280px): tab rail of pills, groups open a white panel.
     Below 1280px: burger (☰) lists the same items.
     Nav data: config/navigation.php via App\Support\Navigation (presentation only). --}}
@php
    $navItems   = \App\Support\Navigation::items();
    $navName    = \App\Support\Navigation::displayName();
    $navInitial = \App\Support\Navigation::initials();
    $navEmail   = Auth::check() ? Auth::user()->email : '';
@endphp
<header class="pnp-header">
    <nav class="pnp-header__shell" aria-label="Main navigation">
        <div class="pnp-header__left">
            {{-- Brand pill: both logos keep their original click handlers (template.blade.php) --}}
            <div class="pnp-brand">
                <img src="{{ asset('img/logo.png') }}" alt="Philippine National Police" onclick="pnplogo()" title="PNP">
                <img src="{{ asset('backend/img/logos/pasay-logo.png') }}" alt="Pasay City Police" onclick="pasaylogo()" title="Pasay City Police">
            </div>

            <div class="pnp-title" title="Pasay Police Clearance">
                <span class="pnp-title__name">PASAY POLICE CLEARANCE</span>
            </div>

            {{-- Desktop tab rail --}}
            <ul class="pnp-rail list-unstyled mb-0" role="menubar">
                @foreach($navItems as $item)
                    @if(!empty($item['children']))
                        <li class="pnp-rail__item dropdown" role="none">
                            <a href="#" class="pnp-rail__link dropdown-toggle {{ $item['active'] ? 'is-active' : '' }}"
                               id="railMenu{{ $item['key'] }}" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" role="menuitem">
                                <i class="fas {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                                <i class="fas fa-chevron-down pnp-caret"></i>
                            </a>
                            <div class="dropdown-menu pnp-menu" aria-labelledby="railMenu{{ $item['key'] }}">
                                <p class="pnp-menu__title">{{ $item['label'] }}</p>
                                @foreach($item['children'] as $child)
                                    <a class="dropdown-item {{ $child['active'] ? 'is-active' : '' }}" href="{{ url($child['href']) }}">
                                        <i class="fas {{ $child['icon'] }}"></i>
                                        <span>{{ $child['label'] }}</span>
                                        @if($child['active'])<i class="fas fa-check pnp-check"></i>@endif
                                    </a>
                                @endforeach
                            </div>
                        </li>
                    @else
                        <li class="pnp-rail__item" role="none">
                            <a href="{{ url($item['href']) }}" class="pnp-rail__link {{ $item['active'] ? 'is-active' : '' }}" role="menuitem"
                               @if($item['active']) aria-current="page" @endif>
                                <i class="fas {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <div class="pnp-header__right">
            {{-- Notifications (placeholder data, as before) --}}
            <div class="dropdown">
                <a href="#" class="pnp-iconbtn dropdown-toggle" id="alertsDropdown" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" aria-label="Notifications" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="pnp-dot"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right pnp-menu" aria-labelledby="alertsDropdown">
                    <p class="pnp-menu__title">Notifications</p>
                    <span class="dropdown-item" style="cursor:default;">
                        <i class="fas fa-info-circle"></i>
                        <span>No new notifications</span>
                    </span>
                </div>
            </div>

            {{-- Account --}}
            <div class="dropdown">
                <button type="button" class="pnp-account dropdown-toggle" id="userDropdown" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" aria-label="Account menu" title="{{ $navName }}">
                    <span class="pnp-avatar">{{ $navInitial }}</span>
                    <span class="pnp-account__meta">
                        <span class="pnp-account__name">{{ $navName }}</span>
                        <span class="pnp-account__role">Signed in</span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-right pnp-menu pnp-menu--account" aria-labelledby="userDropdown">
                    <div class="pnp-menu__hero">
                        <span class="pnp-avatar">{{ $navInitial }}</span>
                        <div class="min-w-0">
                            <p class="text-truncate">{{ $navName }}</p>
                            <span class="pnp-role-pill">{{ $navEmail }}</span>
                        </div>
                    </div>
                    <a class="dropdown-item" href="{{ url('user') }}?edit={{ Auth::id() }}"><i class="fas fa-user-edit"></i> <span>Update User</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger-action" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> <span>Sign out</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- Burger (< 1280px): same items as the rail --}}
            <div class="dropdown pnp-burger">
                <button type="button" class="pnp-iconbtn dropdown-toggle" id="burgerDropdown" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false" aria-label="Open navigation menu" title="Menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right pnp-menu pnp-menu--burger" aria-labelledby="burgerDropdown">
                    <p class="pnp-menu__title">Menu</p>
                    @foreach($navItems as $item)
                        @if(!empty($item['children']))
                            <p class="pnp-menu__title">{{ $item['label'] }}</p>
                            @foreach($item['children'] as $child)
                                <a class="dropdown-item is-sub {{ $child['active'] ? 'is-active' : '' }}" href="{{ url($child['href']) }}">
                                    <i class="fas {{ $child['icon'] }}"></i>
                                    <span>{{ $child['label'] }}</span>
                                    @if($child['active'])<i class="fas fa-check pnp-check"></i>@endif
                                </a>
                            @endforeach
                        @else
                            <a class="dropdown-item {{ $item['active'] ? 'is-active' : '' }}" href="{{ url($item['href']) }}">
                                <i class="fas {{ $item['icon'] }}"></i>
                                <span>{{ $item['label'] }}</span>
                                @if($item['active'])<i class="fas fa-check pnp-check"></i>@endif
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </nav>
</header>

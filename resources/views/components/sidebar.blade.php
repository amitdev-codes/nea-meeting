@php
    use App\Helpers\MenuHelper;

    $user = auth()->user();
    $userPermissions = $user ? $user->roles->flatMap->permissions->pluck('name')->unique()->toArray() : [];
@endphp


<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme border-end">
{{--    <div class="app-brand py-3 px-3 border-bottom"--}}
{{--         style="background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 50%, #9333ea 100%);">--}}

{{--        <a href="{{ route('dashboard') }}"--}}
{{--           class="app-brand-link d-flex align-items-center text-decoration-none w-100">--}}

{{--<span class="app-brand-logo d-flex align-items-center justify-content-center flex-shrink-0">--}}
{{--    <img src="{{ Vite::asset('resources/assets/images/nea-logo.png') }}"--}}
{{--         alt="NEA Logo"--}}
{{--         class="sidebar-logo">--}}
{{--</span>--}}

{{--            <span class="app-brand-text ms-3 text-white lh-sm">--}}
{{--            <span class="fw-bold d-block" style="font-size: 14px;">--}}
{{--                {{ __('field.nea') }}--}}
{{--            </span>--}}

{{--            <small class="d-block text-white opacity-75"--}}
{{--                   style="font-size: 11px; line-height: 1.2;">--}}
{{--                {{ __('field.meeting_management_system') }}--}}
{{--            </small>--}}
{{--        </span>--}}

{{--        </a>--}}
{{--    </div>--}}
    <div class="app-brand border-bottom px-3 py-3"
         style="background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 45%, #ddd6fe 100%);">

        <a href="{{ route('dashboard') }}"
           class="app-brand-link d-flex align-items-center text-decoration-none w-100 overflow-hidden">

            {{-- Logo --}}
            <span class="flex-shrink-0">
            <img src="{{ Vite::asset('resources/assets/images/nea-logo.png') }}"
                 alt="NEA Logo"
                 class="sidebar-logo">
        </span>

            {{-- Text --}}
            <div class="ms-2 flex-grow-1 overflow-hidden">
                <div class="sidebar-title fw-bold">
                    Nepal Electricity Authority
                </div>

                <div class="sidebar-subtitle">
                    Meeting Management System
                </div>
            </div>

        </a>
    </div>
    <div class="user-profile py-3 px-4 border-bottom">
        <div class="d-flex align-items-center">
            <div class="avatar avatar-online me-3">
                <img src="{{ Vite::asset('resources/assets/images/no-image.png') }}" alt="User Avatar"
                     class="w-px-40 h-auto rounded-circle">
            </div>
            <div class="user-profile-text">
                <h6 class="mb-0 fw-bold">{{ ucwords($user->username) }}</h6>
                <small class="text-muted fw-bold">{{ $user->roles->first()->name ?? 'No Role' }}</small>
                <br>
            </div>
        </div>
    </div>

    <ul class="menu-inner py-3">
        @foreach ($verticalMenuData->menu as $menuSection)
            @php
                $hasAccessibleItems = false;
                foreach ($menuSection->items as $menu) {
                    if (MenuHelper::canAccessMenu($menu, $userPermissions)) {
                        $hasAccessibleItems = true;
                        break;
                    }
                }
            @endphp

            @if (isset($menuSection->header) && $hasAccessibleItems)
                <li class="menu-header">
                    <span class="menu-header-text">{{ __($menuSection->header) }}</span>
                </li>
            @endif

            @foreach ($menuSection->items as $menu)
                @if (MenuHelper::canAccessMenu($menu, $userPermissions))
                    <li class="menu-item {{ Route::is($menu->slug) ? 'active open' : '' }}">
                        <a href="{{ isset($menu->url) ? route($menu->url) : 'javascript:void(0);' }}"
                           class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}">
                            <i class="{{ $menu->icon ?? '' }}"></i>
                            <div>{{ isset($menu->name) ? MenuHelper::resolveLabel($menu->name) : '' }}</div>
                        </a>

                        @isset($menu->submenu)
                            <ul class="menu-sub">
                                @foreach ($menu->submenu as $submenu)
                                    @if (MenuHelper::canAccessMenu($submenu, $userPermissions))
                                        <li class="menu-item {{ Route::is($submenu->slug) ? 'active open' : '' }}">
                                            <a href="{{ isset($submenu->url) ? route($submenu->url) : 'javascript:void(0)' }}"
                                               class="menu-link">
                                                @if (isset($submenu->icon))
                                                    <i class="{{ $submenu->icon }}"></i>
                                                @endif
                                                <div>
                                                    {{ isset($submenu->name) ? MenuHelper::resolveLabel($submenu->name) : '' }}
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endisset
                    </li>
                @endif
            @endforeach
        @endforeach
    </ul>
</aside>
@section('page-style')
    <style>
        .bg-menu-theme .menu-sub>.menu-item>.menu-link:before {
            display: none !important;
        }

        /* Add consistent spacing for all menu items */
        .menu-link i {
            margin-right: 12px;
            /* Space between icon and text */
            font-size: 1.2rem;
            /* Optional: Adjust icon size */
            width: 20px;
            /* Fixed width for alignment */
            text-align: center;
        }

        /* Submenu specific spacing */
        .menu-sub .menu-link i {
            margin-right: 10px;
            /* Slightly less space in submenus */
            font-size: 1.1rem;
        }

        /* Adjust padding for better visual hierarchy */
        .menu-link {
            padding: 0.625rem 1rem;
        }

        .menu-sub .menu-link {
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            /* Extra left padding for submenu indentation */
        }

        /* User Profile Section Styling */
        .user-profile {
            background: rgba(255, 255, 255, 0.05);
            /* Subtle background for contrast */
        }

        .user-profile .avatar img {
            width: 40px;
            height: 40px;
        }

        .user-profile h6 {
            font-size: 1rem;
            line-height: 1.2;
        }

        .user-profile small {
            font-size: 0.85rem;
            line-height: 1.2;
        }

        .layout-navbar-fixed.layout-compact.layout-menu-fixed.layout-menu-collapsed .user-profile-text {
            display: none !important;
        }
        .sidebar-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
            display: block;
        }

        .sidebar-title {
            font-size: clamp(11px, 1vw, 14px);
            line-height: 1.2;
            color: #5b21b6;
            /*white-space: nowrap;*/
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-subtitle {
            font-size: clamp(9px, 0.8vw, 11px);
            color: #7c3aed;
            line-height: 1.1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .app-brand-link {
            min-width: 0;
        }

        /* Collapsed sidebar */
        .layout-menu-collapsed .sidebar-title,
        .layout-menu-collapsed .sidebar-subtitle {
            display: none;
        }
    </style>
@endsection

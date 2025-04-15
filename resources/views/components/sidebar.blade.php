<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme border-end">
    <div class="app-brand py-4 px-3 border-bottom">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/gov.png') }}" alt="Government Logo" width="35">
            </span>
            <span class="app-brand-text ms-2 fw-bold">
                <span>{{ __('field.nea') }}<br> {{ __('field.meeting_management_system') }}</span>
            </span>
            
        </a>
    </div>

    <li class="menu-item border-bottom fiscal-date">
        <div class="menu-link d-flex flex-column fiscal-date-container align-items-start px-5">
            <span class="fiscal-year small fw-medium text-primary">
                {{ __('field.current_fiscal_year') }}: {{ $currentFiscalYear }}
            </span>
            <span class="nepali-date small text-primary">
                {{ $nepaliDateTime['formatted_date'] }},
                {{ $nepaliDateTime['weekday'] }}, <span id="nepaliDateTime"></span>
            </span>
        </div>
    </li>

    <ul class="menu-inner py-3">
        @foreach ($menuData->menu as $menuSection)
            @php
                $hasAccessibleItems = false;
                foreach ($menuSection->items as $menu) {
                    if (\App\Helpers\MenuHelper::canAccessMenu($menu)) {
                        $hasAccessibleItems = true;
                        break;
                    }
                }
            @endphp

            @if (isset($menuSection->header) && $hasAccessibleItems)
                <li class="menu-header">
                    <span class="menu-header-text" style="font-size: 0.9rem;font-weight: 600">{{ __($menuSection->header) }}</span>
                </li>
            @endif

            @foreach ($menuSection->items as $menu)
                @if (\App\Helpers\MenuHelper::canAccessMenu($menu))
                    <li class="menu-item {{ Route::is($menu->slug) ? 'active open' : '' }}">
                        <a href="{{ isset($menu->url) ? route($menu->url) : 'javascript:void(0);' }}"
                            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}">
                            <i class="{{ $menu->icon }}"></i>
                            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                        </a>

                        @isset($menu->submenu)
                            <ul class="menu-sub">
                                @foreach ($menu->submenu as $submenu)
                                    @if (\App\Helpers\MenuHelper::canAccessMenu($submenu))
                                        <li class="menu-item {{ Route::is($submenu->slug) ? 'active open' : '' }}">
                                            <a href="{{ isset($submenu->url) ? route($submenu->url) : 'javascript:void(0)' }}"
                                                class="menu-link">
                                                @if (isset($submenu->icon))
                                                    <i class="{{ $submenu->icon }}"></i>
                                                @endif
                                                <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
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
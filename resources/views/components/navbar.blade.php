<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme border-bottom"
     id="layout-navbar">

    {{-- ── Left: hamburger ──────────────────────────────────────── --}}
    <div class="navbar-nav flex-row align-items-center">
        <div class="layout-menu-toggle">
            <a class="nav-item nav-link px-0" href="javascript:void(0)">
                <i class="bx bx-menu"></i>
            </a>
        </div>
    </div>

    {{-- ── Right section ────────────────────────────────────────── --}}
    <div class="navbar-nav-right d-flex align-items-center gap-2" id="navbar-collapse">

        {{-- ── Google Calendar chip ──────────────────────────────── --}}
        <div class="navbar-gcal me-1">

            @if (auth()->user()->google_access_token)
                {{-- Connected state --}}
                <div class="nav-item dropdown navbar-gcal-dropdown">
                    <a class="gcal-chip gcal-chip--connected dropdown-toggle hide-arrow"
                       href="javascript:void(0);"
                       data-bs-toggle="dropdown"
                       data-bs-auto-close="outside"
                       aria-expanded="false">

                        <span class="gcal-icon-wrap gcal-icon-wrap--connected">
                            @include('_partials._google-icon')
                        </span>

                        <span class="gcal-text">
                            <span class="gcal-title">{{ __('Google Calendar') }}</span>
                            <span class="gcal-status gcal-status--connected">{{ __('Connected') }}</span>
                        </span>

                        <span class="gcal-pulse" aria-hidden="true"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end gcal-dropdown-menu p-0">
                        <div class="gcal-dropdown-header">
                            <span class="gcal-icon-wrap gcal-icon-wrap--connected gcal-icon-wrap--lg">
                                @include('_partials._google-icon')
                            </span>
                            <div>
                                <p class="gcal-dropdown-title">{{ __('Google Calendar') }}</p>
                                <p class="gcal-dropdown-synced">
                                    <i class="bx bx-check-circle"></i>
                                    {{ __('Synced with your account') }}
                                </p>
                            </div>
                        </div>

                        <div class="gcal-dropdown-body">
                            <p class="gcal-dropdown-desc">
                                {{ __('Meetings and schedules stay in sync with your Google Calendar automatically.') }}
                            </p>
                        </div>

                        <div class="gcal-dropdown-footer">
                            <form method="POST" action="{{ route('google.disconnect') }}">
                                @csrf
                                <button type="submit" class="gcal-disconnect-btn">
                                    <i class="bx bx-unlink"></i>
                                    {{ __('Disconnect Calendar') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                {{-- Disconnected state --}}
                <div class="gcal-chip gcal-chip--disconnected">
                    <span class="gcal-icon-wrap gcal-icon-wrap--disconnected">
                        @include('_partials._google-icon', ['muted' => true])
                    </span>

                    <span class="gcal-text">
                        <span class="gcal-title">{{ __('Google Calendar') }}</span>
                        <span class="gcal-status gcal-status--disconnected">{{ __('Sync meetings automatically') }}</span>
                    </span>

                    <a href="{{ route('google.auth') }}" class="gcal-connect-btn">
                        <i class="bx bx-link"></i>
                        <span>{{ __('Connect') }}</span>
                    </a>
                </div>
            @endif
        </div>

        <div class="navbar-divider d-none d-xl-block"></div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            {{-- Language switcher --}}
            <li class="nav-item dropdown dropdown-language me-2 me-xl-0">
                <a class="nav-link nav-icon-btn d-flex align-items-center gap-1"
                   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://flagcdn.com/{{ auth()->user()->locale === 'en' ? 'us' : 'np' }}.svg"
                         width="20" height="14" class="flag-img"
                         alt="{{ auth()->user()->locale === 'en' ? 'US' : 'Nepal' }}">
                    <span class="nav-label">{{ auth()->user()->locale === 'en' ? 'EN' : 'NP' }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item {{ auth()->user()->locale === 'en' ? 'active' : '' }}"
                           href="{{ route('account.locale', ['locale' => 'en']) }}">
                            <img src="https://flagcdn.com/us.svg" width="20" height="14" class="flag-img me-2" alt="US">
                            English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ auth()->user()->locale === 'np' ? 'active' : '' }}"
                           href="{{ route('account.locale', ['locale' => 'np']) }}">
                            <img src="https://flagcdn.com/np.svg" width="20" height="14" class="flag-img me-2" alt="Nepal">
                            नेपाली
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Theme switcher --}}
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link nav-icon-btn hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-moon bx-sm"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li><a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <i class="bx bx-sun me-2 bx-spin-hover"></i>Light
                        </a></li>
                    <li><a class="dropdown-item active" href="javascript:void(0);" data-theme="dark">
                            <i class="bx bx-moon me-2 bx-flashing-hover"></i>Dark
                        </a></li>
                    <li><a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <i class="bx bx-desktop me-2"></i>System
                        </a></li>
                </ul>
            </li>

            {{-- Shortcuts --}}
            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link nav-icon-btn hide-arrow" href="javascript:void(0);"
                   data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="bx bx-grid-alt bx-sm"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Shortcuts</h6>
                        </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container ps">
                        @hasanyrole('admin|superadmin')
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-calendar bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('admin.calendar') }}" class="stretched-link">Calendar</a>
                                <small>Appointments</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-food-menu bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('admin.meetings.index') }}" class="stretched-link">Meetings</a>
                                <small>Manage Meetings</small>
                            </div>
                        </div>
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-user bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('admin.users.index') }}" class="stretched-link">User App</a>
                                <small>Manage Users</small>
                            </div>
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-check-shield bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('admin.roles.index') }}" class="stretched-link">Role Management</a>
                                <small>Permission</small>
                            </div>
                        </div>
                        <div class="row row-bordered overflow-visible g-0">
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-pie-chart-alt-2 bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('dashboard') }}" class="stretched-link">Dashboard</a>
                                <small>User Dashboard</small>
                            </div>
                        </div>
                        @endhasanyrole
                    </div>
                </div>
            </li>

            {{-- User avatar --}}
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ auth()->user()->profile_photo_url ?? asset('./../assets/img/avatars/8.png') }}"
                             alt="{{ auth()->user()->username }}"
                             class="w-px-32 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('account.profile.edit') }}">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-online me-3" style="width:32px;height:32px">
                                    <img src="{{ auth()->user()->profile_photo_url ?? asset('./../assets/img/avatars/8.png') }}"
                                         alt="" class="rounded-circle" style="width:32px;height:32px">
                                </div>
                                <div>
                                    <p class="mb-0 fw-medium" style="font-size:13px">{{ auth()->user()->username }}</p>
                                    <small class="text-muted">
                                        {{ auth()->user()->getRoleNames()->first() ?? 'No Role' }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('account.profile.edit') }}">
                            <i class="bx bx-user bx-sm me-3"></i>My Profile
                        </a>
                    </li>
                    <li><div class="dropdown-divider my-1"></div></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-nav-form" style="display:none">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="javascript:void(0);"
                           onclick="document.getElementById('logout-nav-form').submit()">
                            <i class="bx bx-power-off bx-sm me-3"></i>Log Out
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>
<style>
        /* ── Shared tokens ───────────────────────────────────────────── */
        :root {
            --gcal-green:       #1D9E75;
            --gcal-green-light: #E1F5EE;
            --gcal-green-dark:  #0F6E56;
            --gcal-green-deep:  #085041;
            --gcal-red:         #A32D2D;
            --gcal-red-light:   #FCEBEB;
            --gcal-red-mid:     #E24B4A;
        }

        /* ── Navbar tweaks ───────────────────────────────────────────── */
        #layout-navbar {
            padding-top: 0;
            padding-bottom: 0;
            height: 62px;
        }

        .navbar-divider {
            width: 1px;
            height: 22px;
            background: rgba(0,0,0,0.1);
            margin: 0 4px;
        }

        .nav-icon-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background 0.12s;
        }
        .nav-icon-btn:hover { background: rgba(0,0,0,0.05); }

        .nav-label {
            font-size: 11px;
            font-weight: 500;
        }

        .flag-img {
            border-radius: 2px;
            object-fit: cover;
        }

        /* ── Google Calendar chip ────────────────────────────────────── */
        .gcal-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px 6px 8px;
            border-radius: 10px;
            border: 1px solid rgba(0,0,0,0.1);
            background: transparent;
            cursor: pointer;
            text-decoration: none !important;
            transition: background 0.12s;
            white-space: nowrap;
        }
        .gcal-chip:hover { background: rgba(0,0,0,0.04); }

        .gcal-chip--connected {
            border-color: var(--gcal-green);
            border-width: 1px;
        }
        .gcal-chip--disconnected {
            border-style: dashed;
            border-color: rgba(0,0,0,0.15);
        }

        /* Icon wrap */
        .gcal-icon-wrap {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .gcal-icon-wrap svg { width: 16px; height: 16px; }
        .gcal-icon-wrap--connected    { background: var(--gcal-green-light); }
        .gcal-icon-wrap--disconnected { background: rgba(0,0,0,0.05); }
        .gcal-icon-wrap--lg { width: 36px; height: 36px; border-radius: 9px; }
        .gcal-icon-wrap--lg svg { width: 20px; height: 20px; }

        /* Text labels */
        .gcal-text {
            display: flex;
            flex-direction: column;
            line-height: 1;
        }
        .gcal-title {
            font-size: 12px;
            font-weight: 600;
            color: inherit;
        }
        .gcal-status {
            font-size: 10px;
            margin-top: 3px;
        }
        .gcal-status--connected    { color: var(--gcal-green-dark); }
        .gcal-status--disconnected { color: #888; }

        /* Pulse dot */
        .gcal-pulse {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gcal-green);
            flex-shrink: 0;
            animation: gcal-pulse 2.2s ease-in-out infinite;
        }
        @keyframes gcal-pulse {
            0%, 100% { opacity: 1; transform: scale(1);   }
            50%       { opacity: 0.4; transform: scale(0.8); }
        }

        /* Connect button */
        .gcal-connect-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            background: #185FA5;
            color: #E6F1FB !important;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none !important;
            transition: background 0.12s;
            white-space: nowrap;
        }
        .gcal-connect-btn:hover { background: #0C447C; }
        .gcal-connect-btn i { font-size: 13px; }

        /* ── Dropdown menu ───────────────────────────────────────────── */
        .gcal-dropdown-menu {
            width: 290px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-top: 6px !important;
        }

        .gcal-dropdown-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.07);
        }
        .gcal-dropdown-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            color: inherit;
        }
        .gcal-dropdown-synced {
            font-size: 11px;
            color: var(--gcal-green-dark);
            margin: 3px 0 0;
            display: flex;
            align-items: center;
            gap: 3px;
        }
        .gcal-dropdown-synced i { font-size: 12px; }

        .gcal-dropdown-body {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0,0,0,0.07);
        }
        .gcal-dropdown-desc {
            font-size: 12px;
            color: #666;
            line-height: 1.55;
            margin: 0;
        }

        .gcal-dropdown-footer { padding: 12px 16px; }

        .gcal-disconnect-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 7px 12px;
            border-radius: 7px;
            border: 1px solid var(--gcal-red);
            background: transparent;
            color: var(--gcal-red);
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.12s;
        }
        .gcal-disconnect-btn:hover { background: var(--gcal-red-light); }
        .gcal-disconnect-btn i { font-size: 14px; }

        /* ── Responsive: hide text labels on small screens ───────────── */
        @media (max-width: 992px) {
            .gcal-text { display: none; }
            .gcal-chip { padding: 6px 8px; gap: 5px; }
            .gcal-connect-btn span { display: none; }
            .gcal-connect-btn { padding: 5px 7px; }
            .nav-label { display: none; }
        }
    </style>



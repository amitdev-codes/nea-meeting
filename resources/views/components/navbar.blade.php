<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme border-bottom"
    id="layout-navbar">
    <div class="navbar-nav flex-row align-items-center">
        <div class="layout-menu-toggle">
            <a class="nav-item nav-link px-0" href="javascript:void(0)">
                <i class="bx bx-menu text-primary"></i>
            </a>
        </div>

    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item dropdown dropdown-language me-2 me-xl-0">
                <!-- Language Switcher -->
                <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdownLanguage" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://flagcdn.com/{{ auth()->user()->locale === 'en' ? 'us' : 'np' }}.svg"
                        class="small" width="20" height="15"
                        alt="{{ auth()->user()->locale === 'en' ? 'US Flag' : 'Nepal Flag' }}">
                    <span class="ms-1 small">{{ auth()->user()->locale === 'en' ? 'English' : 'नेपाली' }}</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownLanguage">
                    <!-- English Link -->
                    <li>
                        <a class="dropdown-item {{ auth()->user()->locale === 'en' ? 'active' : '' }}"
                            href="{{ route('account.locale', ['locale' => 'en']) }}">
                            <img src="https://flagcdn.com/us.svg" width="20" height="15" alt="US Flag"
                                class="small">
                            <span class="ms-2 small">English</span>
                        </a>
                    </li>
                    <!-- Nepali Link -->
                    <li>
                        <a class="dropdown-item {{ auth()->user()->locale === 'np' ? 'active' : '' }}"
                            href="{{ route('account.locale', ['locale' => 'np']) }}">
                            <img src="https://flagcdn.com/np.svg" width="20" height="15" alt="Nepal Flag"
                                class="small">
                            <span class="ms-2 small">नेपाली</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- /Language -->


            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bx bx-sm"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span><i class="bx bx-sun me-3 bx-spin-hover"></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item active" href="javascript:void(0);" data-theme="dark">
                            <span><i class="bx bx-moon me-3 bx-flashing-hover"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span><i class="bx bx-desktop me-3"></i>System</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!-- / Style Switcher-->


            <!-- Quick links  -->
            <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bx bx-grid-alt bx-sm"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                    <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Shortcuts</h6>
                            <a href="javascript:void(0)" class="dropdown-shortcuts-add py-2" data-bs-toggle="tooltip"
                                data-bs-placement="top" aria-label="Add shortcuts"
                                data-bs-original-title="Add shortcuts"><i
                                    class="bx bx-plus-circle text-heading"></i></a>
                        </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container ps">
                        <div class="row row-bordered overflow-visible g-0">
                            @hasanyrole('user|admin|superadmin')
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-calendar bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('admin.calendar') }}" class="stretched-link">Calendar</a>
                                <small>Appointments</small>
                            </div>
                            @endhasanyrole
                            @hasanyrole('admin|superadmin')
                            <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="bx bx-food-menu bx-26px text-heading"></i>
                                </span>
                                <a href="{{ route('admin.meetings.index') }}" class="stretched-link">Meetings</a>
                                <small>Manage Meetings</small>
                            </div>
                            @endhasanyrole
                        </div>
                        @hasanyrole('admin|superadmin')
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
                        @endhasanyrole
                        @hasanyrole('admin|superadmin')
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

                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                        </div>
                    </div>
                </div>
            </li>
            <!-- Quick links -->

            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                    data-bs-auto-close="outside" aria-expanded="false">
                    <span class="position-relative">
                        <i class="bx bx-bell bx-sm"></i>
                        <!-- <span class="badge rounded-pill badge-dot badge-notifications border p-1"></span> -->
                        <div class="indicator">
                            <div class="circle"></div>
                        </div>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h6 class="mb-0 me-auto">Notification</h6>
                            <div class="d-flex align-items-center h6 mb-0">
                                <span class="badge bg-label-primary me-2">8 New</span>
                                <a href="javascript:void(0)" class="dropdown-notifications-all p-2"
                                    data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Mark all as read"
                                    data-bs-original-title="Mark all as read"><i
                                        class="bx bx-envelope-open text-heading"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container ps">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <img src="../../assets/img/avatars/1.png" alt=""
                                                class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="small mb-0">Congratulation Lettie 🎉</h6>
                                        <small class="mb-1 d-block text-body">Won the monthly best seller gold
                                            badge</small>
                                        <small class="text-muted">1h ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                class="bx bx-x"></span></a>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar">
                                            <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="small mb-0">Charles Franklin</h6>
                                        <small class="mb-1 d-block text-body">Accepted your connection</small>
                                        <small class="text-muted">12hr ago</small>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                                class="badge badge-dot"></span></a>
                                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                                class="bx bx-x"></span></a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                        </div>
                        <div class="ps__rail-y" style="top: 0px; right: 0px;">
                            <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                        </div>
                    </li>
                    <li class="border-top">
                        <div class="d-grid p-4">
                            <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                                <small class="align-middle">View all notifications</small>
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="../../assets/img/avatars/1.png" alt=""
                            class="w-px-30 h-auto rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="pages-account-settings-account.html">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="../../assets/img/avatars/1.png" alt=""
                                            class="w-px-30 h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->user()->username }}</h6>
                                    <small class="text-muted">
                                        {{ auth()->user()->getRoleNames()->first() ?? 'No Role' }}
                                    </small>
                                </div>
                                
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('account.profile.edit') }}">
                            <i class="bx bx-user bx-sm me-3"></i><span>My Profile</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="{{ route('admin.site-settings.index') }}">
                            <i class="bx bx-cog bx-sm me-3"></i><span>Settings</span>
                        </a>
                    </li> --}}
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-menu-form-on-nav"
                            style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="document.getElementById('logout-menu-form-on-nav').submit();">
                            <i class="bx bx-power-off bx-sm me-3"></i><span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>
@push('scripts')
    <script type="module">
        function getNepaliNumber(number) {
            const nepaliNumbers = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
            return number.toString().split('').map(digit => nepaliNumbers[digit] || digit).join('');
        }

        function getNepaliTimePeriod(hours) {
            if (hours >= 4 && hours < 12) {
                return 'बिहान'; // Morning
            } else if (hours >= 12 && hours < 16) {
                return 'दिउँसो'; // Afternoon
            } else if (hours >= 16 && hours < 20) {
                return 'बेलुका'; // Evening
            } else {
                return 'राति'; // Night
            }
        }

        function updateNepaliTime() {
            const now = new Date();
            const hours = getNepaliNumber(now.getHours().toString().padStart(2, '0'));
            const minutes = getNepaliNumber(now.getMinutes().toString().padStart(2, '0'));
            const seconds = getNepaliNumber(now.getSeconds().toString().padStart(2, '0'));
            const timePeriod = getNepaliTimePeriod(now.getHours());
            const formattedTime = `${hours}:${minutes}:${seconds} ${timePeriod}`;

            const timeElement = document.getElementById('nepaliDateTime');
            if (timeElement) {
                timeElement.textContent = formattedTime;
            }
        }

        setInterval(updateNepaliTime, 1000);
        document.addEventListener('DOMContentLoaded', updateNepaliTime);
    </script>
@endpush

@push('styles')
<style>
    .fiscal-date-container {
        min-width: 250px; /* Adjust as needed for consistent width */
        text-align: left; /* Align all text to the left */
    }

    .fiscal-year,
    .nepali-date {
        display: block;
        white-space: nowrap; /* Prevent text wrapping */
        overflow: hidden; /* Hide overflow if too long */
        text-overflow: ellipsis; /* Add ellipsis for long text */
    }

    .fiscal-year {
        font-size: 0.85rem; /* Slightly smaller than navbar default */
        line-height: 1.2; /* Tighten line spacing */
    }

    .nepali-date {
        font-size: 0.85rem;
        line-height: 1.2;
    }

    /* Ensure primary color consistency */
    .text-primary {
        color: #007bff !important; /* Default Bootstrap primary color, override if custom */
    }
</style>
@endpush
<header class="app-header fixed-top">
    <div class="app-header-inner">
        <div class="container-fluid py-2">
            <div class="app-header-content">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                                <title>Menu</title>
                                <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="search-mobile-trigger d-sm-none col">
                        <i class="search-mobile-trigger-icon fa-solid fa-magnifying-glass"></i>
                    </div>

                    <div class="app-search-box col">
                        <form class="app-search-form" method="GET" action="{{ url()->current() }}">
                            <input type="text" placeholder="Search..." name="search" class="form-control search-input" value="{{ request('search') }}">
                            <button type="submit" class="btn search-btn btn-primary" value="Search">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>

                    <div class="app-utilities col-auto">
                        <div class="app-utility-item app-notifications-dropdown dropdown">
                            <a class="dropdown-toggle no-toggle-arrow" id="notifications-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" title="Notifications">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                                    <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                </svg>
                                <span class="icon-badge">{{ auth()->user()->isAdmin() ? '2' : '1' }}</span>
                            </a>

                            <div class="dropdown-menu p-0" aria-labelledby="notifications-dropdown-toggle">
                                <div class="dropdown-menu-header p-3">
                                    <h5 class="dropdown-menu-title mb-0">Notifications</h5>
                                </div>
                                <div class="dropdown-menu-content">
                                    <div class="item p-3">
                                        <div class="row gx-2 justify-content-between align-items-center">
                                            <div class="col-auto">
                                                <div class="app-icon-holder">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.854 5.146a.5.5 0 0 0-.708 0L13.5 6.793l-.646-.647a.5.5 0 0 0-.708.708l1 1a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0 0-.708z"/>
                                                        <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm11-1c0-.246-.154-.986-.832-1.664C10.516 10.68 9.289 10 7 10c-2.29 0-3.516.68-4.168 1.332C2.154 12.014 2 12.754 2 13h10z"/>
                                                        <path fill-rule="evenodd" d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM4 5a3 3 0 1 1 6 0 3 3 0 0 1-6 0z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="info">
                                                    <div class="desc">Signed in as {{ auth()->user()->name }}.</div>
                                                    <div class="meta">Now</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="item p-3">
                                        <div class="row gx-2 justify-content-between align-items-center">
                                            <div class="col-auto">
                                                <div class="app-icon-holder icon-holder-mono">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                                        <path fill-rule="evenodd" d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z"/>
                                                        <circle cx="3.5" cy="5.5" r=".5"/>
                                                        <circle cx="3.5" cy="8" r=".5"/>
                                                        <circle cx="3.5" cy="10.5" r=".5"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="info">
                                                    <div class="desc">
                                                        {{ auth()->user()->isAdmin() ? 'Admin registration workflow is available.' : 'Your activity registration workspace is ready.' }}
                                                    </div>
                                                    <div class="meta">Today</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php($workspaceRoute = auth()->user()->isAdmin() ? 'admin.registrations.index' : 'my-registrations.index')
                                @if (Route::has($workspaceRoute))
                                    <div class="dropdown-menu-footer p-2 text-center">
                                        <a href="{{ route($workspaceRoute) }}">Open workspace</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="app-utility-item">
                            <a href="{{ route('profile.edit') }}" title="Settings">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z"/>
                                    <path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z"/>
                                </svg>
                            </a>
                        </div>

                        <div class="app-utility-item app-user-dropdown dropdown">
                            <a class="dropdown-toggle" id="user-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                <img src="{{ asset('portal/assets/images/user.png') }}" alt="user profile">
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
                                <li class="dropdown-item-text">
                                    <div class="fw-bold">{{ auth()->user()->name }}</div>
                                    <div class="small text-muted">{{ auth()->user()->email }}</div>
                                    <div class="small text-uppercase text-muted mt-1">{{ auth()->user()->role }}</div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Account</a></li>
                                @if (Route::has('preferences.edit') && auth()->user()->isUser())
                                    <li><a class="dropdown-item" href="{{ route('preferences.edit') }}">Preferences</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Log Out</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="app-sidepanel" class="app-sidepanel">
        <div id="sidepanel-drop" class="sidepanel-drop"></div>
        <div class="sidepanel-inner d-flex flex-column">
            <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>
            <div class="app-branding">
                <a class="app-logo" href="{{ route('dashboard') }}">
                    <img class="logo-icon me-2" src="{{ asset('portal/assets/images/app-logo.svg') }}" alt="logo">
                    <span class="logo-text">{{ strtoupper(config('app.name', 'Portal')) }}</span>
                </a>
            </div>

            <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                <ul class="app-menu list-unstyled accordion" id="menu-accordion">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-speedometer2" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4zM3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 9.31A.91.91 0 1 0 8.85 10.58l3.434-4.297a.389.389 0 0 0-.029-.529z"/>
                                    <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.341-2.341.113a6.49 6.49 0 1 0-10.412 0c-.496 1.228-1.899 1.14-2.341-.113A7.977 7.977 0 0 1 0 10zm8-7a7 7 0 0 0-6.603 9.329c.203.575.923.65 1.187.1a7.49 7.49 0 1 1 10.832 0c.264.55.984.475 1.187-.1A7 7 0 0 0 8 3z"/>
                                </svg>
                            </span>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>

                    @if (Route::has('activities.index') && auth()->user()->isUser())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                                <span class="nav-icon">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar-event" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                    </svg>
                                </span>
                                <span class="nav-link-text">Activities</span>
                            </a>
                        </li>
                    @endif

                    @if (Route::has('my-registrations.index') && auth()->user()->isUser())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('my-registrations.*') ? 'active' : '' }}" href="{{ route('my-registrations.index') }}">
                                <span class="nav-icon">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-card-list" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M14.5 3h-13a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                        <path fill-rule="evenodd" d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5z"/>
                                        <circle cx="3.5" cy="5.5" r=".5"/>
                                        <circle cx="3.5" cy="8" r=".5"/>
                                        <circle cx="3.5" cy="10.5" r=".5"/>
                                    </svg>
                                </span>
                                <span class="nav-link-text">My Registrations</span>
                            </a>
                        </li>
                    @endif

                    @if (Route::has('preferences.edit') && auth()->user()->isUser())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('preferences.*') ? 'active' : '' }}" href="{{ route('preferences.edit') }}">
                                <span class="nav-icon">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-sliders" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
                                    </svg>
                                </span>
                                <span class="nav-link-text">Preferences</span>
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.registrations.index') && auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}" href="{{ route('admin.registrations.index') }}">
                                <span class="nav-icon">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                        <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                        <path d="M9.5 1a.5.5 0 0 1 .5.5v1A.5.5 0 0 1 9.5 3h-3a.5.5 0 0 1-.5-.5v-1A.5.5 0 0 1 6.5 1h3z"/>
                                    </svg>
                                </span>
                                <span class="nav-link-text">Admin Registrations</span>
                            </a>
                        </li>
                    @endif

                    @if (Route::has('admin.users.index') && auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                <span class="nav-icon">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-people" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 7c0 1.105-1.12 2-2.5 2S8 8.105 8 7s1.12-2 2.5-2S13 5.895 13 7zM5.5 9C6.88 9 8 8.105 8 7s-1.12-2-2.5-2S3 5.895 3 7s1.12 2 2.5 2z"/>
                                        <path d="M2 13c0-1.657 2.015-3 4.5-3S11 11.343 11 13v1H2v-1zm9 1v-1c0-.77-.231-1.468-.64-2.063C12.32 11.23 14 12.45 14 14v0h-3z"/>
                                    </svg>
                                </span>
                                <span class="nav-link-text">Users</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                                </svg>
                            </span>
                            <span class="nav-link-text">Account</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>
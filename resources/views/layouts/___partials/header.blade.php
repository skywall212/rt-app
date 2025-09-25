<!--begin::Header-->
<div id="kt_app_header" class="app-header">
    <!--begin::Header container-->
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
        <!--begin::Header logo (mobile)-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-2">
            <!--begin::Mobile logo-->
            <a href="{{ url('/') }}">
                <h4 class="text-white fw-bold m-0">{{ config('app.name', 'Aplikasi RT') }}</h4>
            </a>
            <!--end::Mobile logo-->
        </div>
        <!--end::Header logo-->

        <!--begin::Page title (opsional)-->
        <div class="d-flex align-items-center flex-grow-1">
            <h3 class="text-dark fw-bold m-0">@yield('title', 'Dashboard')</h3>
        </div>
        <!--end::Page title-->

        <!--begin::Navbar-->
        <div class="app-navbar flex-shrink-0">
            <!--begin::User menu-->
            <div class="app-navbar-item ms-1 ms-lg-3">
                <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
                     data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                     data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <img src="{{ asset('metronic/assets/media/avatars/blank.png') }}" alt="user" />
                </div>

                <!--begin::User dropdown menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800
                            menu-state-bg menu-state-primary fw-semibold py-4 fs-6 w-275px"
                     data-kt-menu="true">

                    <!--begin::User info-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img src="{{ asset('metronic/assets/media/avatars/blank.png') }}" alt="avatar" />
                            </div>
                            <!--end::Avatar-->

                            <!--begin::User details-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    {{ Auth::user()->name ?? 'Pengguna' }}
                                </div>
                                <span class="fw-semibold text-muted fs-7">{{ Auth::user()->role ?? '-' }}</span>
                            </div>
                            <!--end::User details-->
                        </div>
                    </div>
                    <!--end::User info-->

                    <div class="separator my-2"></div>

                    <!--begin::Logout-->
                    <div class="menu-item px-5">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger p-0 m-0">
                                Logout
                            </button>
                        </form>
                    </div>
                    <!--end::Logout-->
                </div>
                <!--end::User dropdown menu-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header container-->
</div>
<!--end::Header-->

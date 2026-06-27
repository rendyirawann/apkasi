{{-- Demo 11 Horizontal Menu Bar --}}
<div class="header-menu flex-column flex-lg-row"
    data-kt-drawer="true" data-kt-drawer-name="header-menu"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_header_menu_toggle"
    data-kt-swapper="true" data-kt-swapper-mode="prepend"
    data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">

    <!--begin::Menu-->
    <div class="menu menu-rounded menu-column menu-lg-row menu-root-here-bg-desktop menu-active-bg menu-state-primary menu-title-gray-800 menu-arrow-gray-500 align-items-stretch flex-grow-1 my-5 my-lg-0 px-2 px-lg-0 fw-semibold fs-6"
        id="#kt_header_menu" data-kt-menu="true">

        <!--begin::Dashboard-->
        <div class="menu-item me-0 me-lg-2 {{ request()->routeIs('dashboard') ? 'here show menu-here-bg' : '' }}">
            <a class="menu-link py-3" href="{{ route('dashboard') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-element-11 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </div>
        <!--end::Dashboard-->

        <!--begin::User Management-->
        @role('Superadmin|superadmin')
        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ request()->is('admin/users*') || request()->is('admin/roles*') ? 'here show menu-here-bg' : '' }}">
            <span class="menu-link py-3">
                <span class="menu-icon">
                    <i class="ki-duotone ki-people fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                </span>
                <span class="menu-title">User Management</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown py-4 w-200px">
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-user fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Users</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-shield-tick fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Roles & Permissions</span>
                    </a>
                </div>
            </div>
        </div>
        @endrole
        <!--end::User Management-->

        <!--begin::Data Master-->
        @can('view_data_master')
        @php $isDataMaster = request()->is('admin/gedung*') || request()->is('admin/hotels*') || request()->is('admin/pics*') || request()->is('admin/destinasi*') || request()->is('admin/rentals*') || request()->is('admin/rental-banners*') || request()->is('admin/kuliner*') || request()->is('admin/rekayasa*') || request()->is('admin/rundown*'); @endphp
        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ $isDataMaster ? 'here show menu-here-bg' : '' }}">
            <span class="menu-link py-3">
                <span class="menu-icon">
                    <i class="ki-duotone ki-home-2 fs-3"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <span class="menu-title">Data Master</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown py-4 w-200px">
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/gedung*') ? 'active' : '' }}" href="{{ route('gedung.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-bank fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Gedung</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/rundown*') ? 'active' : '' }}" href="{{ route('rundown.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-calendar fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Rundown Kegiatan</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/hotels*') ? 'active' : '' }}" href="{{ route('hotels.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-home-2 fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Hotel</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/destinasi*') ? 'active' : '' }}" href="{{ route('destinasi.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-geolocation fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Destinasi Wisata</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/rentals*') ? 'active' : '' }}" href="{{ route('rentals.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-car fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Rental Mobil</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/rental-banners*') ? 'active' : '' }}" href="{{ route('rental-banners.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-picture fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Banner Rental</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/kuliner*') ? 'active' : '' }}" href="{{ route('kuliner.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-cup fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">Kuliner</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/rekayasa*') ? 'active' : '' }}" href="{{ route('rekayasa.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-map fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                        <span class="menu-title">Rekayasa Lalu Lintas</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/pics*') ? 'active' : '' }}" href="{{ route('pics.index') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-user-tick fs-4"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">PIC</span>
                    </a>
                </div>
            </div>
        </div>
        @endcan
        <!--end::Data Master-->

        <!--begin::Landing Page-->
        @can('landing.edit')
        <div class="menu-item me-0 me-lg-2 {{ request()->is('admin/landing*') ? 'here show menu-here-bg' : '' }}">
            <a class="menu-link py-3" href="{{ route('landing.index') }}">
                <span class="menu-icon"><i class="ki-duotone ki-picture fs-3"><span class="path1"></span><span class="path2"></span></i></span>
                <span class="menu-title">Landing Page</span>
            </a>
        </div>
        @endcan
        <!--end::Landing Page-->

        <!--begin::Settings-->
        @role('Superadmin|superadmin')
        <div class="menu-item me-0 me-lg-2 {{ request()->routeIs('settings.*') ? 'here show menu-here-bg' : '' }}">
            <a class="menu-link py-3" href="{{ route('settings.index') }}">
                <span class="menu-icon">
                    <i class="ki-duotone ki-setting-2 fs-3"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <span class="menu-title">Settings</span>
            </a>
        </div>
        @endrole
        <!--end::Settings-->

    </div>
    <!--end::Menu-->
</div>
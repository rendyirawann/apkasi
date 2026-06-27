	<div class="d-flex align-items-center flex-shrink-0">
								<!--begin::Menu Search-->
								<div id="kt_menu_search" class="header-search d-flex align-items-center w-lg-250px" data-kt-menu-trigger="click" data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end">
									<!--begin::Tablet and mobile search toggle-->
									<div class="search-toggle-mobile d-flex d-lg-none align-items-center" id="menu_search_toggle_mobile">
										<div class="d-flex btn btn-icon btn-color-gray-700 btn-active-color-primary btn-outline btn-active-bg-light w-30px h-30px w-lg-40px h-lg-40px">
											<i class="ki-duotone ki-magnifier fs-1 text-gray-700 fs-2">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</div>
									</div>
									<!--end::Tablet and mobile search toggle-->
									<!--begin::Form-->
									<div class="d-none d-lg-block w-100 position-relative mb-2 mb-lg-0">
										<!--begin::Icon-->
										<i class="ki-duotone ki-magnifier fs-2 text-gray-700 position-absolute top-50 translate-middle-y ms-4">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										<!--end::Icon-->
										<!--begin::Input-->
										<input type="text" id="menu_search_input" class="form-control bg-transparent ps-13 fs-7 h-40px" name="menu_search" value="" placeholder="Cari menu..." autocomplete="off" />
										<!--end::Input-->
										<!--begin::Reset-->
										<span class="btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-4" id="menu_search_clear">
											<i class="ki-duotone ki-cross fs-2 fs-lg-1 me-0">
												<span class="path1"></span>
												<span class="path2"></span>
											</i>
										</span>
										<!--end::Reset-->
									</div>
									<!--end::Form-->
									<!--begin::Menu-->
									<div id="menu_search_results" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-300px w-md-350px">
										<!--begin::Empty / Hint-->
										<div id="menu_search_empty" class="text-center px-3 py-5 d-none">
											<div class="pb-3">
												<i class="ki-duotone ki-search-list fs-3x text-gray-400 opacity-75">
													<span class="path1"></span>
													<span class="path2"></span>
													<span class="path3"></span>
												</i>
											</div>
											<div class="text-gray-600 fs-6 fw-semibold">Tidak ada menu ditemukan</div>
											<div class="text-muted fs-7">Coba kata kunci lain</div>
										</div>
										<!--end::Empty / Hint-->
										<!--begin::Hint-->
										<div id="menu_search_hint" class="px-3">
											<div class="text-muted fs-7 fw-semibold text-uppercase px-3 pb-2">Menu Cepat</div>
											<div id="menu_search_list"></div>
										</div>
										<!--end::Hint-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Menu Search-->
								<!--begin::Theme mode-->
								<div class="d-flex align-items-center ms-3 ms-lg-4">
									<!--begin::Menu toggle-->
									<a href="#" class="btn btn-icon btn-color-gray-700 btn-active-color-primary btn-outline btn-active-bg-light w-30px h-30px w-lg-40px h-lg-40px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-night-day theme-light-show fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
											<span class="path6"></span>
											<span class="path7"></span>
											<span class="path8"></span>
											<span class="path9"></span>
											<span class="path10"></span>
										</i>
										<i class="ki-duotone ki-moon theme-dark-show fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<!--begin::Menu toggle-->
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-night-day fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
														<span class="path5"></span>
														<span class="path6"></span>
														<span class="path7"></span>
														<span class="path8"></span>
														<span class="path9"></span>
														<span class="path10"></span>
													</i>
												</span>
												<span class="menu-title">Light</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-moon fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span>
												<span class="menu-title">Dark</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-screen fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
													</i>
												</span>
												<span class="menu-title">System</span>
											</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Theme mode-->
								<!--begin::User-->
								<div class="d-flex align-items-center ms-3 ms-lg-4" id="kt_header_user_menu_toggle">
									<!--begin::Menu- wrapper-->
									<!--begin::User icon(remove this button to use user avatar as menu toggle)-->
									<div class="btn btn-icon btn-color-gray-700 btn-active-color-primary btn-outline btn-active-bg-light w-30px h-30px w-lg-40px h-lg-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-user fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</div>
									<!--end::User icon-->
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													@auth
													<img alt="Logo" src="{{ asset('assets/media/avatars/' . (Auth::user()->avatar ?? 'default.png')) }}" />
													@endauth
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													@auth
													<div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
													<span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">{{ Auth::user()->roles->first()->name ?? 'User' }}</span></div>
													<a href="#" class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
													@endauth
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="{{ route('account.index') }}" class="menu-link px-5">Profil Saya</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<form method="POST" action="{{ route('logout') }}" id="logout-form">
												@csrf
												<a href="#" class="menu-link px-5" onclick="confirmLogout(event)">Sign Out</a>
											</form>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::User -->
								<!--begin::Sidebar Toggler-->
								<!--end::Sidebar Toggler-->
							</div>

<!--begin::Menu Search Script-->
<script>
	(function () {
		// Menu items rendered server-side with the same permission guards as the sidebar.
		var menuItems = [
			{ label: 'Dashboard', url: '{{ route('dashboard') }}', icon: 'ki-element-11', group: 'Umum' },
			@role('Superadmin|superadmin')
			{ label: 'Users', url: '{{ route('users.index') }}', icon: 'ki-user', group: 'User Management' },
			{ label: 'Roles & Permissions', url: '{{ route('roles.index') }}', icon: 'ki-shield-tick', group: 'User Management' },
			@endrole
			@can('view_data_master')
			{ label: 'Gedung', url: '{{ route('gedung.index') }}', icon: 'ki-bank', group: 'Data Master' },
			{ label: 'Rundown Kegiatan', url: '{{ route('rundown.index') }}', icon: 'ki-calendar', group: 'Data Master' },
			{ label: 'Hotel', url: '{{ route('hotels.index') }}', icon: 'ki-home-2', group: 'Data Master' },
			{ label: 'Destinasi Wisata', url: '{{ route('destinasi.index') }}', icon: 'ki-geolocation', group: 'Data Master' },
			{ label: 'Rental Mobil', url: '{{ route('rentals.index') }}', icon: 'ki-car', group: 'Data Master' },
			{ label: 'Banner Rental', url: '{{ route('rental-banners.index') }}', icon: 'ki-picture', group: 'Data Master' },
			{ label: 'Kuliner', url: '{{ route('kuliner.index') }}', icon: 'ki-cup', group: 'Data Master' },
			{ label: 'Rekayasa Lalu Lintas', url: '{{ route('rekayasa.index') }}', icon: 'ki-map', group: 'Data Master' },
			{ label: 'PIC', url: '{{ route('pics.index') }}', icon: 'ki-user-tick', group: 'Data Master' },
			@endcan
			@can('landing.edit')
			{ label: 'Landing Page', url: '{{ route('landing.index') }}', icon: 'ki-picture', group: 'Konten' },
			@endcan
			{ label: 'Profil Saya', url: '{{ route('account.index') }}', icon: 'ki-profile-circle', group: 'Akun' },
			{ label: 'Security', url: '{{ route('my-security.index') }}', icon: 'ki-lock', group: 'Akun' },
			{ label: 'Activity', url: '{{ route('my-activity.index') }}', icon: 'ki-pulse', group: 'Akun' },
			{ label: 'Login Sessions', url: '{{ route('my-login-session.index') }}', icon: 'ki-devices', group: 'Akun' },
			@role('Superadmin|superadmin')
			{ label: 'Settings', url: '{{ route('settings.index') }}', icon: 'ki-setting-2', group: 'Sistem' },
			{ label: 'Activity Log', url: '{{ url('admin/log-activity') }}', icon: 'ki-notepad', group: 'Sistem' },
			@endrole
		];

		function escapeHtml(str) {
			return String(str).replace(/[&<>"']/g, function (c) {
				return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
			});
		}

		function renderItems(items) {
			var html = '';
			for (var i = 0; i < items.length; i++) {
				var it = items[i];
				html += '<div class="menu-item px-3">'
					+ '<a class="menu-link px-3 menu-search-link" data-url="' + escapeHtml(it.url) + '">'
					+ '<span class="menu-icon"><i class="ki-duotone ' + escapeHtml(it.icon) + ' fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>'
					+ '<span class="menu-title d-flex flex-column">'
					+ '<span>' + escapeHtml(it.label) + '</span>'
					+ '<span class="fs-8 text-muted">' + escapeHtml(it.group) + '</span>'
					+ '</span>'
					+ '</a>'
					+ '</div>';
			}
			return html;
		}

		document.addEventListener('DOMContentLoaded', function () {
			var input = document.getElementById('menu_search_input');
			var listEl = document.getElementById('menu_search_list');
			var emptyEl = document.getElementById('menu_search_empty');
			var hintEl = document.getElementById('menu_search_hint');
			var clearEl = document.getElementById('menu_search_clear');

			if (!input || !listEl) {
				return;
			}

			// Initial hint list (all permitted items).
			listEl.innerHTML = renderItems(menuItems);

			function bindLinks() {
				var links = document.querySelectorAll('#menu_search_results .menu-search-link');
				for (var i = 0; i < links.length; i++) {
					links[i].addEventListener('click', function (e) {
						e.preventDefault();
						var url = this.getAttribute('data-url');
						if (url) {
							window.location.href = url;
						}
					});
				}
			}
			bindLinks();

			function filter() {
				var q = (input.value || '').trim().toLowerCase();

				if (clearEl) {
					clearEl.classList.toggle('d-none', q.length === 0);
				}

				if (q.length === 0) {
					listEl.innerHTML = renderItems(menuItems);
					listEl.classList.remove('d-none');
					if (hintEl) hintEl.classList.remove('d-none');
					if (emptyEl) emptyEl.classList.add('d-none');
					bindLinks();
					return;
				}

				var matches = menuItems.filter(function (it) {
					var path = '';
					try { path = new URL(it.url, window.location.origin).pathname.toLowerCase(); }
					catch (err) { path = String(it.url).toLowerCase(); }
					return it.label.toLowerCase().indexOf(q) !== -1
						|| String(it.url).toLowerCase().indexOf(q) !== -1
						|| path.indexOf(q) !== -1
						|| it.group.toLowerCase().indexOf(q) !== -1;
				});

				if (matches.length === 0) {
					listEl.innerHTML = '';
					listEl.classList.add('d-none');
					if (hintEl) hintEl.classList.add('d-none');
					if (emptyEl) emptyEl.classList.remove('d-none');
					return;
				}

				listEl.innerHTML = renderItems(matches);
				listEl.classList.remove('d-none');
				if (hintEl) hintEl.classList.remove('d-none');
				if (emptyEl) emptyEl.classList.add('d-none');
				bindLinks();
			}

			input.addEventListener('keyup', filter);
			input.addEventListener('focus', function () {
				if (typeof KTMenu !== 'undefined') {
					var menuEl = document.getElementById('kt_menu_search');
					var menu = KTMenu.getInstance(menuEl);
					if (menu) { menu.show(menuEl); }
				}
			});
			input.addEventListener('click', function () {
				if (typeof KTMenu !== 'undefined') {
					var menuEl = document.getElementById('kt_menu_search');
					var menu = KTMenu.getInstance(menuEl);
					if (menu) { menu.show(menuEl); }
				}
			});

			if (clearEl) {
				clearEl.addEventListener('click', function () {
					input.value = '';
					filter();
					input.focus();
				});
			}
		});
	})();

	function confirmLogout(e) {
		e.preventDefault();
		if (typeof Swal === 'undefined') {
			if (confirm('Keluar dari sesi ini?')) document.getElementById('logout-form').submit();
			return;
		}
		Swal.fire({
			text: 'Yakin ingin keluar dari sesi ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Ya, keluar',
			cancelButtonText: 'Batal',
			buttonsStyling: false,
			customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' }
		}).then(function (r) {
			if (r.isConfirmed) document.getElementById('logout-form').submit();
		});
	}
</script>
<!--end::Menu Search Script-->

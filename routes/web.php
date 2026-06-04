<?php

use Illuminate\Support\Facades\Route;

// Import Controller Dashboard
use App\Http\Controllers\Backend\Dashboard\DashboardAdminController; // Sesuaikan jika nama controllernya beda
// Import Controller PROFILE
use App\Http\Controllers\Backend\MyProfile\AccountController;
use App\Http\Controllers\Backend\MyProfile\ProfileController;
use App\Http\Controllers\Backend\MyProfile\SecurityController;
use App\Http\Controllers\Backend\MyProfile\ActivityController;
use App\Http\Controllers\Backend\MyProfile\LoginSessionController;

// Import Controller USER MANAGEMENT
use App\Http\Controllers\Backend\UserManagement\UserController;
use App\Http\Controllers\Backend\UserManagement\RoleController;

// Import Controller HELP/LOG
use App\Http\Controllers\Backend\Help\LogActivityController;
use App\Http\Controllers\Backend\Settings\SettingController;

// Import Controller DATA MASTER (Hotel, PIC, Destinasi Wisata)
use App\Http\Controllers\Backend\DataMaster\HotelController;
use App\Http\Controllers\Backend\DataMaster\GedungController;
use App\Http\Controllers\Backend\DataMaster\PicController;
use App\Http\Controllers\Backend\DataMaster\DestinasiWisataController;
use App\Http\Controllers\Backend\DataMaster\RentalController;
use App\Http\Controllers\Backend\DataMaster\RundownController;
use App\Http\Controllers\Backend\Settings\LandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Halaman Depan (Landing Page & Panduan APKASI Deli Serdang) — throttle per IP
// 120 request/menit cukup longgar utk kantor ber-NAT, tapi menahan scraper/abuse.
Route::middleware('throttle:120,1')->group(function () {
    Route::get('/', [\App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
    Route::get('/panduan', [\App\Http\Controllers\Frontend\HomeController::class, 'guide'])->name('guide');
    Route::get('/peta-hotel', [\App\Http\Controllers\Frontend\Peta\PetaHotelController::class, 'index'])->name('peta-hotel');
});
// Endpoint AJAX/JSON (sering dipanggil saat paginasi) — limit lebih longgar
Route::middleware('throttle:240,1')->group(function () {
    // Daftar tempat paginasi (AJAX) untuk list di /peta-hotel
    Route::get('/peta-hotel/list', [\App\Http\Controllers\Frontend\Peta\PetaHotelController::class, 'list'])->name('peta-hotel.list');
    // Endpoint JSON data tempat (dipakai SPA React di folder frontend/ via proxy Vite)
    Route::get('/api/places', [\App\Http\Controllers\Frontend\Peta\PetaHotelController::class, 'json'])->name('api.places');
});

Route::any('/dine-sync-pos', function () {
    return redirect('/admin/login');
});



// --- TARUH DEBUG DISINI (DI LUAR MIDDLEWARE AUTH) ---
Route::get('/admin/debug-session', function () {
    $user = auth()->user();

    // Cek manual apakah tabel bans error
    $bannedStatus = 'Tidak dicek';
    $error = null;

    if ($user) {
        try {
            // Kita coba panggil paksa relasi banned-nya
            $bannedStatus = $user->isBanned() ? 'YA TER-BANNED' : 'AMAN';
        } catch (\Exception $e) {
            $bannedStatus = 'ERROR SAAT CEK BANNED: ' . $e->getMessage();
        }
    }

    return [
        'status_login' => $user ? 'SUDAH LOGIN' : 'BELUM LOGIN / SESI HILANG',
        'user_id' => $user?->id,
        'user_name' => $user?->name,
        'session_id' => session()->getId(),
        'driver_session' => config('session.driver'),
        'cek_banned' => $bannedStatus,
    ];
});

// NOTE: Route /login POST dihapus dari sini karena sudah ada di auth.php
// agar tidak bentrok "Route [login] defined twice".

// Group Middleware untuk User yang sudah Login
// Kita tambahkan 'forbid-banned-user' agar user yang di-banned tidak bisa akses
Route::middleware(['auth', 'forbid-banned-user'])->group(function () {

    // --- SHARED ROLE ROUTES (generate-permissions helper, select) ---
    Route::post('/admin/roles/generate-permissions', [RoleController::class, 'generatePermissions'])->name('roles.generate');
    Route::get('/admin/select/role', [RoleController::class, 'select'])->name('role.select');

    // --- DASHBOARD (accessible by ALL authenticated roles) ---
    Route::get('/admin/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // --- MY ACCOUNT / PROFILE (accessible by ALL authenticated users) ---
    Route::get('/admin/my-account', [AccountController::class, 'index'])->name('account.index');
    Route::get('/admin/my-account/{id}/avatar', [AccountController::class, 'editAvatar'])->name('avatar-edit');
    Route::post('/admin/my-account/{id}/update-avatar', [AccountController::class, 'updateAvatar'])->name('avatar-update');

    Route::resource('/admin/my-profile', ProfileController::class);
    Route::resource('/admin/my-security', SecurityController::class);
    Route::post('/admin/my-security', [SecurityController::class, 'store'])->name('change.password');
    Route::post('/admin/my-security/logout-other-devices', [SecurityController::class, 'logoutOtherDevices'])->name('security.logout-other-devices');

    Route::get('/admin/my-activity', [ActivityController::class, 'index'])->name('my-activity.index');
    Route::get('/admin/mget-my-activity', [ActivityController::class, 'getActivity'])->name('get-my-activity');

    Route::get('/admin/mmy-login-session', [LoginSessionController::class, 'index'])->name('my-login-session.index');
    Route::get('/admin/mget-my-login-session', [LoginSessionController::class, 'getLoginSession'])->name('get-my-login-session');

    // --- SETTINGS (accessible by ALL authenticated users) ---
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/admin/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // --- DEBUG/CHECK AUTH ---
    Route::get('/admin/check-auth', function () {
        $u = auth()->user();
        return [
            'user' => $u,
            'roles' => $u?->getRoleNames(),
            'permissions' => $u?->getAllPermissions()->pluck('name'),
        ];
    });
    Route::get('/admin/debug-session', function () {
        $user = auth()->user();
        return ['user' => $user?->name, 'roles' => $user?->getRoleNames()];
    });

    // ====================================================
    // RESOURCES (User & Role Mgmt): view_resources — Superadmin only
    // ====================================================
    Route::middleware('can:view_resources')->group(function () {
        Route::resource('/admin/users', UserController::class);
        Route::get('/admin/get-datauser', [UserController::class, 'getDataUsers'])->name('get-users');
        Route::post('/admin/users/mass-delete', [UserController::class, 'massDelete'])->name('users.mass-delete');
        Route::get('/admin/get-user-show-log/{id}', [UserController::class, 'getLoginSession'])->name('get-user-show-log');
        Route::get('/admin/get-user-show-log-activity/{id}', [UserController::class, 'getActivity'])->name('get-user-show-log-activity');
        Route::post('/admin/users/{id}/ban', [UserController::class, 'ban'])->name('users.ban');
        Route::post('/admin/users/{id}/unban', [UserController::class, 'unban'])->name('users.unban');

        Route::resource('/admin/roles', RoleController::class);
        Route::get('/admin/get-datarole', [RoleController::class, 'getDataRoles'])->name('get-datarole');
        Route::post('/admin/roles/mass-delete', [RoleController::class, 'massDelete'])->name('roles.mass-delete');
    });

    // ====================================================
    // HELP (Log Activity): view_help — Superadmin, admin
    // ====================================================
    Route::middleware('can:view_help')->group(function () {
        Route::resource('/admin/log-activity', LogActivityController::class);
        Route::get('/admin/get-datalogactivity', [LogActivityController::class, 'getDataLogActivity'])->name('get-datalogactivity');
    });

    // ====================================================
    // LANDING PAGE CMS: landing.edit
    // ====================================================
    Route::middleware('can:landing.edit')->group(function () {
        Route::get('/admin/landing', [LandingController::class, 'index'])->name('landing.index');
        Route::post('/admin/landing', [LandingController::class, 'update'])->name('landing.update');
        Route::post('/admin/landing/logo', [LandingController::class, 'logoStore'])->name('landing.logo.store');
        Route::delete('/admin/landing/logo/{id}', [LandingController::class, 'logoDestroy'])->name('landing.logo.destroy');
        Route::post('/admin/landing/faq', [LandingController::class, 'faqSync'])->name('landing.faq.sync');
    });

    // ====================================================
    // DATA MASTER (Hotel & PIC): view_data_master
    // ====================================================
    Route::middleware('can:view_data_master')->group(function () {
        // Gedung / Venue
        Route::get('/admin/gedung', [GedungController::class, 'index'])->name('gedung.index');
        Route::get('/admin/gedung/data', [GedungController::class, 'data'])->name('gedung.data');
        Route::post('/admin/gedung', [GedungController::class, 'store'])->name('gedung.store');
        Route::get('/admin/gedung/{id}', [GedungController::class, 'show'])->name('gedung.show');
        Route::get('/admin/gedung/{id}/edit', [GedungController::class, 'edit'])->name('gedung.edit');
        Route::put('/admin/gedung/{id}', [GedungController::class, 'update'])->name('gedung.update');
        Route::delete('/admin/gedung/{id}', [GedungController::class, 'destroy'])->name('gedung.destroy');

        // Hotels
        Route::get('/admin/hotels', [HotelController::class, 'index'])->name('hotels.index');
        Route::get('/admin/hotels/data', [HotelController::class, 'data'])->name('hotels.data');
        Route::post('/admin/hotels', [HotelController::class, 'store'])->name('hotels.store');
        Route::get('/admin/hotels/{id}', [HotelController::class, 'show'])->name('hotels.show');
        Route::get('/admin/hotels/{id}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
        Route::put('/admin/hotels/{id}', [HotelController::class, 'update'])->name('hotels.update');
        Route::delete('/admin/hotels/{id}', [HotelController::class, 'destroy'])->name('hotels.destroy');

        // PICs
        Route::get('/admin/pics', [PicController::class, 'index'])->name('pics.index');
        Route::get('/admin/pics/data', [PicController::class, 'data'])->name('pics.data');
        Route::post('/admin/pics', [PicController::class, 'store'])->name('pics.store');
        Route::get('/admin/pics/{id}', [PicController::class, 'show'])->name('pics.show');
        Route::get('/admin/pics/{id}/edit', [PicController::class, 'edit'])->name('pics.edit');
        Route::put('/admin/pics/{id}', [PicController::class, 'update'])->name('pics.update');
        Route::delete('/admin/pics/{id}', [PicController::class, 'destroy'])->name('pics.destroy');

        // Destinasi Wisata
        Route::get('/admin/destinasi', [DestinasiWisataController::class, 'index'])->name('destinasi.index');
        Route::get('/admin/destinasi/data', [DestinasiWisataController::class, 'data'])->name('destinasi.data');
        Route::post('/admin/destinasi', [DestinasiWisataController::class, 'store'])->name('destinasi.store');
        Route::get('/admin/destinasi/{id}', [DestinasiWisataController::class, 'show'])->name('destinasi.show');
        Route::get('/admin/destinasi/{id}/edit', [DestinasiWisataController::class, 'edit'])->name('destinasi.edit');
        Route::put('/admin/destinasi/{id}', [DestinasiWisataController::class, 'update'])->name('destinasi.update');
        Route::delete('/admin/destinasi/{id}', [DestinasiWisataController::class, 'destroy'])->name('destinasi.destroy');

        // Rental Mobil
        Route::get('/admin/rentals', [RentalController::class, 'index'])->name('rentals.index');
        Route::get('/admin/rentals/data', [RentalController::class, 'data'])->name('rentals.data');
        Route::post('/admin/rentals', [RentalController::class, 'store'])->name('rentals.store');
        Route::get('/admin/rentals/{id}', [RentalController::class, 'show'])->name('rentals.show');
        Route::get('/admin/rentals/{id}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
        Route::put('/admin/rentals/{id}', [RentalController::class, 'update'])->name('rentals.update');
        Route::delete('/admin/rentals/{id}', [RentalController::class, 'destroy'])->name('rentals.destroy');

        // Rundown Kegiatan
        Route::get('/admin/rundown', [RundownController::class, 'index'])->name('rundown.index');
        Route::get('/admin/rundown/data', [RundownController::class, 'data'])->name('rundown.data');
        Route::post('/admin/rundown', [RundownController::class, 'store'])->name('rundown.store');
        Route::get('/admin/rundown/{id}', [RundownController::class, 'show'])->name('rundown.show');
        Route::get('/admin/rundown/{id}/edit', [RundownController::class, 'edit'])->name('rundown.edit');
        Route::put('/admin/rundown/{id}', [RundownController::class, 'update'])->name('rundown.update');
        Route::delete('/admin/rundown/{id}', [RundownController::class, 'destroy'])->name('rundown.destroy');
    });
});

// Load Routes Authentication (Login, Register, Reset Password)
require __DIR__ . '/auth.php';

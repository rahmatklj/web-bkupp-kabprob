<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes - Portal DKUPP Kabupaten Probolinggo
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/informasi', [PublicController::class, 'informasi'])->name('informasi');
Route::get('/berita', [PublicController::class, 'informasi'])->name('berita');
Route::get('/informasi/{slug}', [PublicController::class, 'newsDetail'])->name('news.detail');
Route::get('/berita/{slug}', [PublicController::class, 'newsDetail'])->name('berita.detail');
Route::get('/halaman/{slug}', [PublicController::class, 'page'])->name('page');

// DKUPP Specific Public Routes
Route::get('/katalog-umkm', [PublicController::class, 'umkmKatalog'])->name('umkm.katalog');
Route::get('/katalog-umkm/{slug}', [PublicController::class, 'umkmDetail'])->name('umkm.detail');
Route::get('/harga-pasar', [PublicController::class, 'hargaPasar'])->name('harga.pasar');
Route::get('/layanan', [PublicController::class, 'layanan'])->name('layanan');
Route::get('/layanan/{slug}', [PublicController::class, 'layananDetail'])->name('layanan.detail');
Route::get('/dokumen', [PublicController::class, 'dokumen'])->name('dokumen');
Route::get('/dokumen/view/{id}', [PublicController::class, 'viewDokumen'])->name('dokumen.view');
Route::get('/dokumen/download/{id}', [PublicController::class, 'downloadDokumen'])->name('dokumen.download');
Route::get('/kontak', [PublicController::class, 'kontak'])->name('kontak');
Route::post('/kontak', [PublicController::class, 'storeContact'])->name('kontak.store');
Route::get('/lapor', [PublicController::class, 'lapor'])->name('lapor');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('galeri');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/captcha-refresh', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (CMS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // 1. DOKUMEN CRUD
    Route::get('/documents', [AdminController::class, 'documents'])->name('documents');
    Route::post('/documents', [AdminController::class, 'documentStore'])->name('documents.store');
    Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('documents.update');
    Route::delete('/documents/{id}', [AdminController::class, 'documentDestroy'])->name('documents.destroy');

    // 2. INFORMASI / BERITA CRUD
    Route::get('/news', [AdminController::class, 'news'])->name('news');
    Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
    Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [AdminController::class, 'newsDestroy'])->name('news.destroy');

    // 3. UMKM PRODUCTS CRUD (SIMADU SAE)
    Route::get('/umkm', [AdminController::class, 'umkm'])->name('umkm');
    Route::post('/umkm', [AdminController::class, 'umkmStore'])->name('umkm.store');
    Route::post('/umkm/settings', [AdminController::class, 'umkmSettingsStore'])->name('umkm.settings');
    Route::put('/umkm/{id}', [AdminController::class, 'umkmUpdate'])->name('umkm.update');
    Route::delete('/umkm/{id}', [AdminController::class, 'umkmDestroy'])->name('umkm.destroy');

    // 4. MARKET PRICES CRUD (Harga Bahan Pokok)
    Route::get('/market-prices', [AdminController::class, 'marketPrices'])->name('market-prices');
    Route::post('/market-prices', [AdminController::class, 'marketPricesStore'])->name('market-prices.store');
    Route::put('/market-prices/{id}', [AdminController::class, 'marketPricesUpdate'])->name('market-prices.update');
    Route::delete('/market-prices/{id}', [AdminController::class, 'marketPricesDestroy'])->name('market-prices.destroy');

    // 4b. PPID LINK CRUD
    Route::get('/ppid', [AdminController::class, 'ppid'])->name('ppid');
    Route::post('/ppid', [AdminController::class, 'ppidUpdate'])->name('ppid.update');

    Route::get('/maklumat', [AdminController::class, 'maklumat'])->name('maklumat');
    Route::post('/maklumat', [AdminController::class, 'maklumatUpdate'])->name('maklumat.update');

    Route::get('/qr-code', [AdminController::class, 'qrCode'])->name('qr-code');
    Route::post('/qr-code', [AdminController::class, 'qrCodeUpdate'])->name('qr-code.update');

    // 5. STRUKTUR ORGANISASI CRUD
    Route::get('/org-members', [AdminController::class, 'orgMembers'])->name('org-members');
    Route::post('/org-members', [AdminController::class, 'orgMemberStore'])->name('org-members.store');
    Route::put('/org-members/{id}', [AdminController::class, 'orgMemberUpdate'])->name('org-members.update');
    Route::put('/org-members/{id}/toggle', [AdminController::class, 'orgMemberToggleStatus'])->name('org-members.toggle');
    Route::delete('/org-members/{id}', [AdminController::class, 'orgMemberDestroy'])->name('org-members.destroy');

    // 6. GALERI FOTO & VIDEO CRUD
    Route::get('/gallery', [AdminController::class, 'gallery'])->name('gallery');
    Route::post('/gallery', [AdminController::class, 'galleryStore'])->name('gallery.store');
    Route::delete('/gallery/{id}', [AdminController::class, 'galleryDestroy'])->name('gallery.destroy');

    // RESTRICTED TO SUPER ADMIN ONLY
    Route::middleware(['role:super_admin'])->group(function () {
        
        Route::get('/sliders', [AdminController::class, 'sliders'])->name('sliders');
        Route::post('/sliders', [AdminController::class, 'sliderStore'])->name('sliders.store');
        Route::put('/sliders/{id}', [AdminController::class, 'sliderUpdate'])->name('sliders.update');
        Route::put('/sliders/{id}/toggle', [AdminController::class, 'sliderToggleStatus'])->name('sliders.toggle');
        Route::delete('/sliders/{id}', [AdminController::class, 'sliderDestroy'])->name('sliders.destroy');
        
        Route::get('/menus', [AdminController::class, 'menus'])->name('menus');
        Route::post('/menus', [AdminController::class, 'menuStore'])->name('menus.store');
        Route::put('/menus/{id}', [AdminController::class, 'menuUpdate'])->name('menus.update');
        Route::delete('/menus/{id}', [AdminController::class, 'menuDestroy'])->name('menus.destroy');

        Route::get('/pages', [AdminController::class, 'pages'])->name('pages');
        Route::post('/pages', [AdminController::class, 'pageStore'])->name('pages.store');
        Route::put('/pages/{id}', [AdminController::class, 'pageUpdate'])->name('pages.update');
        Route::put('/pages/{id}/toggle', [AdminController::class, 'pageToggleStatus'])->name('pages.toggle');
        Route::delete('/pages/{id}', [AdminController::class, 'pageDestroy'])->name('pages.destroy');

        Route::get('/widgets', [AdminController::class, 'widgets'])->name('widgets');
        Route::post('/widgets', [AdminController::class, 'widgetStore'])->name('widgets.store');
        Route::put('/widgets/{id}', [AdminController::class, 'widgetUpdate'])->name('widgets.update');
        Route::put('/widgets/{id}/toggle', [AdminController::class, 'widgetToggleStatus'])->name('widgets.toggle');
        Route::delete('/widgets/{id}', [AdminController::class, 'widgetDestroy'])->name('widgets.destroy');

        Route::get('/links', [AdminController::class, 'links'])->name('links');
        Route::post('/links', [AdminController::class, 'linkStore'])->name('links.store');
        Route::put('/links/{id}', [AdminController::class, 'linkUpdate'])->name('links.update');
        Route::delete('/links/{id}', [AdminController::class, 'linkDestroy'])->name('links.destroy');

        Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
        Route::post('/messages/lapor-sp4n', [AdminController::class, 'laporSp4nSettingsStore'])->name('messages.lapor-sp4n');
        Route::put('/messages/{id}', [AdminController::class, 'messageStatus'])->name('messages.status');
        Route::delete('/messages/{id}', [AdminController::class, 'messageDestroy'])->name('messages.destroy');

        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

        Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
        Route::delete('/activity-logs/clear', [AdminController::class, 'activityLogsClear'])->name('activity-logs.clear');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    });

});

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
Route::get('/admin/login', function() { return redirect()->route('login'); });
Route::post('/login', [AuthController::class, 'login']);
Route::post('/force-login', [AuthController::class, 'forceLogin'])->name('force.login');
Route::get('/captcha-refresh', [AuthController::class, 'refreshCaptcha'])->name('captcha.refresh');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (CMS)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // --- OPERATIONAL ROUTES (Accessible by Staff / Anggota & Super Admin) ---
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // 1. LAYANAN PUBLIK CRUD (Standar Pelayanan & SOP)
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/services', [AdminController::class, 'serviceStore'])->name('services.store');
    Route::put('/services/{id}', [AdminController::class, 'serviceUpdate'])->name('services.update');
    Route::put('/services/{id}/toggle', [AdminController::class, 'serviceToggleStatus'])->name('services.toggle');
    Route::delete('/services/{id}', [AdminController::class, 'serviceDestroy'])->name('services.destroy');

    // 2. MONITORING HARGA PASAR CRUD
    Route::get('/market-prices', [AdminController::class, 'marketPrices'])->name('market-prices');
    Route::post('/market-prices', [AdminController::class, 'marketPricesStore'])->name('market-prices.store');
    Route::put('/market-prices/{id}', [AdminController::class, 'marketPricesUpdate'])->name('market-prices.update');
    Route::delete('/market-prices/{id}', [AdminController::class, 'marketPricesDestroy'])->name('market-prices.destroy');

    // 3. INFORMASI / BERITA CRUD
    Route::get('/news', [AdminController::class, 'news'])->name('news');
    Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
    Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');
    Route::put('/news/{id}/toggle', [AdminController::class, 'newsToggleStatus'])->name('news.toggle');
    Route::delete('/news/{id}', [AdminController::class, 'newsDestroy'])->name('news.destroy');

    // 4. GALERI FOTO & VIDEO CRUD
    Route::get('/gallery', [AdminController::class, 'gallery'])->name('gallery');
    Route::post('/gallery', [AdminController::class, 'galleryStore'])->name('gallery.store');
    Route::put('/gallery/{id}', [AdminController::class, 'galleryUpdate'])->name('gallery.update');
    Route::delete('/gallery/{id}', [AdminController::class, 'galleryDestroy'])->name('gallery.destroy');

    // 5. DOKUMEN KINERJA & SAKIP CRUD
    Route::get('/documents', [AdminController::class, 'documents'])->name('documents');
    Route::post('/documents', [AdminController::class, 'documentStore'])->name('documents.store');
    Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('documents.update');
    Route::put('/documents/{id}/toggle', [AdminController::class, 'documentToggleStatus'])->name('documents.toggle');
    Route::delete('/documents/{id}', [AdminController::class, 'documentDestroy'])->name('documents.destroy');

    // 6. MASTER KATEGORI CRUD
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'categoryStore'])->name('categories.store');
    Route::post('/categories/quick-store', [AdminController::class, 'categoryQuickStore'])->name('categories.quick-store');
    Route::post('/categories/quick-destroy', [AdminController::class, 'categoryQuickDestroy'])->name('categories.quick-destroy');
    Route::put('/categories/{id}', [AdminController::class, 'categoryUpdate'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'categoryDestroy'])->name('categories.destroy');

    // 7. LOG AKTIVITAS SISTEM (Accessible by Staff / Anggota & Super Admin)
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
    Route::delete('/activity-logs/clear', [AdminController::class, 'activityLogsClear'])->name('activity-logs.clear');

    // --- RESTRICTED TO SUPER ADMIN ONLY ---
    Route::middleware(['role:super_admin'])->group(function () {
        
        // UNIFIED PORTAL LINKS SETTINGS (Harga Pasar, SIMADU SAE, SP4N LAPOR!, WhatsApp, PPID)
        Route::get('/portal-links', [AdminController::class, 'portalLinks'])->name('portal-links');

        // 6. SIMADU SAE LINK SETTINGS
        Route::get('/umkm', [AdminController::class, 'umkm'])->name('umkm');
        Route::post('/umkm', [AdminController::class, 'umkmStore'])->name('umkm.store');
        Route::post('/umkm/settings', [AdminController::class, 'umkmSettingsStore'])->name('umkm.settings');
        Route::put('/umkm/{id}', [AdminController::class, 'umkmUpdate'])->name('umkm.update');
        Route::delete('/umkm/{id}', [AdminController::class, 'umkmDestroy'])->name('umkm.destroy');

        // 7. PPID LINK SETTINGS
        Route::get('/ppid', [AdminController::class, 'ppid'])->name('ppid');
        Route::post('/ppid', [AdminController::class, 'ppidUpdate'])->name('ppid.update');

        // 7b. SP4N LAPOR! LINK SETTINGS
        Route::get('/sp4n-lapor', [AdminController::class, 'sp4nLapor'])->name('sp4n-lapor');
        Route::post('/sp4n-lapor', [AdminController::class, 'sp4nLaporUpdate'])->name('sp4n-lapor.update');

        // 7c. WHATSAPP PENGADUAN SETTINGS
        Route::get('/whatsapp', [AdminController::class, 'whatsapp'])->name('whatsapp');
        Route::post('/whatsapp', [AdminController::class, 'whatsappUpdate'])->name('whatsapp.update');

        // 8. MAKLUMAT PELAYANAN SETTINGS
        Route::get('/maklumat', [AdminController::class, 'maklumat'])->name('maklumat');
        Route::post('/maklumat', [AdminController::class, 'maklumatUpdate'])->name('maklumat.update');

        // 9. KODE QR & SKM SETTINGS
        Route::get('/qr-code', [AdminController::class, 'qrCode'])->name('qr-code');
        Route::post('/qr-code', [AdminController::class, 'qrCodeUpdate'])->name('qr-code.update');

        // 10. STRUKTUR ORGANISASI CRUD
        Route::get('/org-members', [AdminController::class, 'orgMembers'])->name('org-members');
        Route::post('/org-members', [AdminController::class, 'orgMemberStore'])->name('org-members.store');
        Route::put('/org-members/{id}', [AdminController::class, 'orgMemberUpdate'])->name('org-members.update');
        Route::put('/org-members/{id}/toggle', [AdminController::class, 'orgMemberToggleStatus'])->name('org-members.toggle');
        Route::delete('/org-members/{id}', [AdminController::class, 'orgMemberDestroy'])->name('org-members.destroy');
        
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

        Route::get('/contact-info', [AdminController::class, 'contactInfo'])->name('contact-info');
        Route::post('/contact-info', [AdminController::class, 'contactInfoUpdate'])->name('contact-info.update');

        Route::get('/social-media', [AdminController::class, 'socialMedia'])->name('social-media');
        Route::post('/social-media', [AdminController::class, 'socialMediaUpdate'])->name('social-media.update');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    });

});

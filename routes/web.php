<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/halaman/{slug}', [PublicController::class, 'page'])->name('page.show');
Route::get('/dokumen', [PublicController::class, 'documents'])->name('public.documents');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('public.galeri');
Route::get('/kontak', [PublicController::class, 'contact'])->name('public.contact');
Route::get('/informasi', [PublicController::class, 'informasi'])->name('informasi');
Route::get('/informasi/{slug}', [PublicController::class, 'newsDetail'])->name('news.detail');
Route::get('/survei-skm', [PublicController::class, 'surveyForm'])->name('survey.form');
Route::post('/survei-skm', [PublicController::class, 'submitSurvey'])->name('survey.submit');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes (Role Based Access Control & Full CRUD)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard (Accessible by Both Roles)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // 1. DOKUMEN CRUD (Super Admin & Anggota Staf)
    Route::get('/documents', [AdminController::class, 'documents'])->name('documents');
    Route::post('/documents', [AdminController::class, 'documentStore'])->name('documents.store');
    Route::put('/documents/{id}', [AdminController::class, 'documentUpdate'])->name('documents.update');
    Route::delete('/documents/{id}', [AdminController::class, 'documentDestroy'])->name('documents.destroy');

    // 2. INFORMASI / BERITA CRUD (Super Admin & Anggota Staf)
    Route::get('/news', [AdminController::class, 'news'])->name('news');
    Route::post('/news', [AdminController::class, 'newsStore'])->name('news.store');
    Route::put('/news/{id}', [AdminController::class, 'newsUpdate'])->name('news.update');
    Route::delete('/news/{id}', [AdminController::class, 'newsDestroy'])->name('news.destroy');

    // RESTRICTED TO SUPER ADMIN ONLY:
    Route::middleware(['role:super_admin'])->group(function () {
        
        // 3. Hero Sliders CRUD
        Route::get('/sliders', [AdminController::class, 'sliders'])->name('sliders');
        Route::post('/sliders', [AdminController::class, 'sliderStore'])->name('sliders.store');
        Route::put('/sliders/{id}', [AdminController::class, 'sliderUpdate'])->name('sliders.update');
        Route::delete('/sliders/{id}', [AdminController::class, 'sliderDestroy'])->name('sliders.destroy');
        
        // 4. Navigation Menus CRUD (HOME, PROFIL, LAYANAN, DOKUMEN, INFORMASI, HUBUNGI)
        Route::get('/menus', [AdminController::class, 'menus'])->name('menus');
        Route::post('/menus', [AdminController::class, 'menuStore'])->name('menus.store');
        Route::put('/menus/{id}', [AdminController::class, 'menuUpdate'])->name('menus.update');
        Route::delete('/menus/{id}', [AdminController::class, 'menuDestroy'])->name('menus.destroy');

        // 5. Custom Pages CRUD (PROFIL & LAYANAN Content)
        Route::get('/pages', [AdminController::class, 'pages'])->name('pages');
        Route::post('/pages', [AdminController::class, 'pageStore'])->name('pages.store');
        Route::put('/pages/{id}', [AdminController::class, 'pageUpdate'])->name('pages.update');
        Route::delete('/pages/{id}', [AdminController::class, 'pageDestroy'])->name('pages.destroy');

        // 6. Sidebar Widgets CRUD
        Route::get('/widgets', [AdminController::class, 'widgets'])->name('widgets');
        Route::post('/widgets', [AdminController::class, 'widgetStore'])->name('widgets.store');
        Route::put('/widgets/{id}', [AdminController::class, 'widgetUpdate'])->name('widgets.update');
        Route::delete('/widgets/{id}', [AdminController::class, 'widgetDestroy'])->name('widgets.destroy');

        // 7. Related Links CRUD
        Route::get('/links', [AdminController::class, 'links'])->name('links');
        Route::post('/links', [AdminController::class, 'linkStore'])->name('links.store');
        Route::put('/links/{id}', [AdminController::class, 'linkUpdate'])->name('links.update');
        Route::delete('/links/{id}', [AdminController::class, 'linkDestroy'])->name('links.destroy');

        // 7.5 Galeri Media (Foto & Video) CRUD
        Route::get('/media', [AdminController::class, 'media'])->name('media');
        Route::post('/media', [AdminController::class, 'mediaStore'])->name('media.store');
        Route::put('/media/{id}', [AdminController::class, 'mediaUpdate'])->name('media.update');
        Route::delete('/media/{id}', [AdminController::class, 'mediaDestroy'])->name('media.destroy');

        // 8. Contact Messages & Lapor Bencana CRUD
        Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
        Route::put('/messages/{id}', [AdminController::class, 'messageStatus'])->name('messages.status');
        Route::delete('/messages/{id}', [AdminController::class, 'messageDestroy'])->name('messages.destroy');

        // 9. Users & Roles Management CRUD
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'userStore'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'userUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'userDestroy'])->name('users.destroy');

        // 9.5 Activity Logs (Log Aktivitas)
        Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
        Route::delete('/logs/clear', [AdminController::class, 'logsClear'])->name('logs.clear');
        Route::delete('/logs/{id}', [AdminController::class, 'logDestroy'])->name('logs.destroy');

        // 10. Site Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'settingsUpdate'])->name('settings.update');
    });

});

<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DownloadCategoryController as AdminDownloadCategoryController;
use App\Http\Controllers\Admin\DownloadController as AdminDownloadController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\OfficialController as AdminOfficialController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VillageController as AdminVillageController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MenuPageController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\StatisticController;
use App\Http\Controllers\Frontend\VillageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Publik (Frontend)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
Route::redirect('/visi-dan-misi', '/profil#visimisi')->name('visi-dan-misi');
Route::redirect('/sejarah-kecamatan', '/profil#sejarah')->name('sejarah-kecamatan');
Route::redirect('/struktur-organisasi', '/profil#struktur')->name('struktur-organisasi');
Route::get('/berita', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/berita/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/data-desa', [VillageController::class, 'index'])->name('villages.index');
Route::get('/data-desa/{slug}', [VillageController::class, 'show'])->name('villages.show');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');

// Download routes
Route::get('/download', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/download/{id}/preview', [DownloadController::class, 'preview'])->name('downloads.preview');
Route::get('/download/{id}/unduh', [DownloadController::class, 'download'])->name('downloads.download');

// Contact routes (with rate limiting)
Route::get('/kontak', [ContactController::class, 'index'])->name('contact.index');
Route::post('/kontak', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

// Statistics
Route::get('/statistik', [StatisticController::class, 'index'])->name('statistics.index');

// Redirect /dashboard Breeze to /admin/dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Web Routes - Administrator
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Sliders
    Route::resource('sliders', AdminSliderController::class)->except(['show']);

    // Dynamic Menus
    Route::resource('menus', AdminMenuController::class)->except(['show']);

    // News Categories & Articles
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::post('articles/generate-ai', [AdminArticleController::class, 'generateAi'])->name('articles.generate-ai');
    Route::post('articles/upload-image', [AdminArticleController::class, 'uploadImage'])->name('articles.upload-image');
    Route::resource('articles', AdminArticleController::class)->except(['show']);

    // Villages
    Route::resource('villages', AdminVillageController::class)->except(['show']);

    // Public Services
    Route::resource('services', AdminServiceController::class)->except(['show']);

    // Galleries
    Route::resource('galleries', AdminGalleryController::class)->except(['show']);

    // Officials & Bagan Struktur Organisasi
    Route::post('/officials/bagan', [AdminOfficialController::class, 'updateBagan'])->name('officials.update-bagan');
    Route::delete('/officials/bagan', [AdminOfficialController::class, 'deleteBagan'])->name('officials.delete-bagan');
    Route::post('/officials/bagan/sync', [AdminOfficialController::class, 'syncBagan'])->name('officials.sync-bagan');
    Route::get('/officials/bagan/template', [AdminOfficialController::class, 'downloadTemplate'])->name('officials.download-template');
    Route::resource('officials', AdminOfficialController::class)->except(['show']);

    // Downloads
    Route::resource('download-categories', AdminDownloadCategoryController::class)->except(['show']);
    Route::resource('downloads', AdminDownloadController::class)->except(['show']);

    // Messages
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{message}/toggle-status', [AdminMessageController::class, 'updateStatus'])->name('messages.toggle-status');
    Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

    // General Settings
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Profile & Social Media Accounts
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/social-accounts', [AdminProfileController::class, 'storeSocialAccount'])->name('profile.social-accounts.store');
    Route::put('/profile/social-accounts/{socialAccount}', [AdminProfileController::class, 'updateSocialAccount'])->name('profile.social-accounts.update');
    Route::delete('/profile/social-accounts/{socialAccount}', [AdminProfileController::class, 'destroySocialAccount'])->name('profile.social-accounts.destroy');
    Route::post('/profile/social-accounts/{socialAccount}/toggle', [AdminProfileController::class, 'toggleSocialAccount'])->name('profile.social-accounts.toggle');
    Route::post('/profile/social-accounts/{socialAccount}/test', [AdminProfileController::class, 'testSocialAccount'])->name('profile.social-accounts.test');

    // Users Management (Super Admin only)
    Route::middleware('super_admin')->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
    });
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Fallback Route for Dynamic Menu Pages (MUST BE LAST)
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [MenuPageController::class, 'show'])->name('page.show');

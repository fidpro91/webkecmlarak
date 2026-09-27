<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Message;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Share common data with frontend views
        View::composer(['frontend.*', 'components.*', 'layouts.frontend'], function ($view) {
            if (Schema::hasTable('menus') && Schema::hasTable('settings')) {
                $navMenus = Cache::remember('nav_menus', 3600, function () {
                    return Menu::indukAktif()->get();
                });

                $siteSettings = Setting::allKeyed();

                $view->with([
                    'navMenus' => $navMenus,
                    'siteSettings' => $siteSettings,
                ]);
            }
        });

        // Share data with admin views
        View::composer(['admin.*', 'layouts.admin'], function ($view) {
            if (Schema::hasTable('messages') && Schema::hasTable('settings')) {
                $unreadMessagesCount = Message::unread()->count();
                $siteSettings = Setting::allKeyed();

                $view->with([
                    'unreadMessagesCount' => $unreadMessagesCount,
                    'siteSettings' => $siteSettings,
                ]);
            }
        });
    }
}

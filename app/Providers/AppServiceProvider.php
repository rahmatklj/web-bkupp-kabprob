<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $settings = \Illuminate\Support\Facades\Cache::remember('site_settings_all', 3600, function () {
                return \App\Models\SiteSetting::pluck('value', 'key')->toArray();
            });
            $navMenus = \App\Models\NavigationMenu::whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('order', 'asc')
                ->with(['children' => function($q) {
                    $q->where('is_active', true)->orderBy('order', 'asc');
                }])->get();

            $runningNews = \Illuminate\Support\Facades\Cache::remember('running_news_top', 300, function () {
                return \App\Models\NewsItem::orderBy('published_at', 'desc')->take(6)->get();
            });

            $view->with('settings', $settings)->with('navMenus', $navMenus)->with('runningNews', $runningNews);
        });
    }
}

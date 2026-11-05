<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register view composers.
     */
    public function boot(): void
    {
        View::composer(
            ['admin.include.header', 'admin.include.sidebar'],
            function ($view): void {
                $businessName = getSetting('business_name') ?? config('app.name');

                $view->with([
                    'firstLetter' => mb_strtoupper(mb_substr($businessName, 0, 1)),
                    'logo' => getSetting('site_logo'),
                    'business_name' => $businessName,
                    'favicon' => getSetting('site_favicon'),

                ]);
            },
        );
        View::composer(
            ['admin.include.footer'],
            function ($view): void {
                $view->with([
                    'developed_by' => getSetting('developed_by'),
                    'developed_by_url' => getSetting('developed_by_url'),
                ]);
            },
        );
    }
}

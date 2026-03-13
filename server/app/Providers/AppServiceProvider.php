<?php

namespace App\Providers;

use App\Models\EmailConfiguration;
use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
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
        Paginator::useBootstrap();

        $generalSettings=GeneralSetting::first();
        $logoSettings=LogoSetting::first();
        $mailSetting = EmailConfiguration::first();
        
        /** set time zone */
        Config::set('app.timezone', $generalSettings->time_zone);

        /** Set Mail Config */
        Config::set('mail.mailers.smtp.host', $mailSetting->host);
        Config::set('mail.mailers.smtp.port', $mailSetting->port);
        Config::set('mail.mailers.smtp.encryption', $mailSetting->encryption);
        Config::set('mail.mailers.smtp.username', $mailSetting->username);
        Config::set('mail.mailers.smtp.password', $mailSetting->password);

        
        
        if($generalSettings) {
            Config::set('app.timezone', $generalSettings->time_zone);
        }
        View::addLocation(resource_path('views/frontend'));
        // share general settings with all views
        View::composer('*', function ($view) use ($generalSettings, $logoSettings) {
            $view->with(['settings' => $generalSettings, 'logoSettings' => $logoSettings]);
        });
    }
}

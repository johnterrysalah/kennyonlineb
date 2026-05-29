<?php

namespace App\Providers;

use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use Illuminate\Support\Facades\View;
use App\Models\Settings;
use App\Models\SettingsCont;
use App\Models\TermsPrivacy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 1. ADD THIS: Force HTTPS and Trust Proxies for Render/Load Balancers
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
            
            // This tells Laravel to trust the headers coming from the Load Balancer
            \Illuminate\Support\Facades\Request::setTrustedProxies(
                ['127.0.0.1', '10.0.0.0/8'], 
                \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL
            );
        }

        FacadesStorage::extend('sftp', function ($app, $config) {
            return new Filesystem(new SftpAdapter($config));
        });

        Paginator::useBootstrap();

        // 2. Wrap these in a try-catch to prevent a 500 error if DB isn't ready
        try {
            $settings = Settings::where('id', '1')->first();
            $terms =  TermsPrivacy::find(1);
            $moreset =  SettingsCont::find(1);

            View::share('settings', $settings);
            View::share('terms', $terms);
            View::share('moresettings', $moreset);
            View::share('mod', $settings ? $settings->modules : null);
        } catch (\Exception $e) {
            // Database might not be migrated yet, ignore during build
        }
    }
}
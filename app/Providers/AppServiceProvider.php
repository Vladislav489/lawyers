<?php

namespace App\Providers;


use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\General\Site;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
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
    public function boot(){
        $session = Session::get('site',null);
        if (is_null($session)) {
            Site::getSite();
        }
    }
}

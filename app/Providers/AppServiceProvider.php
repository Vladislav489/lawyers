<?php

namespace App\Providers;

use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\General\Site;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
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
    public function boot() {
        if (Session::get('site', null) === null) {
            Site::getSite();
        }

        $from = [];
        $path = database_path() . DIRECTORY_SEPARATOR . 'migrations';
        $entities = array_slice(scandir($path), 2);

        foreach ($entities as $entity) {
            if (is_dir($dir = $path . DIRECTORY_SEPARATOR . $entity)) {
                $from[] = $dir;
            }
        }

        $this->loadMigrationsFrom($from);
    }
}

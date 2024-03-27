<?php

use App\Http\TaskSystem\ControllerTaskSystem;
use App\Models\CoreEngine\Model\SystemSite;
use App\Models\System\General\Site;
use App\Models\System\General\SiteConfig;
use App\Models\System\RouteBilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

ini_set('max_execution_time', 500);

// Route::any('/system/import/chunk', [ControllerTaskSystem::class, 'importChartForecastData'])->name('importChart');
// Route::any('/system/import/price', [ControllerTaskSystem::class, 'importPrice'])->name('cron_price');
Route::any('/install', function () {
    $check = Schema::hasTable((new SystemSite())->getTable());
    if (!$check) {
        $result = Artisan::call('migrate');
        $conf = new SiteConfig();
        $conf->setConfig(['migration' => true]);
        return view('/install');
    } else {
        redirect('/');
    }
})->name('install');

$publicRout = new RouteBilder();
foreach ($publicRout->build() as $controller_name => $controller) {
    foreach ($controller as $action) {
       try {
           Route::any($action['url'], $action['pathController'])->name($action['name']);
       } catch (Throwable $e) {
           dd('error', $action);
       }
    }
}

Route::any('/{file?}', function (Request $r) {
    if (isset($r->segments()[0])) {
        $dir = public_path('sitemap_') . '/' . Site::getSite()['domain_name'] . '/sitemap/' . $r->segments()[0];
        if (file_exists($dir)) {
            return response()->file($dir);
        } else {
            abort(404);
        }
    }
})->where('file', '(.*?)\.(xml)$')->name('sitemap');

Route::any('/download', [\App\Http\Controllers\Client\ClientController::class, 'actionViewFile'])->middleware('auth')->name('download');

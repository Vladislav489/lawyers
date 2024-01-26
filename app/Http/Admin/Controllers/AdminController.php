<?php
namespace App\Http\Admin\Controllers;

use App\Models\CoreEngine\LogicModels\Forecast\InformationalDataLogic;
use App\Models\System\Component\Component;
use App\Models\System\ControllersModel\AdminController as BaseAdminController;
use App\Models\System\General\Routs;
use App\Models\System\General\Seo;
use App\Models\System\General\Site;
use App\Models\System\General\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseAdminController {
    public function callAction($method, $parameters){
        return parent::callAction($method, $parameters);
    }
    public function actionIndex(){return view('Admin.main');}
    public function actionEditInfoData(){return view('Admin.Forecast.info_data');}
    public function actionTemplateEdit(){

        $this->params['site_id'] = (string)Site::getSiteId();
        $this->params['sort_by'] = Template::getTable().".id";
        $this->params['sort_dir'] ="desc";
        $this->params['id'] = $this->params['template'];
        $template = new Template($this->params,
            [   "*",
                DB::raw(Routs::getTable().".url"),
                DB::raw(Routs::getTable().".template_url"),
                DB::raw(Routs::getTable().".open"),
                DB::raw(Routs::getTable().".name_title"),
                DB::raw(Site::getTable().".domain_name"),
            ]);
        $template->setJoin(['Site','Route']);

        $tamplate = $template->getUseViewByParams(true,true);
        $paramsFromUrlCHPU = [];
        if($tamplate['template_url']){
            $paramsFromUrlCHPU = Routs::parseUrlTemplateParams($tamplate);
        }

        $seo = new Seo(['route_id'=>(string)$tamplate['route_id'],
            'template_id'=>(string)$tamplate['id'],
            'lang_id'=>(string)$tamplate['lang_id'],
            'site_id'=>(string)$tamplate['site_id']
        ]);

        $component = (new Component())->getAllComponentWithCodeComponet();
        $seoData = $seo->getOne();
        try{
            $tamplate['view'] = Blade::render($tamplate['body_view'],["admin" => true]);
            $headerMenuAnchor  = Template::getAnchor('head-page');
            $tamplate['view'] = Template::removeAnchorFromView(
                $headerMenuAnchor[0],$headerMenuAnchor[1],$tamplate['view']);
            $footer  = Template::getAnchor('footer-page');
            $tamplate['view'] = Template::removeAnchorFromView(
                $footer[0],$footer[1],$tamplate['view']);

         $view =  view('Admin.template',['template'=>$tamplate,
                'seo'=>$seoData,
             'component_tree' => $component,
             'params_route'=> $paramsFromUrlCHPU]);
        return  $view;
        }catch (\Throwable $e){
            $template__ =  $template->getOne();
            $tamplate['body_view'] = $template__['body_view'] ;
            $view =  view('Admin.template',['template'=>$tamplate,
                'seo'=>$seoData,

                'params_route'=> $paramsFromUrlCHPU,
                'error_view'=>["message" => $e->getMessage()]]);
            return  $view;
        }
    }
    public function actionLog(){return view('Admin.system_log');}
    public function actionRoutePage(){return view('Admin.route_page');}
    public function actionRouteReal(){return view('Admin.route_real');}
    public function actionEditSite(){return view('Admin.site');}
    public function actionEditView(){return view('Admin.view_page');}
    public function actionAdminMenu(){return view('Admin.menu_admin_page');}
    public function actionAdminType(){return view('Admin.type_page');}
    public function actionAdminCategory(){return view('Admin.category_page');}
    public function actionAdminImport(){return view('Admin.import');}
    public function actionAdminSiteMap(){return view('Admin.sitemap');}
    public function actionAdminCacheMap(){}

    public function actionServicePage() {return view('Admin.service-list');}


    public function actionAdmiTest(){
            $parama["union"] = [
                ['category'=>'1','page'=>'1','pageSize'=>'5'],
                ['category'=>'2','page'=>'1','pageSize'=>'5'],
                ['category'=>'3','page'=>'1','pageSize'=>'5'],
                ['category'=>'4','page'=>'1','pageSize'=>'5']
            ];

            $new = new InformationalDataLogic();
            dd($new->unionQueryOneObjectCore($parama["union"],'category_id'));
    }

    /*
     * $cache = new \Memcached();
        $cache->addServer('localhost', '11211');
        $keys = $cache->getAllKeys();
        dd($keys);
     */
    public  function getPageParams(){
        return [
            "actionIndex" => ['name'=>"Главная",'chpu'=>[]],
            "actionEditInfoData" => ['name'=>"Управление валютами акциями",'chpu'=>[]],
            "actionTemplateEdit" => ['name'=>"Управление шаблонами",'chpu'=>[]],
            "actionLog" => ['name'=>"Логи",'chpu'=>[]],
            "actionEditSite" => ['name'=>"Управление доменами",'chpu'=>[]],
            "actionRoutePage" => ['name'=>"Управление Путями",'chpu'=>[]],
            "actionEditView" => ['name'=>"Управление Шаблонами",'chpu'=>[]],
            "actionAdminMenu"=>['name'=>"Административное Меню",'chpu'=>[]],
            "actionRouteReal"=>['name'=>"Все доступные Пути",'chpu'=>[]],
            "actionAdminType"=>['name'=>"Административное Тип",'chpu'=>[]],
            "actionAdminCategory"=>['name'=>"Административное Категория",'chpu'=>[]],
            "actionAdminImport"=>['name'=>"Импорт Данных",'chpu'=>[]],
            "actionServicePage"=>['name'=>"Сервисы",'chpu'=>[]]
        ];
    }
}

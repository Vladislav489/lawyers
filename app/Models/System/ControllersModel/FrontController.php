<?php
namespace  App\Models\System\ControllersModel;
use App\Models\System\General\Routs;
use App\Models\System\General\Seo;
use App\Models\System\General\Site;
use http\Env\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\System\General\Template;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class FrontController extends CentralController {
    protected $template = null;
    protected $routs = null;
    protected $url = null;
    protected $setting = null;
    public function callAction($method, $parameters){
        // если сайт выключен или удален или отсутствует
        //if(is_null(Site::getSiteId()))
          //          abort(404);
        //Проверка для системных пользователей
        if (!Auth::check()) {
            return redirect(route__("actionUserLogin_logincontroller"));
        }

        if(isset($_GET['cache_build'])) {
            if($_GET['cache_build'] != Config::get('app.borderToken')){
                return null;
            }
        }else{
           /* if (!Auth::check()) {

             //   if ($_SERVER['SERVER_ADDR'] != "127.0.0.1")
                   return redirect(route("actionIndex_logincontroller"));
            }*/
        }
        //инициализация страницы
        $this->init($parameters);
        return parent::callAction($method, $parameters);
    }
    //Для всех страниц фронта
    public function actionCustomPage(){ //преставка action значит что это страница адресс стр. (custompage)
        return response()->make($this->View());
    }

    protected function View(){
        $search ='cache_build='.Config::get('app.borderToken');
        if(strpos($this->url,$search) !== false){
            $this->url = str_replace(["?".$search,"&".$search],"",$this->url);
        }
        if(false){
            $view = Cache::tags([Site::getSite()['domain_name'],"page"])->get($this->url);
            if(is_null($view)) {
                $view = Template::getUseView($this->template);
                $view = str_replace(["<!--//--", "--//-->"], "", $view);
                Cache::tags([Site::getSite()['domain_name'],"page"])->put($this->url, $view,7300);
            }
        }else{
            $view = Template::getUseView($this->template);
            $view = str_replace(["<!--//--", "--//-->"], "", $view);
        }
        return $view;
    }

    //Параметры страниц
    public function getPageParams(){
        return [];
    }
    // Занрузка шаблона страницы
    private function init($parameters){
        $template = $seo = null;
        $this->setting =Cache::tags([Site::getSite()['domain_name']])->get('site_setting');
        if(empty($this->setting)) {
            $this->setting = getSetting('site_setting');
            Cache::tags([Site::getSite()['domain_name']])->set('site_setting', $this->setting);
        }

        $this->url = \request()->fullUrl();
        $routs = new  Routs([],['id','name_title', 'template_url','url','open',
                                'site_id','physically','active','check_module']);
        //Поиск пуит в системе для указываного урла с учетом отсутствия такого тогда загрузка стандатра из ыистемы
        $this->routs = $routsTemplate = $routs->setJoin(['Template'])->offPagination()
            ->getRoute(\request()->getPathInfo(),\Illuminate\Support\Facades\Route::current()->uri());
        //Если не найден путь то  вернем что не нашли  ищет стери дефолтных и из ДБ
        ;
        if (!$routsTemplate) abort(404);
        //все параметры роута
        $GLOBALS['route'] = $routsTemplate;
        //все параметры страницы
        $GLOBALS['setting'] = (!is_null($this->setting) && $this->setting !== false)?$this->setting:[];
        $GLOBALS['params'] = array_merge(\request()->all(),$parameters);
        // проыеряет переданны ли правельные параметры для страницы (проверка урла на жизнеспособность)
        $checkParamsUrlExist = $routs->checkDinamicParams($routsTemplate,$GLOBALS['params']);
         //проверка на существование данных если нет то 404
        if (!is_null($checkParamsUrlExist)) {
            if (!is_object($checkParamsUrlExist) &&  $checkParamsUrlExist == 0){

                abort(404);
                return null;
            }
            //если RedirectResponse то переадрисация
            if( $checkParamsUrlExist instanceof RedirectResponse ){
                $this->template = $checkParamsUrlExist;
                return;
            }
        }
        // для передачи данных во все view данные из урла чпу и данные о пути
        \view()->composer('*', function ($view) {
            $param = array_merge($GLOBALS['setting'],["route_global" => $GLOBALS['route'],
                "route" => $GLOBALS['route'],
                "params_global" => $GLOBALS["params"]
            ]);
            $view->with($param);
        });
        //Рендеринг кода страницы

        $this->template = $this->getTemplateView($routsTemplate,$parameters);
        //удаляет псевдо теги  для работы клмпонентов
        $this->template = str_replace(["<!--//--","--//-->"],"",  $this->template);
        if (is_null($this->template) || empty($this->template)) {
            abort(500);//Если не найден не одного темплейта (даже дефолтного)
        }
    }



    private function getTemplateView($route,$parameters){
        $seo = null;
        $template = null;
        if (isset($route['id'])) {
            //Ищем темплейт для по айди роута и сайт айди
            $templateParams = ["site_id" => (string)$route['site_id'], "route_id" => (string)$route['id'],
                "lang_id" => '1', 'active' => '1',];

            //получение темплейта если найден путь
            $template = new Template($templateParams, ['*']);
            $template = $template->offPagination()->getOne();

            if (isset($template['id']) && !empty($template['id'])) {
                // собираем данные из риквеста и с чпу урла
                $this->params = array_merge($this->params,$parameters);
                //заменя на фильтер для алиасов
                if(isset($this->params['type'])){
                    $this->params['type_alias'] = $this->params['type'];
                    unset($this->params['type']);
                }
                //заменя на фильтер для алиасов
                if(isset($this->params['category'])){
                    $this->params['category_alias'] = $this->params['category'];
                    unset($this->params['category']);
                }
                //параметры поиска
                $seoParams = ["template_id" => (string)$template['id'], "site_id" => (string)$route['site_id'],
                    "route_id" => (string)$route['id'], "lang_id" => '1'];
                //получаем данны для формирования сео  по ади роута
                $seo = new Seo($seoParams,["*"],$this->params);
                $seo = $seo->offPagination()->buildText();
            } else {
                //если не шаблона
                abort(404);
            }
        }
        // Полная сборка страницы
        return Template::getTemplate($route,$seo,$template,$this->getPageParams());
    }
}

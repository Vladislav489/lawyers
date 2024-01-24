<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 18.12.2022
 * Time: 4:29
 */

namespace App\Http\Admin\Mainstay;
use App\Models\CoreEngine\LogicModels\ComboSite\CategoryLogic;
use App\Models\CoreEngine\LogicModels\ComboSite\SiteMapLogic;
use App\Models\CoreEngine\LogicModels\ComboSite\TypeLogic;
use App\Models\CoreEngine\LogicModels\Forecast\InformationalDataLogic;
use App\Models\CoreEngine\LogicModels\Forecast\NasdaqSectionLogic;
use App\Models\System\Admin\AdminMenu;
use App\Models\System\Admin\LogLogic;
use App\Models\System\Component\Component;
use App\Models\System\ControllersModel\MainstayAdminController;
use App\Models\System\General\Routs;
use App\Models\System\General\Seo;
use App\Models\System\General\SettingStorage;
use App\Models\System\General\Site;
use App\Models\System\General\TableSysten;
use App\Models\System\General\Template;
use App\Models\System\HelperFunction;
use App\Models\System\LogicModulsInfo;
use App\Models\System\RouteBilder;
use App\Models\System\Securities;
use App\Models\System\SystemLog;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class BackController extends MainstayAdminController {
    public function callAction($method, $parameters){
        //dd(Cache::get('controllerList'));
        if (!Auth::check()) {
          //  return redirect(route__("actionIndex_logincontroller"));
        }
        if(Auth::id() != 1){}

        return parent::callAction($method, $parameters);
    }

    public function actionSystemsMenu($params = []){

        if(Auth::id() != 1)
            $this->params['site'] = (string)Site::getSiteId();

        $this->params['active'] = 1;

            $menu = new AdminMenu($this->params);

        return Response::json($menu->getMemuItem());
    }
    public function actionAdminMenuList($params = []){
        $colums = AdminMenu::getColumForAdmin();
        $menu = new AdminMenu($this->params,["*"]);
        $return = $menu->getList();
        $return['column'] = $menu->getLable($colums['sort_col'],'rus',$colums['ignody_col']);
        return Response::json($return);
    }
    public function actionGetAdminMenuList($params = []){
        $menu = new AdminMenu($this->params,["*"]);
        $return = $menu->getList();
        $return['result'] = HelperFunction::ArrayForSelectFomCodeEngine($return['result'],'id','lable');
        return Response::json($return);
    }
    public function actionGetAdminMenuDelete($params = []){
        $data = [];
        $obj = new AdminMenu();
        $data['result'] =$obj->delete($this->params['id']);
        return Response::json($data);
    }
    public function actionAdminMenuSave($param = []){
        $result = [];
        $obj = new AdminMenu();
        $this->params['site_id'] = Site::getSiteId();
        $result['result'] =($obj->save($this->params))? 1:0;
        return Response::json($result);
    }

    public function actionGetAllCarrence($param = []){
        $colums = InformationalDataLogic::getColumForAdmin();
        $infoData = new InformationalDataLogic($this->params,HelperFunction::BuildSelectColumsForEngin($colums['select']));
        $return = $infoData->getList();
        foreach ($return['result'] as $key => $value){
            $return['result'][$key]['updated_at'] = date("Y-m-d H:i:s",strtotime($return['result'][$key]['updated_at']));
        }
        return Response::json($return);
    }
    public function actionInformationalDataSave($param = []){
          $data = [];
          $obj = new InformationalDataLogic([]);
          $data['result'] =($obj->save($this->params))?1:0;
          return Response::json($data);
    }
    public function actionInformationalDataDelete($param = []){
        $data = [];
        $obj = new InformationalDataLogic();
        $data['result'] =$obj->delete($this->params['id']);
        return Response::json($data);
    }
    public function actiongetListNasdaqSection(){
        $section = new NasdaqSectionLogic();
        $result = $section->getList();
        $result['result'] = HelperFunction::ArrayForSelectFomCodeEngine(
            $result['result'],"id","section_name");
        return Response::json($result);
    }

    public function actiongetListTypeCarrency($param = []){
        $result = [];
        $result['result'] = Securities::getTypeSTR('rus');
        return Response::json($result);
    }
    public function actiongetListTypeLog($param = []){
        $result = [];
        $result['result'] = SystemLog::getListConst('rus');
        return Response::json($result);
    }
    public function actionGetListPageSite($param = []){
        $route = new  RouteBilder();
        $routeParams = $route->getParamsController('controller');
        $return = [];
        foreach ($routeParams as $key => $item) {
            if (isset($item['params'])) {
                $return[$item['url']] = $item['params']['name'].' -> ('.$item['url'].')';
            }
        }
        $return['result'] = $return;
        return Response::json($return);
    }
    public function actionGetListComponent($param = []){
        $return = [];
        $listComponent = new Component();
        $return['result'] = $listComponent->getAllComponent();
        return Response::json($return);
    }
    public function actionGetListComponentWithCode($param = []){
        $return = [];
        $listComponent = new Component();
        $return['result'] = $listComponent->getAllComponentWithCodeComponet();
        return Response::json($return);
    }
    public function actionSystemLog($param = []){
        $this->params['sort_dir'] = "desc";
        $this->params['sort_by'] ="id";
        $log = new LogLogic($this->params,["*"]);
        return Response::json($log->getLog());
    }
    public function actionGetListRouteSelect($param = []){
        $Routs = new Routs($this->params,["id",'name_title']);
        $result = $Routs->getList();
        $result = HelperFunction::ArrayForSelectFomCodeEngine(
            $result['result'],"id","name_title");
        return Response::json($result);
    }
    public function actionGetSystemRoute($param = []){
        $colums = Routs::getColumForAdmin();
        $Routs = new Routs($this->params,HelperFunction::BuildSelectColumsForEngin($colums['select']));
        $Routs->setJoin(['Site']);
        $listrout = json_decode($this->actionGetListPageSite()->getContent(),true)['result'];
        $return = $Routs->getList();
        $return['column'] = $Routs->getLable($colums['sort_col'],'rus',$colums['ignody_col']);
        foreach ($return['result'] as $key => $value){
            if(is_null($return['result'][$key]['name_title']) && empty($return['result'][$key]['name_title'])){
                $return['result'][$key]['name_title'] = (isset($listrout[$return['result'][$key]['url']]))?
                     $listrout[$return['result'][$key]['url']]:"НЕТ";
            }
            $return['result'][$key]['updated_at'] = date("Y-m-d H:i:s",strtotime($return['result'][$key]['updated_at']));
        }
        return Response::json($return);
    }
    public function actionSystemRouteSave($param = []){
        $return = [];
        $routs = new Routs();
        $paramsDefoltPhisicalPage = RouteBilder::findByTemplate($this->params['template']);

        $this->params['type_page'] = ( $paramsDefoltPhisicalPage)? $paramsDefoltPhisicalPage['params']['type_page']:"end";
        $id = $routs->save($routs->getEngine()->removeNotExistCol($this->params));
        $return['result'] =($id)?1:0;
        if($id) {
            unset($this->params['id']);
            unset($this->params['type_page']);
            $this->params['route_id'] = $id;
            if($this->params['template'] != "Empty") {
                $this->params['name_view'] = $this->params['template'];
                $this->params['bode_view'] = "";
            } else {
                $this->params['name_view'] = "Кастомный";
                $this->params['bode_view'] = "";
            }
            $this->params['url_route'] = $this->params['url'];
            $template = new Template([
                'route_id' => (string)$this->params['route_id'],
                'lang_id' => (string)$this->params['lang_id'],
                'site_id' => (string)$this->params['site_id'],
            ]);
            $res = $template->getOne();
            if(isset($res['id'])) {
                if (!empty($res['bode_view']))
                    $this->params['bode_view'] = ($this->params['template'] != "Empty")? $res['bode_view']:"";

                $this->params['id'] = $res['id'];
            }
            $idTemplate = $template->save($template->getEngine()->removeNotExistCol($this->params));
            $return['result'] = ($idTemplate) ? 1:0;
            if(!$idTemplate)
                $routs->delete($id);
        }
        return Response::json($return);
    }
    public function actionSystemRouteDelete($param = []){
        $result = [];
        $obj = new Routs();
        $result['result'] = $obj->delete($this->params['id'],true);
        return Response::json($result);
    }

    public function actionClearCache($param = []){
        Artisan::call("route:clear");
        Artisan::call("cache:clear");
        Artisan::call("view:clear");
        return redirect(route__('actionIndex_admin_controllers_admincontroller'));
    }

    public function actionGetListSiteSelect($param = []){
        $Site = new Site($this->params,["id",'domain_name']);
        $result = $Site->getList();
        $result['result'] = HelperFunction::ArrayForSelectFomCodeEngine(
            $result['result'],"id","domain_name");
        return Response::json($result);
    }
    public function actionGetListSite($param = []){
        $site = new Site([],['id','domain_name','user_main_id','style','lang_id','active']);
        $return = $site->getList();
        $return['column'] = HelperFunction::getLableForQuery(
            $return['result'][0],
            $site->getEngine()->getLable('rus'),
            ['active']
        );
        return Response::json($return);
    }
    public function actionSiteSave($param = []){
        $result = [];
        $obj = new Site();
        $result['result'] =($obj->save($this->params))?1:0;
        return Response::json($result);
    }
    public function actionSiteDelete($param = []){
        $result = [];
        $obj = new Site();
        $result['result'] =$obj->delete($this->params['id']);
        return Response::json($result);
    }

    public function actionGetListViews($param = []){
        $result = [];
        $template = new Template();
        $result['result'] = $template->getPresentListTemplate();
        return Response::json($result);
    }

    public function actionGetListView($param = []){
        $colums = Template::getColumForAdmin();
        $this->params['sort_dir'] = 'desc';
        $this->params['sort_by'] = [
            Template::getTable().".updated_at",
            Routs::getTable().".name_title"];
        $colums['select'] =  HelperFunction::BuildSelectColumsForEngin($colums['select']);
        $colums['select'][] = DB::raw(Routs::getTable().".template_url");
        $template = new Template($this->params,$colums['select']
        );
        $template->setJoin(['Route','Site']);
        $return = $template->getList();
        $return['column'] = $template->getLable($colums['sort_col'],'rus',$colums['ignody_col']);
        return Response::json($return);
    }
    public function actionViweSave($param = []){
        $result = [];
        $obj = new Template();
        $result['result'] =($obj->save($this->params))?1:0;
        return Response::json($result);
    }
    public function actionViweDelete($param = []){
        $result = [];
        $obj = new Template();
        $result['result'] =$obj->delete($this->params['id'],true);
        return Response::json($result);
    }

    public function actionRouteList($param = []){
        $route = new Routs();
        return Response::json($route->getAllSystemUrl());
    }
    public function actionGetModuleLogic($param = []){
        $seo = new Seo();
        return Response::json(['result'=>$seo->getListModule()]);
    }

    public function actionGetModuleLogicList($param = []){
        $data = LogicModulsInfo::getInstace()->getParmasFromObject();
        $data = HelperFunction::ArrayForSelectFomCodeEngine($data,'class_name','class_name');
        return Response::json(['result'=>$data]);
    }

    public function actionGetTempateId($param = []) {
        $template = new Template( array_merge($this->params,
            ["site_id" => (string)Site::getSiteId(), "sort_by" => 'id', "sort_dir" => 'desc']));
        $result = $template->getUseViewByParams(true);
        return Response::json($result);
    }
    public function actionGetTempateCode($param = []){
        $template = new Template( array_merge($this->params,
            ["site_id" => (string)Site::getSiteId(), "sort_by" => 'id', "sort_dir" => 'desc']));
        $templatePage = $template->getOne();
    }
    public function actionGetTempateView($param = []){}
    public function actionTempateSave($param = []){
        $this->params['site_id'] = (string)Site::getSiteId();
        DB::beginTransaction();
        $site_id = Site::getSiteId();
        $this->params['template']['site_id'] = $site_id;
        $this->params['seo']['site_id'] = $site_id;
        $this->params['seo']['lang_id'] =  $this->params['template']['lang_id'];
        $this->params['seo']['template_id'] =  $this->params['template']['id'];
        $this->params['seo']['route_id'] =  $this->params['template']['route_id'];
        $template = new Template();
        foreach ($this->params['template'] as $key => $item){
            if( !in_array($key,$template->getEngine()->getFillable())){
               if($key!='id')
                    unset($this->params['template'][$key]);
            }
        }
        unset($this->params['template']['created_at']);
        unset($this->params['template']['updated_at']);
        $result = $template->save($this->params['template']);
        $seo = new Seo();
        $result = $seo->save($this->params['seo']);
        if ($result)
            DB::commit();
        else
            DB::rollBack();

        return Response::json($result);
    }

    public function actionGetListType($param = []){
        $type = new TypeLogic($param);
        $return = $type->getList();
        if (isset($return['result'])) {
            $return['column'] =  $type->getLable(array_keys($return['result'][0]),'rus',
                    ['active','id_name','is_deleted','site_id','created_at','updated_at']);
        }
        return Response::json($return);
    }
    public function actionTypeSave($param = []){
        $result = [];
        $obj = new TypeLogic();
        $result['result'] =($obj->save($this->params))?1:0;
        return Response::json($result);
    }
    public function actionTypeDelete($param = []){
        $result = [];
        $obj = new TypeLogic();
        $result['result'] =$obj->delete($this->params['id']);
        return Response::json($result);
    }
    public function actionGetListTypeSelect($param = []){
        $type = new TypeLogic($this->params,["id"]);
        $result = $type->offPagination()->getList();
        if(isset($result['result'])) {
            $result['result'] = HelperFunction::ArrayForSelectFomCodeEngine(
                $result['result'], "id", "name");
        }
        return Response::json($result);
    }

    public function actionGetListCategory($param = []){
        $category = new CategoryLogic();
        $return = $category->getList();

        if(isset($return['result'])) {
            $return['column'] = HelperFunction::getLableForQuery(
                $return['result'][0],
                $category->getEngine()->getLable('rus'),
                ['active','id_name','is_deleted','site_id','created_at','updated_at']
            );
        }
        return Response::json($return);
    }
    public function actionCategorySave($param = []){
        $result = [];
        $obj = new CategoryLogic();
        $result['result'] = ($obj->save($this->params))? 1:0;
        return Response::json($result);
    }
    public function actionCategoryDelete($param = []){
        $result = [];
        $obj = new CategoryLogic();
        $result['result'] = $obj->delete($this->params['id']);
        return Response::json($result);
    }
    public function actionGetListCategorySelect($param = []){
        $category = new CategoryLogic($this->params,["id",'alias_url']);
        $result = $category->offPagination()->getList();
        if(isset($result['result'])) {
            $result['result'] = HelperFunction::ArrayForSelectFomCodeEngine(
                $result['result'], "id", "name");
        }
        return Response::json($result);
    }

    public function actionGetListTable($param = []){
        $result['result'] = HelperFunction::ArrayForSelectFomCodeEngine(
            (new TableSysten)->getListTableByUser(), "id", "name");
        return Response::json($result);
    }

    public function actionGetListFieldTable($param = []){
        if (isset($this->params['table'])) {
            $field = LogicModulsInfo::getInstace()->findByClassName($this->params['table'],'table');
            $module = HelperFunction::ArrayForSelectFomCodeEngine($field['lable'], 'key','name');
            return Response::json(['result'=>$module]);
        }
    }

    public function actionHideElementMore4DayDiffUpdatePrice($param = []){
        try {
            DB::statement("UPDATE " . InformationalDataLogic::getTable() .
                " SET `active` = '0' WHERE DATEDIFF(DATE(NOW()),DATE(`updated_at`)) > 4 ");
            return ['result' => 1];
        }catch (\Throwable $e){
            return ['result' => 0];
        }
    }

    public function actionBuildView($param = []){
        $id = TypeLogic::getTypeId($this->params['type']);
        $this->params['type'] = (is_null($id))? $this->params['type']:(string)$id;
        $tamplate =  $this->params['template'];
        $seoParams = ["template_id" => (string)$this->params['template']['id'],
                      "site_id" => (string)$this->params['template']['site_id'],
                      "route_id" => (string)$this->params['template']['route_id'],
                      "lang_id" => '1'];
        unset($this->params['template']);
        $seo = new Seo($seoParams,["*"],$this->params);
        $seo = $seo->offPagination()->buildText();
        $tamplate['body_view'] = str_replace(["<!--//--","--//-->"],"",$tamplate['body_view']);
        $view =  Blade::render(Template::buildTemplateFromBody($tamplate ,$seo),['admin'=>true]);
        $headerMenuAnchor  = Template::getAnchor('head-page');
        $view = Template::removeAnchorFromView(
            $headerMenuAnchor[0],$headerMenuAnchor[1],$view);
        $footer  = Template::getAnchor('footer-page');
        $view = Template::removeAnchorFromView(
            $footer[0],$footer[1],$view);
        return $view;
    }

    public function actiongetNewCodeForComponent($param = []){



    }

    public function actionGetSetting($param = []){SettingStorage::getInstance()->getSetting($this->params['key']);}
    public function actionSetSetting($param = []){SettingStorage::getInstance()->setSetting($this->params['key'],$this->params['value']);}

    public function actionCreateSiteMap($param = []){
        $sitemap = new SiteMapLogic();
        $res =  $sitemap->createSiteMap();
        return Response::json($res);
    }
    public function actionGetListSiteMap($param = []){
        $sitemap = new SiteMapLogic();
        $res = $sitemap->getListFileSiteMap();
        return Response::json($res);
    }

    public function getPageParams($param = []){
        return [];
    }


}

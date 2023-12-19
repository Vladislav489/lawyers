<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.05.2023
 * Time: 13:45
 */

namespace App\Models\System\Component;


use App\Models\System\General\Routs;
use App\Models\System\General\Site;
use App\Models\System\HelperFunction;
use App\Models\System\SettingStorage;
use App\Models\System\SystemLog;
use App\System\Parser\UrlTargetParser;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;

class
ComponentBuilder {

    const START_COMPONENT = "<!--//--";
    const END_COMPONENT = "--//-->";

    private $component = null;
    private $add_include_js = [];
    private $params_component = [];
    private $route = [];
    private $listShowOptionComponent = null;
    private $includeComponenToComponet = null;
    private $request = null;
    private $codeComponent = "";

    public function __construct(){
        $this->listShowOptionComponent[] = route__("actionTemplateEdit_admincontroller");
        $this->listShowOptionComponent[] = route__("actionBuildView_backcontroller");
        $this->request = request();
    }

    public function buildComponent($dataComponent){
        $this->component = $dataComponent['component'];
        if (isset($dataComponent['route']))
            $this->route = $dataComponent['route'];

        if (isset($dataComponent['params_component']))
            $this->params_component = $dataComponent['params_component'];

        if (isset($dataComponent['add_link_component']))
            $this->add_include_js = $dataComponent['add_link_component'];

        if (isset($this->params_component['data']) && !is_null($this->params_component['data']) && !empty($this->params_component['data'])) {
            try {
                $result = json_decode($this->params_component['data']);
                if (json_last_error() !== JSON_ERROR_NONE)
                    $this->params_component['data'] = "'" . $this->params_component['data'] . "'";
            } catch (\Throwable $e) {
                dd($this->params_component['data'],$this->component);
            }
        } else {
            $this->params_component['data'] = 'null';
        }


        if (isset($this->params_component['name']))
                $this->params_component['clear_name'] = $this->params_component['name'];

        if (in_array("/".$this->request->path(),$this->listShowOptionComponent)) {
            if (isset($this->params_component['include_component']))
                $this->params_component = $this->includeComponentToComponent($this->params_component);
            return $this->buildForAdmin();
        } else {
            if(isset($this->params_component['name']))
                $this->params_component['name'].= uniqid();


            if (isset($this->params_component['include_component'])) {
                $this->params_component = $this->includeComponentToComponent($this->params_component);
                return $this->buildComponentFront();
            } else {
                if(key_exists('ssr',$this->params_component))
                    $this->params_component = $this->SSRData($this->params_component);
                else
                    $this->params_component['data'] = 'null';
                return $this->buildComponentFront();
            }
        }
    }
    public function buildComponentFront($admin = false){
        $commandComponent =  $this->includeJsComponent();
        if(isset($this->params_component['template'])) {
            if(!$admin) {
                $this->params_component['template'] = str_replace("{{", '@{{', $this->params_component['template']);
                $this->params_component['template'] = Blade::render($this->params_component['template']);
            }
        }

        $commandComponent[] = "@include(\$component,\$params_component)";
        if(!is_array($this->params_component)){
            $this->params_component = json_decode($this->params_component,true);
            if(!is_array($this->params_component)){
                $this->params_component = [];
                SystemLog::addLog("BuildComponent",['component' => $this->component, 'params_component' => $this->params_component],"buildComponentFront");
            }
        }
        $this->codeComponent = Blade::render(implode("\n", $commandComponent),
            ['component' => $this->component, 'params_component' => $this->params_component]);

        return $this->codeComponent;
    }
    public function buildComponentAdmin(){
        $commandComponent = [];
        unset($this->params_component['includeComponentHtml']);
        unset($this->params_component['includeComponentScript']);
        if(isset($this->params_component['name'])) {
            $this->params_component['clear_name'] = $this->params_component['name'];
            $this->params_component['name'] = "view_component_{$this->params_component['name']}";
            $commandComponent[] = "<component_template data-id='{$this->params_component['name']}'>";
            $this->params_component['name'] = $this->params_component['clear_name'];
        }



        $commandComponent = array_merge($commandComponent,$this->includeJsComponent(true));
        $commandComponent[] = self::START_COMPONENT;
        $commandComponent[] = "@include('component_build',[";
        $commandComponent[] = "'component'=>'{$this->component}',";


        $flgClrTemp = (isset($this->params_component['data']) && strpos($this->params_component['data'],'template_clear') !== false);
        $this->params_component['data'] = ($flgClrTemp)?str_replace(["'",'"'],'',$this->params_component['data']):'null';

        $tobilder = $this->params_component;
        if(isset($this->params_component['template']))
            $tobilder['template'] = str_replace("@{{", '{{', $tobilder['template']);

        $commandComponent[] = "'params_component'=>".\App\Models\System\HelperFunction::array_to_string($tobilder).",";

        if(!empty($this->add_include_js))
            $commandComponent[] = "'add_link_component'=>".(is_array($this->add_include_js)?\App\Models\System\HelperFunction::array_to_string($this->add_include_js):"'{$this->add_include_js}'");

        $commandComponent[] = "])";
        $commandComponent[] = self::END_COMPONENT;
        $commandComponent[] = "</component_template>";
        return implode("\n",$commandComponent);
    }


    private function includeJsComponent($adminFlag = false){
        $commandComponent = [];
        if(!empty($this->add_include_js)){
            if ($this->add_include_js && is_array($this->add_include_js)) {
                foreach ($this->add_include_js as $item)
                    $commandComponent[] =   (($adminFlag)? "\n ".self::START_COMPONENT." \n":"")."@include('".$item."',['include' => true])".(($adminFlag)? "\n".self::END_COMPONENT."\n":"");
            } else {
                $commandComponent[] =  (($adminFlag)? "\n ".self::START_COMPONENT." \n":"")."@include('".$this->add_include_js."',['include' => true])".(($adminFlag)? "\n".self::END_COMPONENT."\n":"");
            }
        }
        return $commandComponent;
    }
    private function buildForAdmin(){
        $autostart = $ssr = null;
        if(isset($this->params_component['ssr']))
            $ssr = $this->params_component['ssr'];
        if(isset($this->params_component['autostart']))
            $autostart = $this->params_component['autostart'];

        $this->params_component['ssr'] = false;
        $this->params_component['autostart'] = 'true';

        $code = $this->buildComponentFront(true)."\n";
        $this->params_component['ssr'] =(!is_null($ssr))? $ssr:false;
        $this->params_component['autostart'] =(!is_null($autostart))?$autostart:false;
        $code .= $this->buildComponentAdmin();
        return $code;
    }
    private function includeComponentToComponent($paramsComponet){
        $data = null;
        if(isset($paramsComponet['include_component'])){
            $this->includeComponenToComponet = $paramsComponet['include_component'];
            if (key_exists('ssr',$paramsComponet) && $paramsComponet['ssr'] == true || $paramsComponet['ssr'] == 'true' ) {
                if (key_exists('type_query',$paramsComponet) && $paramsComponet['type_query']) {
                    $data = $this->ssrUnionForGroupComponent($paramsComponet);
                } else {
                    $data = $this->ssrForGroupComponent($paramsComponet);
                }
            }
            $this->includeComponenToComponet['params_component']['includeToComponent__'] = true;
            $this->includeComponenToComponet['params_component']['ssr'] = false;
            $this->includeComponenToComponet['params_component']['data'] = 'data_component';
            $this->includeComponenToComponet['params_component']['name'] = 'replace_name';
            $this->includeComponenToComponet['params_component']['params'] = "replace_params";
            $templateInclude = "\n@include('component_build',".HelperFunction::array_to_string($this->includeComponenToComponet).")\n";
            $loadIncludeComponent = Blade::render($templateInclude);
            $parserScript = new UrlTargetParser();
            $parserScript->setTargets([
                [
                    'target'    => "script[date-id_script*=".$this->includeComponenToComponet['params_component']['name']."*]",
                    'takeData'  => UrlTargetParser::TAKE_DATE_HTML,
                ],
                [
                    'target'    => "component[data-name*=".$this->includeComponenToComponet['params_component']['name']."*]",
                    'takeData'  => UrlTargetParser::TAKE_DATE_HTML_OUT,
                ],
            ]);
            $search = $parserScript->parserFromText($loadIncludeComponent);
            $paramsComponet['includeComponentHtml'] = trim($search[1]);
            $paramsComponet['includeComponentHtml'] = str_replace(
                'replace_name',
                $paramsComponet['include_component']['params_component']['name'],
                $paramsComponet['includeComponentHtml']
            );
            $paramsComponet['includeComponentScript'] = trim($search[0]);

            $paramsComponet['includeComponentScript'] = str_replace(
                "'data_component'",
                'data_component',
                $paramsComponet['includeComponentScript']
            );

            $paramsComponet['includeComponentScript'] = preg_replace(
                "/'replace_name.*'/",
                "'".$paramsComponet['include_component']['params_component']['name']."'+nameId",
                $paramsComponet['includeComponentScript']
            );

            $paramsComponet['includeComponentScript'] = str_replace(
                ['\'replace_params\'','"replace_params"'],
                "Object.assign(add_params,".json_encode($paramsComponet['include_component']['params_component']['params']).")",
                $paramsComponet['includeComponentScript']
            );

            $paramsComponet['includeComponentScript'] = str_replace(
                '\'#component_replace_name\'',
                "'#component_".$paramsComponet['include_component']['params_component']['name']."'+nameId",
                $paramsComponet['includeComponentScript']
            );

            $paramsComponet['includeComponentScript'] = str_replace(
                '\'component_replace_name\'',
                "'component_".$paramsComponet['include_component']['params_component']['name']."'+nameId",
                $paramsComponet['includeComponentScript']
            );

            $paramsComponet['includeComponentScript'] = str_replace(
                'replace_name',
                $paramsComponet['include_component']['params_component']['name'],
                $paramsComponet['includeComponentScript']
            );

            $paramsComponet['includeComponentScript'].="\n return obj_".$paramsComponet['include_component']['params_component']['name'].";";
            $paramsComponet['data'] = (isset($data['data']) && !empty($data['data']))? json_encode($data['data']):"null";
            return $paramsComponet;
        }
    }

    private function getSiteSettingSite(){
        $setting = Cache::tags([Site::getSite()['domain_name']])->get('site_setting');
        if(!$setting){
            $setting = getSetting("site_setting");
            Cache::tags([Site::getSite()['domain_name']])->set('site_setting',$setting,3600);
        }
        return $setting;
    }
    private function buildParamsForSsr($params_component){
        $paramSend = [];
        if(isset($params_component['params']['union'])){
            foreach ($params_component['params']['union'] as $key => $item){
                if (isset($params_component['globalParams']) && $params_component['globalParams'] == true) {
                    if (isset($this->params_component['params'])) {
                        $params_component['params']['union'][$key] = array_merge(
                            (isset($this->route['params_route'])) ? $this->route['params_route'] : [],
                            array_merge($params_component['params']['union'][$key], $this->request->all())
                        );
                    } else {
                        $params_component['params']['union'][$key] = (isset($this->route['params_route'])) ? array_merge($this->route['params_route'], $this->request->all()) : $this->request->all();
                    }
                }
                if (isset($params_component['pagination']))
                    $params_component['params']['union'][$key] = array_merge($params_component['pagination'],$params_component['params']['union'][$key]);

                foreach ($params_component['params']['union'][$key] as $key__ => $item__){
                    if(!is_array($item__))
                        $params_component['params']['union'][$key][$key__] = (string)$item__;
                }
            }
            $un = $params_component['params']['union'];
            $gp = $params_component['params']['union_group'];
            unset($params_component['params']);
            $params_component['params']['union'] = $un;
            $params_component['params']['union_group'] = $gp;
            $paramSend = $params_component['params'];

        } else {
            if (isset($params_component['params']))
                $paramSend = $params_component['params'];
            ///////////////////////////////////////////////////////////////////////////////////////////
            if (isset($params_component['globalParams']) && $params_component['globalParams'] == true) {
                if (isset($this->params_component['params'])) {
                    $paramSend = array_merge(
                        (isset($this->route['params_route'])) ? $this->route['params_route'] : [],
                        array_merge($params_component['params'], $this->request->all())
                    );
                } else {
                    $paramSend = (isset($this->route['params_route'])) ? array_merge($this->route['params_route'], $this->request->all()) : $this->request->all();
                }
            }
            //////////////////////////////////////////////////////////////////////////////////////////
            if (isset($params_component['pagination']))
                $paramSend = array_merge($params_component['pagination'], $paramSend);
            //////////////////////////////////////////////////////////////////////////////////////////
            foreach ($paramSend as $key => $item){
                if(!is_array($item))
                    $paramSend[$key] = (string)$item;
            }
            /////////////////////////////////////////////////////////////////////////////////////////
        }
        return $paramSend;
    }
    private function SSRData($params_component){
        $paramSend = [];
        $response = null;
        $route = new Routs();
        $listUrl = $route->getAllSystemUrl()['result'];
        if (isset($params_component['url']) &&  key_exists($params_component['url'],$listUrl)) {
            if (isset($params_component['ssr']) && $params_component['ssr'] == true || $params_component['ssr'] == 'true') {
                if (isset($params_component['url'])) {
                    try {

                        $paramSend = $this->buildParamsForSsr($params_component);
                        $obj = new $listUrl[$params_component['url']]['controller']();
                        $response = $obj->{$listUrl[$params_component['url']]['action']}($paramSend);

                    } catch (\Throwable $e) {
                        dd($paramSend ,$listUrl[$params_component['url']]['controller'],$params_component['url'],$listUrl[$params_component['url']]['action'],$response);
                        SystemLog::addLog("SSR Error Http Request",
                            [
                                'component_param' => $params_component,
                                'params' => $paramSend,
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                                'message' => $e->getMessage(),
                                'trace' => $e->getTrace(),
                            ],
                            $e->getMessage(),
                            SystemLog::CODE_ERROR);
                        return $params_component['data'] = json_encode(['result' => ['error' => "SSR Error Http Request"]]);
                    }
                    if ($response->status() == 500) {
                        SystemLog::addLog("SSR Error Http Request", $response->content(),
                            substr($response->content(), 0, 100),
                            SystemLog::CODE_ERROR);
                        $params_component['data'] = json_encode(['result' => ['error' => "SSR Error Http Request"]]);
                        return $params_component;
                    }

                    if (($response->status() == 200)) {
                        $dataAPI = $response->content();
                        $dataAPI = json_decode($dataAPI, true);
                        if (isset($dataAPI['result'])) {
                            $params_component['data'] = json_encode($dataAPI['result']);
                            if (isset($dataAPI['pagination']) && isset($params_component['pagination']))
                                $params_component['pagination'] = array_merge($params_component['pagination'], $dataAPI['pagination']);
                        } else {
                            $params_component['data'] = json_encode($dataAPI);
                        }
                    } else {
                        $params_component['data'] = "null";
                    }
                }
            }
        }
        return $params_component;
    }
    private function ssrForGroupComponent($paramsComponet){
        $data = $this->SSRData($paramsComponet);
        try {
            $data['data'] = ($data['data'] != 'null') ? json_decode($data['data'], true) : $data['data'];
        }catch (\Throwable $e){
            SystemLog::addLog("SSR Error Http Request json_decode",
                [
                    'component_param'=>$paramsComponet,
                    'params' => $data['data'],
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),

                ],
                $e->getMessage(),
                SystemLog::CODE_ERROR);
        }
        if($data['data'] != 'null' && !empty($data['data'])) {
            foreach ($data['data'] as $key => $value) {
                $temp = [];
                foreach ($paramsComponet['add_params'] as $addItem) {
                    if (isset($addItem['field']))
                        $temp[$addItem['target']] = $value[$addItem['field']];
                    if (isset($addItem['value']))
                        $temp[$addItem['target']] = $addItem['value'];
                }
                if (isset($this->includeComponenToComponet['params_component']['params'])) {
                    $this->includeComponenToComponet['params_component']['params'] = array_merge($this->includeComponenToComponet['params_component']['params'], $temp);
                } else {
                    $this->includeComponenToComponet['params_component']['params'] = $temp;
                }
                $subData = $this->SSRData($this->includeComponenToComponet['params_component'])['data'];
                $subData = ($subData != 'null') ? json_decode($subData, true) : $subData;
                $data['data'][$key]['data'] = $subData;
            }
        }
        return $data;
    }
    private function ssrUnionForGroupComponent($paramsComponet){


        $data = $this->SSRData($paramsComponet);
        try {
            $data['data'] = ($data['data'] != 'null') ? json_decode($data['data'], true) : $data['data'];
        }catch (\Throwable $e){
            SystemLog::addLog("SSR Error Http Request json_decode",
                ['component_param'=>$paramsComponet,
                    'params' => $data['data'],
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),],
                $e->getMessage(),
                SystemLog::CODE_ERROR);
        }
        if($data['data'] != 'null' && !empty($data['data'])) {
            $union = [];
            $targetConcatData = null;
            foreach ($data['data'] as $key => $value) {
                $temp = $params = [];
                foreach ($paramsComponet['add_params'] as $addItem) {
                    if($addItem['target'] == $this->params_component['group_query'])
                        $targetConcatData = $addItem['field'];
                    if (isset($addItem['field']))
                        $temp[$addItem['target']] = $value[$addItem['field']];
                    if (isset($addItem['value'])) {
                        $temp[$addItem['target']] = $addItem['value'];
                    }
                }

                if (isset($this->includeComponenToComponet['params_component']['params'])) {
                    $params = array_merge($this->includeComponenToComponet['params_component']['params'], $temp);
                } else {
                    $params = $temp;
                }
                $union[] = $params;
            }

            $this->includeComponenToComponet['params_component']['params']['union'] = $union;
            $this->includeComponenToComponet['params_component']['params']['union_group'] =
                $this->params_component['group_query'];

            $dataSub = $this->SSRData($this->includeComponenToComponet['params_component'])['data'];
            $dataSub = json_decode($dataSub,true);
            //dd($dataSub,$this->includeComponenToComponet['params_component']);
            foreach ($data['data'] as $key => $item) {
                if(key_exists($item[$targetConcatData],$dataSub)){
                    $data['data'][$key]['data'] =$dataSub[$item[$targetConcatData]];
                }
            }
        }
        return $data;
    }
}

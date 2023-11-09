<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 03.01.2023
 * Time: 15:48
 */

namespace App\Models\System\Component;


use App\Models\System\RouteBilder;
use Illuminate\Support\Facades\Blade;

class Component{
    public function getAllComponent(){
       return [
            ['item'=>['lable'=>"Таблицы каталоги",'icon'=>null],'children'=>$this->getComponentGrid(),'level'=>1],
            ['item'=>['lable'=>"Вывод текста",'icon'=>null],'children'=>$this->getComponentInfo(),'level'=>1],
            ['item'=>['lable'=>"Формы",'icon'=>null],'children'=>$this->getComponentForm(),'level'=>1],
            ['item'=>['lable'=>"Выпвдвющие Списки",'icon'=>null],'children'=>$this->getComponentList(),'level'=>1],
            ['item'=>['lable'=>"Загрузочные",'icon'=>null],'children'=>$this->getComponentLoad(),'level'=>1],
            ['item'=>['lable'=>"Меню",'icon'=>null],'children'=>$this->getComponentMenu(),'level'=>1],
            ['item'=>['lable'=>"Пагинации",'icon'=>null],'children'=>$this->getComponentPagination(),'level'=>1],
            ['item'=>['lable'=>"Динамические вставкм переменных",'icon'=>null],'children'=>$this->getComponentDinamicVarible(),'level'=>1]
        ];
    }

    /*
     [{"id":12,"alias_url":"basicmaterials","name":"Basic Materials","sort":0,"id_name":12,"lang_id":1,"domain_name":"signatorinvestors.com","},{"id":3,"alias_url":"consumerdiscretionary","name":"Consumer Discretionary","sort":0,"id_name":3,"lang_id":1,"domain_name":"signatorinvestors.com","data":[{"up_down":1,"price":129.2,"price_s":"129.195","full_name":"Amazon.com Inc. ","id":1454,"short_name":"AMZN","url_icon":"icon_currence\/NASDAQ\/AMZNNASDAQ.svg","updated_at":"2023-07-12 16:21:40","type_inf_id":1,"category_id":3,"type_alias":"stocks","category_alias":"consumerdiscretionary"},{"up_down":1,"price":273.89,"price_s":"273.89","full_name":"Tesla Inc. ","id":8270,"short_name":"TSLA","url_icon":"icon_currence\/NASDAQ\/TSLANASDAQ.svg","updated_at":"2023-07-12 17:53:14","type_inf_id":1,"category_id":3,"type_alias":"stocks","category_alias":"consumerdiscretionary"},{"up_down":1,"price":242.47,"price_s":"242.47","full_name":"Visa Inc.","id":8473,"short_name":"V","url_icon":"icon_currence\/NASDAQ\/VNASDAQ.svg","updated_at":"2023-07-12 17:55:58","type_inf_id":1,"category_id":3,"type_alias":"stocks","category_alias":"consumerdiscretionary"},{"up_down":1,"price":148.81,"price_s":"148.81","full_name":"Procter & Gamble Company (The) ","id":6652,"short_name":"PG","url_icon":"icon_currence\/NASDAQ\/PGNASDAQ.svg","updated_at":"2023-07-12 17:31:27","type_inf_id":1,"category_id":3,"type_alias":"stocks","category_alias":"consumerdiscretionary"},{"up_down":1,"price":399.91,"price_s":"399.91","full_name":"Mastercard Incorporated ","id":5468,"short_name":"MA","url_icon":"icon_currence\/NASDAQ\/MANASDAQ.svg","updated_at":"2023-07-12 17:15:33","type_inf_id":1,"category_id":3,"type_alias":"stocks","category_alias":"consumerdiscretionary"}]},{"id":8,"alias_url":"consumerstaples","name":"Consumer Staples","sort":0,"id_name":8,"lang_id":1,"domain_name":"signatorinvestors.com","data":[{"up_down":0,"price":183.98,"price_s":"183.98","full_name":"PepsiCo Inc. ","id":6614,"short_name":"PEP","url_icon":"icon_currence\/NASDAQ\/PEPNASDAQ.svg","updated_at":"2023-07-12 17:30:56","type_inf_id":1,"category_id":8,"type_alias":"stocks","category_alias":"consumerstaples"},{"up_down":1,"price":59.86,"price_s":"59.855","full_name":"Coca-Cola Company (The) ","id":5128,"short_name":"KO","url_icon":"icon_currence\/NASDAQ\/KONASDAQ.svg","updated_at":"2023-07-12 17:11:00","type_inf_id":1,"category_id":8,"type_alias":"stocks","category_alias":"consumerstaples"},{"up_down":1,"price":71.53,"price_s":"71.53","full_name":"Mondelez International Inc. Class A ","id":5555,"short_name":"MDLZ","url_icon":"icon_currence\/NASDAQ\/MDLZNASDAQ.svg","updated_at":"2023-07-12 17:16:45","type_inf_id":1,"category_id":8,"type_alias":"stocks","category_alias":"consumerstaples"},{"up_down":1,"price":75.18,"price_s":"75.1801","full_name":"General Mills Inc. ","id":4123,"short_name":"GIS","url_icon":"icon_currence\/NASDAQ\/GISNASDAQ.svg","updated_at":"2023-07-12 16:57:28","type_inf_id":1,"category_id":8,"type_alias":"stocks","category_alias":"consumerstaples"},{"up_down":1,"price":56.18,"price_s":"56.18","full_name":"Monster Beverage Corporation","id":5727,"short_name":"MNST","url_icon":"icon_currence\/NASDAQ\/MNSTNASDAQ.svg","updated_at":"2023-07-12 17:19:03","type_inf_id":1,"category_id":8,"type_alias":"stocks","category_alias":"consumerstaples"}]},{"id":7,"alias_url":"energy","name":"Energy","sort":0,"id_name":7,"lang_id":1,"domain_name":"signatorinvestors.com","data":[{"up_down":1,"price":106.46,"price_s":"106.46","full_name":"Exxon Mobil Corporation ","id":8900,"short_name":"XOM","url_icon":"icon_currence\/NASDAQ\/XOMNASDAQ.svg","updated_at":"2023-07-12 18:01:40","type_inf_id":1,"category_id":7,"type_alias":"stocks","category_alias":"energy"},{"up_down":0,"price":3.99,"price_s":"3.9872837","full_name":"Chevron Corporation ","id":2952,"short_name":"CVX","url_icon":"icon_currence\/NASDAQ\/CVXNASDAQ.svg","updated_at":"2023-07-12 20:03:14","type_inf_id":1,"category_id":7,"type_alias":"stocks","category_alias":"energy"},{"up_down":1,"price":108.57,"price_s":"108.57","full_name":"ConocoPhillips ","id":2750,"short_name":"COP","url_icon":"icon_currence\/NASDAQ\/COPNASDAQ.svg","updated_at":"2023-07-12 16:39:01","type_inf_id":1,"category_id":7,"type_alias":"stocks","category_alias":"energy"},{"up_down":1,"price":56.53,"price_s":"56.53","full_name":"Schlumberger N.V. ","id":7605,"short_name":"SLB","url_icon":"icon_currence\/NASDAQ\/SLBNASDAQ.svg","updated_at":"2023-07-12 17:44:19","type_inf_id":1,"category_id":7,"type_alias":"stocks","category_alias":"energy"},{"up_down":1,"price":120.53,"price_s":"120.53","full_name":"EOG Resources Inc. ","id":3452,"short_name":"EOG","url_icon":"icon_currence\/NASDAQ\/EOGNASDAQ.svg","updated_at":"2023-07-12 16:48:26","type_inf_id":1,"category_id":7,"type_alias":"stocks","category_alias":"energy"}]},{"id":2,"alias_url":"finance","name":"Finance","sort":0,"id_name":2,"lang_id":1,"domain_name":"signatorinvestors.com","data":[{"up_down":1,"price":148.5,"price_s":"148.5","full_name":"JP Morgan Chase & Co. ","id":5003,"short_name":"JPM","url_icon":"icon_currence\/NASDAQ\/JPMNASDAQ.svg","updated_at":"2023-07-12 17:09:20","type_inf_id":1,"category_id":2,"type_alias":"stocks","category_alias":"finance"},{"up_down":1,"price":28.23,"price_s":"28.225","full_name":"Ally Financial Inc. ","id":1362,"short_name":"ALLY","url_icon":"icon_currence\/NASDAQ\/ALLYNASDAQ.svg","updated_at":"2023-07-12 16:20:26","type_inf_id":1,"category_id":2,"type_alias":"stocks","category_alias":"finance"},{"up_down":1,"price":29.46,"price_s":"29.455","full_name":"Bank of America Corporation ","id":1811,"short_name":"BAC","url_icon":"icon_currence\/NASDAQ\/BACNASDAQ.svg","updated_at":"2023-07-12 16:26:26","type_inf_id":1,"category_id":2,"type_alias":"stocks","category_alias":"finance"},{"up_down":1,"price":43.27,"price_s":"43.265","full_name":"Wells Fargo & Company ","id":8749,"short_name":"WFC","url_icon":"icon_currence\/NASDAQ\/WFCNASDAQ.svg","updated_at":"2023-07-12 17:59:40","type_inf_id":1,"category_id":2,"type_alias":"stocks","category_alias":"finance"},{"up_down":1,"price":175.02,"price_s":"175.0201","full_name":"Diamond Hill Investment Group Inc. Class A ","id":3093,"short_name":"DHIL","url_icon":"icon_currence\/NASDAQ\/DHILNASDAQ.svg","updated_at":"2023-07-12 16:43:38","type_inf_id":1,"category_id":2,"type_alias":"stocks","category_alias":"finance"}]},{"id":4,"alias_url":"healthcare","name":"Health Care","sort":0,"id_name":4,"lang_id":1,"domain_name":"signatorinvestors.com","data":[{"up_down":0,"price":453.77,"price_s":"453.77","full_name":"UnitedHealth Group Incorporated  (DE)","id":8399,"short_name":"UNH","url_icon":"icon_currence\/NASDAQ\/UNHNASDAQ.svg","updated_at":"2023-07-12 17:54:59","type_inf_id":1,"category_id":4,"type_alias":"stocks","category_alias":"healthcare"},{"up_down":0,"price":158.47,"price_s":"158.465","full_name":"Johnson & Johnson ","id":4991,"short_name":"J
     */

    public function getAllComponentWithCodeComponet(){
        $list = $this->getAllComponent();
        $this->createScritpAndTemlateComponent($list);
        return $list;
    }

    public function parametersComponet(string|array $name):array{
        $list  = include "config/parametComponent.php";

        if(is_array($name)){
             $return = [];
             foreach ($name as  $key => $item){
                 if(is_string($item) && is_numeric($key)) {
                     if (isset($list[$item]))
                         $return[$item] = $list[$item];
                 }
                 /*if(is_string($key) && is_array($item)){
                   // for
                 }*/
             }
             return $return;
        }

        return is_null($name)?$list:((isset($list[$name]))?$list[$name]:false);
    }

    public function getComponentGrid(){
      return [
                ['item'=>[
                    'id'     => 'component.gridComponent.simpleGrid',
                    'name'   => 'simpleGrid',
                    'lable'  => 'Список элементов',
                    'path'   => 'resources/views/component/gridComponent/simpleGrid.blade.php',
                    'url'    => 'component.gridComponent.simpleGrid',
                    'icon'   => null,
                    'params' => $this->parametersComponet(['pagination','autostart','url','name',
                        'data','globalData','column','template','globalParams','params','target',
                        'include','ssr','includeFromHeadToDown'])
                    ],
                'children'   => null,
                'level'      => 2
                ],
                ['item' => [
                        'id'     => 'component.gridComponent.groupGrid',
                        'name'   => 'groupGrid',
                        'lable'  => 'Список Компонентов',
                        'path'   => 'resources/views/component/gridComponent/groupGrid.blade.php',
                        'url'    => 'component.gridComponent.groupGrid',
                        'icon'   => null,
                        'params' => $this->parametersComponet(['pagination','autostart','url','name',
                                'data','indefication','name_group','include_component','globalData',
                                'column','union','indefication','union_group','template','globalParams','params','target',
                                'include','ssr','includeFromHeadToDown'])
                    ],
                  'children'    => null,
                  'level'       => 2
                ]
        ];
    }
    public function getComponentInfo(){
        return [
                ['item'=>[
                        'id'     => 'component.infoComponent.textInfo',
                        'name'   => 'textInfo',
                        'lable'  => 'Список элементов',
                        'path'   => 'resources/views/component/infoComponent/textInfo.blade.php',
                        'icon'   =>  null,
                        'url'    => 'component.infoComponent.textInfo',
                        'params' => $this->parametersComponet(['autostart','url','name','data','globalData','template',
                            'params','include','ssr','includeFromHeadToDown'])
                    ],
                  'children'      => null,
                  'level'         => 2
                ],
        ];
    }
    public function getComponentForm(){
        return [];
    }
    public function getComponentList(){
        return [
            ['item'=>[
                'id'     => 'component.listComponent.selectComponent',
                'lable'  => 'Выпадающий список',
                'name'   => 'selectComponent',
                'path'   => 'resources/views/component/listComponent/selectComponent.blade.php',
                'url'    => 'component.listComponent.selectComponent',
                'icon'   => null,
                'params' => $this->parametersComponet(['autostart','url','default_title','clear_name','data',
                    'globalData','name','template','params','change','focus','select','include','ssr','includeFromHeadToDown'])
                ],
              'children' => null,
              'level'    => 2
            ]
        ];
    }
    public function getComponentLoad(){
        return [
                ['item'=>[
                        'id'     => 'component.loadComponent.loadGlobalData',
                        'lable'  => 'Выгрузка данных в хранилеще',
                        'name'   => 'loadGlobalData',
                        'icon'   => null,
                        'path'   => 'resources/views/component/loadComponent/loadGlobalData.blade.php',
                        'url'    => 'component.loadComponent.loadGlobalData',
                        'params' => $this->parametersComponet(['url','params','name','clear_name','callback','data','ssr','includeFromHeadToDown'])
                    ],
                 'children' => null,
                 'level' => 2
                ],
                ['item'=>[
                        'id'     => 'component.loadComponent.sendData',
                        'lable'  => 'Пагинация',
                        'name'   => 'sendData',
                        'icon'   => null,
                        'path'   => 'resources/views/component/loadComponent/sendData.blade.php',
                        'url'    => 'component.loadComponent.sendData',
                        'params' => $this->parametersComponet(['url','name','callback','params', 'ssr', 'includeFromHeadToDown'])
                    ],
                 'children'      => null,
                 'level'         => 2
                ]
        ];
    }
    public function getComponentMenu(){
        return [
                ['item'=>[
                        'id'    => 'component.menuComponent.menu',
                        'lable' => 'Меню',
                        'name'  => 'menu',
                        'icon'  =>  null,
                        'path'  => 'resources/views/component/menuComponent/menu.blade.php',
                        'url'   => 'component.menuComponent.menu',
                        'params'=>$this->parametersComponet(['autostart','url','name','template','templateItem','data',
                            'globalData','params','typeMenu','include','ssr','includeFromHeadToDown'])
                    ],
                 'children'     => null,
                 'level'        => 2
                ]
        ];
    }
    public function getComponentPagination(){
        return [
                ['item'=>[
                        'id'     => 'component.paginationComponent.pagination',
                        'lable'  => 'Пагинация',
                        'name'   => 'pagination',
                        'icon'   => null,
                        'path'   => 'resources/views/component/paginationComponent/pagination.blade.php',
                        'url'    => 'component.paginationComponent.pagination',
                        'params' => $this->parametersComponet(['pagination','name','targetObject','data','template','target'])
                        ],
                 'children'     => null,
                 'level'        => 2
                ],
        ];
    }
    public function getComponentDinamicVarible(){
        return [
            ['item'=>[
                'id'     => 'component.variablComponent.date',
                'lable'  => 'Пагинация',
                'name'   => 'date',
                'icon'   => null,
                'path'   => 'resources/views/component/variablComponent/date.blade.php',
                'url'    => 'component.variablComponent.date',
                'params' => $this->parametersComponet('format')
              ],
              'children' => null,
              'level'    => 2
            ]
        ];
   }

    private function createScritpAndTemlateComponent(&$listTreeComponent){
        foreach ($listTreeComponent as &$item){
            if(isset($item['item']) && isset($item['item']['id'])){
                $item['script'] = $this->buildTemplateComponentHtml($item['item']['id'],$this->convertParamas($item['item']['params']));
                $item['template'] = $this->buildTemplateComponentCode($item['item']['id'],$this->convertParamas($item['item']['params']));

                if(isset($item['children']) && !is_null($item['children']))
                    $this->createScritpAndTemlateComponent($item['children']);
            } else {
                if(isset($item['children']) && !is_null($item['children']))
                    $this->createScritpAndTemlateComponent($item['children']);
            }
        }
    }

    private function convertParamas($params){
            foreach ($params as $key => &$item){
                switch ($key){
                    case "pagination":
                            foreach ($item as $keyPag => $paramPag)
                                $item[$keyPag] = $paramPag["default"];
                        break;
                    case "target":
                            foreach ($item['sub'] as $keyTarg => $paramTarg)
                                   $item['sub'][$keyTarg] = $paramTarg["default"];
                            $item = $item['sub'];
                        break;
                    default:
                        $item = (isset($item["default"]))?$item["default"]:null;
                      break;
                }
            }
            return $params;
    }


    public function getDefaultData($componetName,$keyData){
        $data = [
            'simpleGrid' =>[
                'data' => 'data":[
                    {"up_down":1,"price":40.7,"price_s":"40.7","full_name":"Freeport-McMoRan Inc. ","id":3681,"short_name":"FCX","url_icon":"icon_currence\/NASDAQ\/FCXNASDAQ.svg","updated_at":"2023-07-12 16:51:31","type_inf_id":1,"category_id":12,"type_alias":"stocks","category_alias":"basicmaterials"},
                    {"up_down":1,"price":45.24,"price_s":"45.24","full_name":"Newmont Corporation","id":5976,"short_name":"NEM","url_icon":"icon_currence\/NASDAQ\/NEMNASDAQ.svg","updated_at":"2023-07-12 17:22:23","type_inf_id":1,"category_id":12,"type_alias":"stocks","category_alias":"basicmaterials"},
                    {"up_down":0,"price":58.85,"price_s":"58.85","full_name":"Fastenal Company ","id":3643,"short_name":"FAST","url_icon":"icon_currence\/NASDAQ\/FASTNASDAQ.svg","updated_at":"2023-07-12 16:51:00","type_inf_id":1,"category_id":12,"type_alias":"stocks","category_alias":"basicmaterials"},
                    {"up_down":1,"price":133.25,"price_s":"133.25","full_name":"Packaging Corporation of America ","id":6705,"short_name":"PKG","url_icon":"icon_currence\/NASDAQ\/PKGNASDAQ.svg","updated_at":"2023-07-12 17:32:11","type_inf_id":1,"category_id":12,"type_alias":"stocks","category_alias":"basicmaterials"},
                    {"up_down":1,"price":"0.544","price_s":"0.544","full_name":"Solitario Zinc Corp. ","id":8914,"short_name":"XPL","url_icon":"icon_currence\/NASDAQ\/XPLNASDAQ.svg","updated_at":"2023-07-12 18:01:52","type_inf_id":1,"category_id":12,"type_alias":"stocks","category_alias":"basicmaterials"}]',
                'column'=>'[{"name":"Currencies"},{"name":"Price"}]',
                'template'=>"cart_item",
            ],
            'groupGrid' =>[
                    'data' => '[
                    {"page":1,"name":"",
                    "data":[
                            {"up_down":0,"price":30513,"price_s":"30513.309","full_name":"Bitcoin","id":1,"short_name":"BTC","url_icon":"icon_currence\/DIGITAL\/BTCUSD.svg","updated_at":"2023-07-12 16:02:10","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":1870.1,"price_s":"1870.0908","full_name":"Ethereum","id":2,"short_name":"ETH","url_icon":"icon_currence\/DIGITAL\/ETHUSD.svg","updated_at":"2023-07-12 20:02:11","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":"1.000034","price_s":"1.0000336","full_name":"Tether","id":3,"short_name":"USDT","url_icon":"icon_currence\/DIGITAL\/USDTUSD.svg","updated_at":"2023-07-12 16:02:12","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":244.33,"price_s":"244.32994","full_name":"BNB","id":5,"short_name":"BNB","url_icon":"icon_currence\/DIGITAL\/BNBUSD.svg","updated_at":"2023-07-12 16:02:13","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"1.00000060","price_s":"1.0000006","full_name":"USD Coin","id":4,"short_name":"USDC","url_icon":"icon_currence\/DIGITAL\/USDCUSD.svg","updated_at":"2023-07-12 20:02:13","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.468","price_s":"0.46821436","full_name":"XRP","id":7,"short_name":"XRP","url_icon":"icon_currence\/DIGITAL\/XRPUSD.svg","updated_at":"2023-07-12 20:02:16","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.291","price_s":"0.29084185","full_name":"Cardano","id":8,"short_name":"ADA","url_icon":"icon_currence\/DIGITAL\/ADAUSD.svg","updated_at":"2023-07-12 16:02:15","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.0642","price_s":"0.06419345","full_name":"Dogecoin","id":10,"short_name":"DOGE","url_icon":"icon_currence\/DIGITAL\/DOGEUSD.svg","updated_at":"2023-07-12 20:02:18","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.737","price_s":"0.7368339","full_name":"Polygon","id":13,"short_name":"MATIC","url_icon":"icon_currence\/DIGITAL\/MATICUSD.svg","updated_at":"2023-07-12 16:02:20","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.1000","price_s":"0.99977905","full_name":"Binance USD","id":6,"short_name":"BUSD","url_icon":"icon_currence\/DIGITAL\/BUSDUSD.svg","updated_at":"2023-07-12 20:02:15","type_inf_id":2,"page":"1","type_alias":"cryptocurrencies","category_alias":null}]},
                    {"page":2,"name":"",
                     "data":[
                            {"up_down":1,"price":4.16,"price_s":"4.1556","full_name":"Solana","id":9,"short_name":"SOL","url_icon":"icon_currence\/DIGITAL\/SOLUSD.svg","updated_at":"2023-07-12 17:45:51","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":5.18,"price_s":"5.1804123","full_name":"Polkadot","id":11,"short_name":"DOT","url_icon":"icon_currence\/DIGITAL\/DOTUSD.svg","updated_at":"2023-07-12 16:02:18","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":95.04,"price_s":"95.037704","full_name":"Litecoin","id":20,"short_name":"LTC","url_icon":"icon_currence\/DIGITAL\/LTCUSD.svg","updated_at":"2023-07-12 20:02:26","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":"0.449","price_s":"0.449","full_name":"TRON","id":15,"short_name":"TRX","url_icon":"icon_currence\/DIGITAL\/TRXUSD.svg","updated_at":"2023-07-12 17:53:04","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.00000739","price_s":"7.389689E-6","full_name":"Shiba Inu","id":16,"short_name":"SHIB","url_icon":"icon_currence\/DIGITAL\/SHIBUSD.svg","updated_at":"2023-07-12 20:02:23","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.999","price_s":"0.9985268","full_name":"Dai","id":12,"short_name":"DAI","url_icon":"icon_currence\/DIGITAL\/DAIUSD.svg","updated_at":"2023-07-12 20:02:20","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":13.05,"price_s":"13.050102","full_name":"Avalanche","id":14,"short_name":"AVAX","url_icon":"icon_currence\/DIGITAL\/AVAXUSD.svg","updated_at":"2023-07-12 16:02:20","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.000282","price_s":"0.00028178125","full_name":"Uniswap","id":17,"short_name":"UNI","url_icon":"icon_currence\/DIGITAL\/UNIUSD.svg","updated_at":"2023-07-12 20:02:24","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":6.22,"price_s":"6.221478","full_name":"Chainlink","id":23,"short_name":"LINK","url_icon":"icon_currence\/DIGITAL\/LINKUSD.svg","updated_at":"2023-07-12 20:02:29","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":23889,"price_s":"23889.186","full_name":"Wrapped Bitcoin","id":18,"short_name":"WBTC","url_icon":"icon_currence\/DIGITAL\/WBTCUSD.svg","updated_at":"2023-07-12 16:02:23","type_inf_id":2,"page":"2","type_alias":"cryptocurrencies","category_alias":null}]},
                     {"page":3,"name":"",
                     "data":[
                            {"up_down":1,"price":4.06,"price_s":"4.0589314","full_name":"UNUS SED LEO","id":19,"short_name":"LEO","url_icon":"icon_currence\/DIGITAL\/LEOUSD.svg","updated_at":"2023-07-12 20:02:26","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":18.81,"price_s":"18.811672","full_name":"Ethereum Classic","id":24,"short_name":"ETC","url_icon":"icon_currence\/DIGITAL\/ETCUSD.svg","updated_at":"2023-07-12 16:02:28","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"0.0957","price_s":"0.09572371","full_name":"Stellar","id":26,"short_name":"XLM","url_icon":"icon_currence\/DIGITAL\/XLMUSD.svg","updated_at":"2023-07-12 20:02:31","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":9,"price_s":"9.000047","full_name":"Cosmos","id":27,"short_name":"ATOM","url_icon":"icon_currence\/DIGITAL\/ATOMUSD.svg","updated_at":"2023-07-12 20:02:32","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":163.34,"price_s":"163.34138","full_name":"Monero","id":28,"short_name":"XMR","url_icon":"icon_currence\/DIGITAL\/XMRUSD.svg","updated_at":"2023-07-12 20:02:32","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":21.21,"price_s":"21.21","full_name":"Bitcoin Cash","id":30,"short_name":"BCH","url_icon":"icon_currence\/DIGITAL\/BCHUSD.svg","updated_at":"2023-07-12 16:27:25","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":4.2,"price_s":"4.1957874","full_name":"Filecoin","id":40,"short_name":"FIL","url_icon":"icon_currence\/DIGITAL\/FILUSD.svg","updated_at":"2023-07-12 20:02:42","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":43.19,"price_s":"43.19305","full_name":"OKB","id":54,"short_name":"OKB","url_icon":"icon_currence\/DIGITAL\/OKBUSD.svg","updated_at":"2023-07-12 16:02:52","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":0,"price":"1.90","price_s":"1.8981879","full_name":"Lido DAO","id":77,"short_name":"LDO","url_icon":"icon_currence\/DIGITAL\/LDOUSD.svg","updated_at":"2023-07-12 16:03:11","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null},
                            {"up_down":1,"price":"1.64","price_s":"1.6381633","full_name":"FTX Token","id":21,"short_name":"FTT","url_icon":"icon_currence\/DIGITAL\/FTTUSD.svg","updated_at":"2023-07-12 16:02:26","type_inf_id":2,"page":"3","type_alias":"cryptocurrencies","category_alias":null}]
                            }
                            ],',
                'template'=>"group1",
                'include_component' =>''
            ],
            'textInfo' =>[
                'data'=>'[{text:"какойта текст"}]',
                'template'=>'<div><p>{{text}}<p></div>'
            ],
            'selectComponent' =>[
                'data'=>'[
                    {"up_down":1,},
                    {"up_down":1,},
                    {"up_down":0,},
                    {"up_down":1,},
                    {"up_down":1,}]',
                'template'=>'<div><p>{{text}}<p></div>'
            ],
            'menu' =>[
                'data'=>'[{text:"какойта текст"}]',
                'template'=>'<div><p>{{text}}<p></div>'
            ],
            'pagination' =>[
                'data'=>'{"pageSize":3,
                          "page":1,
                          "totalCount":100,
                          "typePagination":2,
                         }',
                'template'=>'template_1'
            ],
        ];



    }

    private function margeAllGroup(){
        $return = [];
        $return = array_merge($return, $this->getComponentGrid());
        $return = array_merge($return, $this->getComponentInfo());
        $return = array_merge($return, $this->getComponentPagination());
        $return = array_merge($return, $this->getComponentMenu());
        $return = array_merge($return, $this->getComponentLoad());
        $return = array_merge($return, $this->getComponentList());
        $return = array_merge($return, $this->getComponentDinamicVarible());
        $return = array_merge($return, $this->getComponentForm());
        return $return;
    }

    private function findComponent($field,$value){
        foreach ($this->margeAllGroup() as $key => $item){
            if(isset($item['item'][$field]) && $item['item'][$field] == $value){
                return $item['item'];
            }
        }
        return false;
    }

    public function buildTemplateComponentHtml($id,$paramsComponent){

        $template = [];
        $template[] = "@extends('component.layoutsForGenerikAloneComponent')";
        $template[] = "@push('content')";
        $template[] =  str_replace([ComponentBuilder::START_COMPONENT,ComponentBuilder::END_COMPONENT],
            "",
           $this->buildTemplateComponentCode($id,$paramsComponent));
        $template[] = "@endpush";

       $script =  Blade::render(implode("\n",$template),[],true);
       return $script;
    }

    /*
     * <!--head-start-->\r\n
     * <!--style-start-->\r\n
     * <link type=\"text/css\"  rel=\"stylesheet\" href=\"/js/component/gridComponent/simpleGrid.css\">\n
     *   <!--style-end-->\r\n
     *  <!--js-lib-component-head-start-->\r\n        <!--js-lib-component-head-end-->\r\n
     * <!--head-end-->\r\n
     * <!--body-start-->\r\n
     * <!--body-page-start-->\r\n<!--body-page-end-->\r\n\r\n
     * <!--js-lib-component-start-->\r\n
     * <script src=\"/js/component/gridComponent/simpleGrid.js\"></script>\n
     * <!--js-lib-component-end-->\r\n\r\n
     * <!--js-code-component-start-->\r\n
     * <!--js-code-component-end-->\r\n\r\n
     * <!--js-code-component-load-start-->\r\n<!--js-code-component-load-end-->\r\n
     * <!--body-end-->\r\n\r\n
     */

    public function buildTemplateComponentCode($id,$paramsComponent){
      $commandComponent = [];
      $paramsComponent['admin'] = 'true';
      $paramsComponent_ = $this->findComponent('id',$id);
      $commandComponent[] = ComponentBuilder::START_COMPONENT;
      $commandComponent[] = "@include('component_build',[";
      $commandComponent[] = "'component'=>'{$paramsComponent_['id']}',";
      $commandComponent[] = "'params_component'=>".\App\Models\System\HelperFunction::array_to_string($paramsComponent).",";
      if(isset($paramsComponent['includeComponent']['component']))
        $commandComponent[] = "'add_link_component'=>'{$paramsComponent['includeComponent']['component']}'";

      $commandComponent[] = "])";
      $commandComponent[] = ComponentBuilder::END_COMPONENT;
      return implode("\n",$commandComponent);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26.08.2022
 * Time: 16:02
 */

namespace App\System\Parser;
use App\Models\CoreEngine\Model\ModyfiModel;
use App\Models\System\SystemLog;
use Illuminate\Support\Facades\Http;
use mysql_xdevapi\Exception;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use PHPHtmlParser\Selector\Selector;

//https://www.tradingview.com/symbols/MKRUSD/?utm_campaign=single-quote&utm_medium=widget&utm_source=pandaforecast.com
class UrlTargetParser{

    CONST TARGET_SEARCH_TAG      = 1;
    CONST TARGET_SEARCH_ATTR     = 2;
    CONST TARGET_SEARCH_CLASS    = 3;
    CONST TARGET_SEARCH_ID       = 4;
    CONST TARGET_SEARCH_TEXT     = 5;
    CONST TARGET_SEARCH_STRING   = 6;

    CONST PARAMS_TYPE_CHPU          = 1;
    CONST PARAMS_TYPE_PARAM         = 2;

    CONST TAKE_DATE_ATTR         = 1;
    CONST TAKE_DATE_STRING       = 2;
    CONST TAKE_DATE_HTML         = 3;
    CONST TAKE_DATE_HTML_OUT     = 4;

    private $url                 = null;

    private $domhtml             = "";
    private $bodyOriginal        = "";
    private $params              = []; //[[type,name,value]]
    private $target              = []; //[[target,action,field,type]]

    private $groupByKey          = false;

    private $tamplateParam       = ['type','name','value'];
    private $tamplateTarget      = ['target','action','takeData','attr'];
    public function __construct(){}

    public function parserFromText($text){
        if(!empty($text)) {
            $this->domhtml = new Dom();
            $option = new Options();
            $option->setRemoveSmartyScripts(false);
            $option->setRemoveScripts(false);
            $option->setPreserveLineBreaks(true);
            $this->domhtml->loadStr($text,$option);
            return $this->searchTarget($this->target,$this->domhtml);
        }
        return false;
    }
    public function setGroupByKey($falg){
        $this->groupByKey =  (bool)$falg;
        return $this;
    }

    public function setParamsUrl($paramsArr){
        if(is_array($paramsArr) && isset($paramsArr[0]) && count(array_diff(array_keys($paramsArr[0]),$this->tamplateParam)) == 0){
            $this->params = $paramsArr;
            return $this;
        }else{
            throw new Exception('Некорекный формат массива');
        }
    }
    public function addParamUrl($paramArr){
        if(count(array_diff(array_keys($paramArr),$this->tamplateTarget)) == 0){
            $this->params[] = $paramArr;
            return $this;
        }else{
            throw new Exception('Некорекный формат массива');
        }
    }
    public function changeParamUrl($name,$paramArr){
       return $this->changeDataTemplates($this->getIndexParam($name),$paramArr,$this->params);
    }

    public function setTargets($targetsArr){
        $keys =  array_keys($targetsArr);
        if(is_array($targetsArr) && isset( $targetsArr[$keys[0]]) && count(array_diff(array_keys($targetsArr[$keys[0]]),$this->tamplateTarget)) == 0){
            $this->target = $targetsArr;
            return $this;
        }else{
            throw new Exception('Некорекный формат массива');
        }
    }
    public function addTarget($targetArr){
        if(count(array_diff(array_keys($targetArr),$this->tamplateTarget)) == 0){
            $this->target[] = $targetArr;
            return $this;
        }else{
            throw new Exception('Некорекный формат массива');
        }
    }
    public function changeTarget($field,$targetArr){
       return $this->changeDataTemplates($this->getIndexTarget($field),$targetArr,$this->target);
    }

    public function setModel($model){
        if($model instanceof  ModyfiModel){
            $this->model = $model;
        }else{
            throw  new Exception('Необходимо использовать модел ларавел');
        }
        return $this;
    }
    public function setUrl($url){
        if(strpos($url,'http://') !== false || strpos($url,'https://') !== false ){
            $this->url = $url;
        }else{
            throw new Exception('Некореректный урл');
        }
        return $this;
    }
    public function runParser(){
        return $this->parserReuest($this->sendQuery($this->params));
    }
    private function sendQuery($param){
       try {
           $response = Http::async()->get($this->buildUrlParams($param))->wait();
           if ($response->successful()) {
               $this->bodyOriginal = $response->body();
               return $this->bodyOriginal;
           } else {
               return false;
           }
       }catch (\Throwable $e){
           SystemLog::addLog("Parser image",json_encode($param),"");
       }
    }
    /*
     *   case 'outerhtml':
                return $this->outerHtml();
            case 'innerhtml':
                return $this->innerHtml();
            case 'innertext':
                return $this->innerText();
            case 'text':
                return $this->text();
            case 'tag':
                return $this->getTag();
            case 'parent':
                return $this->getParent();
        }
     */
    private function parserReuest($body){
        if(!empty($body)) {
            $this->domhtml = new Dom;
            $this->domhtml->loadStr($body);

            return $this->searchTarget($this->target,$this->domhtml);
        }else{
            SystemLog::addLog("Импорт изображений",$this->params ,'404',SystemLog::CODE_ERROR);
            return null;
        }
    }
    private function searchTarget($target, &$domhtml){
        $result = null;

        foreach ($target as $key => $rule){
            $r =  new Selector($rule['target']);
            $tag = $domhtml->find($rule['target']);
            if(count($tag) == 1){
                if($this->groupByKey){
                    $result[$key][] = $this->takeData($tag,$rule);
                }else{
                    $result[] = $this->takeData($tag,$rule);
                }
            }else if(count($tag) > 1){
                foreach ($tag as $itemTag){
                    if($this->groupByKey){
                        $result[$key][] = $this->takeData($itemTag,$rule);
                    }else{
                        $result[] = $this->takeData($itemTag,$rule);
                    }
                }
            }else{
                if($this->groupByKey){
                    $result[$key] = NULL;
                }else{
                    $result[] = NULL;
                }

            }
        }
       return $result;
    }
    private function takeData($tag,$rule){
        switch ($rule['takeData']){
            case self::TAKE_DATE_ATTR:
                return  ($rule['attr'] == "all")?$tag->getAttributes():$tag->getAttribute($rule['attr']);
                break;
            case self::TAKE_DATE_STRING:
                return $tag->text;
                break;
            case self::TAKE_DATE_HTML:
                return $tag->innerhtml;
                break;
            case self::TAKE_DATE_HTML_OUT:
                return $tag->outerhtml;
                break;
            default:
                throw  new Exception("Нету типа выбираемых данных");
                break;
        }
    }

    private function joinParametrs(){

    }

    private function getIndexParam($name){
         return $this->findIndexByName($name,'name',$this->params);
    }
    private function getIndexTarget($name){
        return $this->findIndexByName($name,'field',$this->target);
    }
    private function changeDataTemplates($index,$paramArr,&$data){
        if(!empty($index)){
            if(count(array_diff(array_keys($paramArr),array_keys($data[$index]))) == 0) {
                foreach ($paramArr as $key => $value) {
                    $data[$index][$key] = $value;
                }
            }else{
                throw  new Exception('Переданы ключи которые отсутвуют');
            }
        }else{
            throw  new Exception('Индекс не найдет');
        }
        return clone $data;
    }
    private function buildUrlParams($params){
        $chpu ="";
        $param = "?";
        foreach ($params as $key => $item){
            if($item['type'] == self::PARAMS_TYPE_CHPU){
                $chpu.="/".$item['value'];
            }
            if($item['type'] == self::PARAMS_TYPE_PARAM){
                $param.= $item['name']."=".$item['value']."&";
            }
        }
        $chpu.="/";
        $param = substr($param,0,strlen($param)-1);
        return $this->url.$chpu.$param;
    }
    private function findIndexByName($name,$target,$data){
        if(is_array($data) && isset($data[0])) {
            for ($i = 0, $j = count($data);true; $i++,$j--) {
                if($data[$i][$target] == $name || $data[$j][$target] == $name){
                    return ($data[$i][$target] == $name)?$i:$j;
                }
                if($j == 0) return null;
            }
        }else{
            return false;
        }
    }
}

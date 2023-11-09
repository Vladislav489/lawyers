<?php
namespace App\Models;
use App\Models\UrlTargetParser;






class ParserFaced {
    private array $Rules;
    private array $step = [];
    private string $type = null;
    private int $stepCount = 0;
    public function setRules(array $array){
        $this->Rules = $array;
        return $this;
    }

    public function collectLinks(){}


    public function setTypeParaser(string $type){
        $this->type = $type;
        return this;
    }
    public function getStep(){
        return $this->step;
    }

    private function parserUrl($url){
        $array_numberParam = [];
        $array_ParaserParam = [];
        $params = [];
        $max_mun = 0;
        if(preg_match_all("/(\{(.+?)\})/",$url,$array_numberParam)){
            $array_numberParam = $array_numberParam[0];
            foreach($array_numberParam as $item){
                $paramsRule = [
                    'template' => $item
                ];
                $item = str_replace(['{','}'],'',$item);
                $range  = explode('-',$item);
                $paramsRule['rule'] = range($range[0], $range[1]);
                $paramsRule['count'] = $range[1] - $range[0];
                $paramsRule['type'] = 'number';
                $max_mun = ($range[1] > $max_mun )?$range[1]:$max_mun;
                $params[] = $paramsRule;
            }
        }
        if(preg_match_all("/(\[(.+?)\])/",$url,$array_ParaserParam)) {
            $array_ParaserParam = $array_ParaserParam[0];
            foreach ($array_ParaserParam as $item) {
                $paramsRule = ['template' => $item];
                $array_step = [];
                if(str_contains($item, 'data')) {
                    $data = explode(":",$item);
                    if (str_contains($item, 'step')) {
                        if(str_contains($data[1], ',')){
                            $field = explode(",",$data[1]);
                            $step = explode("_",$field[0])[1];
                            $colum = $field[1];
                            if(isset($this->step[$step])) {
                                $array_step = array_map(function ($key, $value) use ($colum) {
                                    return $value[$colum];
                                }, $this->step[$step]);
                            }
                        }else{
                            $step = explode("_",$data[1])[1];
                            if(isset($this->step[$step]))
                                $array_step = $this->step[$step];
                        }
                    }
                    if(count($array_step) >0) {
                        $paramsRule['rule'] = $array_step;
                        $paramsRule['count'] = count($array_step);
                        $paramsRule['type'] = 'array';
                        $params[] = $paramsRule;
                    }
                }
                if(str_contains($item, 'array')) {
                    $array = explode(":",$item)[1];
                    $array = explode(',',str_replace(['[',']'],'',$array));
                    $count = count($array);
                    $max_mun = ($max_mun < $count)?$count:$max_mun;
                    $paramsRule['rule'] = $array;
                    $paramsRule['count'] = $count;
                    $paramsRule['type'] = 'array';
                    $params[] = $paramsRule;
                }
            }
        }
        $params = array_reverse($params);
        if(count($params) > 0) {
            return $this->Comb($params, $url);
        } else {
            return $url;
        }
    }
    public function Comb($listArray, $url){
        $parasm = $urls = [];
        $countGroupt = count($listArray);
        for ($pounterGroup = 0; $pounterGroup < $countGroupt; $pounterGroup++){
            foreach ($listArray[$pounterGroup]['rule'] as $item) {
                if(!isset($parasm[$pounterGroup-1])) {
                    $parasm[$pounterGroup][] = [$listArray[$pounterGroup]['template'] => $item];
                } else {
                    foreach ($parasm[$pounterGroup-1] as $itemUrl)
                        $parasm[$pounterGroup][] = array_merge([$listArray[$pounterGroup]['template'] => $item],$itemUrl);
                }
            }
        }
        foreach ($parasm[$countGroupt-1] as $item)
            $urls[] = str_replace(array_keys($item),array_values($item),$url);
        unset($parasm);
        return $urls;
    }
    private function steParserJson($rule) {
        if(str_contains($rule['url'],'step_')){
            $step =  explode("_",$rule['url'])[1];
            $urls = $this->step[$step-1];
        }else{
            $urls = $this->parserUrl($rule['url']);
            if(!is_array($urls))
                $urls = [$urls];
        }



    }
    private function steParserHtml($rule){
        if(str_contains($rule['url'],'step_')){
            $step =  explode("_",$rule['url'])[1];
            $urls = $this->step[$step-1];
        }else{
            $urls = $this->parserUrl($rule['url']);
            if(!is_array($urls))
                $urls = [$urls];
        }
        foreach ( $urls as $item){
            $parset = new UrlTargetParserHtml();
            $parset->setUrl($item);
            $parset->setTargets($rule['targetSearch']);
            if(!isset($this->step[$this->stepCount])){
                $this->step[$this->stepCount] = [];
            }
            $data = $parset->RunParser();
            $this->step[$this->stepCount] = array_merge($this->step[$this->stepCount],$data);
        }

        $this->step[$this->stepCount] = array_unique($this->step[$this->stepCount]);
        $this->stepCount++;

        if(isset($rule['target']))
            $this->steParserHtml($rule['target']);
    }
    public function RunParser(){
       switch ($this->type ){
           case 'html':
               $this->steParserHtml($this->Rules);
               break;
           case 'json':
               $this->steParserJson($this->Rules);
               break;
           case 'xml':
               $this->steParserXml($this->Rules);
               break;
           case 'csv':
               $this->steParserCsv($this->Rules);
               break;
       }

       dd(implode("\n",$this->step[0]));
    }
}

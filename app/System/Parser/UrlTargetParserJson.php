<?php


namespace App\System\Parser;

/* [
       'url'=>'main_page',
       'target'=>[
           'url'=>'category',
           'targetSearch'=>[
               [
                   'target'    => "data.index.test.index.item",
                   'takeData'  => UrlTargetParser::TAKE_DATE_VALUE,
                   'page_params' =>['name'=>'code','value']
                   'colname' => 'nameProduct'
               ],

                 [
                   'target'    => "data.index.test.index.item",
                   'takeData'  => UrlTargetParser::TAKE_DATE_VALUE,
                   'page_params' =>['name'=>'code','value']
                   'colname' => 'nameProduct'
               ],

                 [
                   'target'    => "data.index.test.index.item",
                   'takeData'  => UrlTargetParser::TAKE_DATE_VALUE,
                   'page_params' =>['name'=>'code','value']
                   'colname' => 'nameProduct'
               ],
               'target'=>[
                    'url'=>'product',
                    'targetSearch'=>[
                        [
                           'target'    => "data.index.test.index.item",
                           'takeData'  => UrlTargetParser::TAKE_DATE_VALUE,
                           'page_params' =>['name'=>'code','value']
                           'colname' => 'nameProduct'
                       ],

                       [
                           'target'    => "data.index.test.index.item",
                           'takeData'  => UrlTargetParser::TAKE_DATE_VALUE,
                           'page_params' =>['name'=>'code','value']
                           'colname' => 'nameProduct'
                       ],
                    ]
               ]
       ]
    ];*/
class UrlTargetParserJson{
    CONST TAKE_DATE_JSON       = 1;
    CONST TAKE_DATE_LIST       = 2;
    CONST TAKE_DATE_VALUE      = 3;

    private $json                = "";

    private $target              = []; //[[target,action,field,type]]

    private $groupByKey          = false;
    private $tamplateParam       = [];
    private $tamplateTarget      = [];
    public function __construct(){}

    public function parserFromText($text){
        if(!empty($text)) {
            $this->json  = $text;
            return $this->searchTarget($this->target,$this->json);
        }
        return false;
    }

    public function RunParser(){
        $body = $this->sendQuery($this->params);
        return $this->parserReuest($body);
    }
    private function parserReuest($body){
        if(!empty($body)) {
            if( !preg_match('/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/',
                preg_replace('/"(\\.|[^"\\\\])*"/', '', $body))){
                return $this->searchTarget($this->target,$body);
            }else{
                dd("Не джейсон");
            }
        }else{
            return null;
        }
    }


    private function findJsonByPath($targe){}


    protected function searchTarget($target, $body){
        $result = null;
        $body = json_decode($body,true);
        foreach ($target as $key => $rule){
            $r =  new Selector($rule['target']);




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




        return $result;
    }
    private function takeData($tag,$rule){
        switch ($rule['takeData']){
            case self::TAKE_DATE_JSON:
                break;
            case self::TAKE_DATE_VALUE:
                break;
            case self::TAKE_DATE_LIST:
                break;
             default:
                dd("Нету типа выбираемых данных");
                break;
        }
    }





}

<?php
namespace App\System\Parser;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use PHPHtmlParser\Selector\Selector;

/* [
       'url'=>'main_page',
       'target'=>[
           'url'=>'category',
           'targetSearch'=>[
               [
                   'target'    => "div.tv-category-header__title-line > img.tv-category-header__icon",
                   'takeData'  => UrlTargetParser::TAKE_DATE_ATTR,
                   'attr'      => 'src',
                   'page_params' =>['name'=>'code','value']
               ],

               [
                   'target'    => "div.tv-category-header__title-line > img.tv-category-header__icon",
                   'takeData'  => UrlTargetParser::TAKE_DATE_ATTR,
                   'attr'      => 'src',
                   'data_dave' =>'',
               ],

               [
                   'target'    => "div.tv-category-header__title-line > img.tv-category-header__icon",
                   'takeData'  => UrlTargetParser::TAKE_DATE_ATTR,
                   'attr'      => 'src',
                   'page_params' =>''
               ],
               'target'=>[
                    'url'=>'product',
                    'targetSearch'=>[
                       [
                           'target'    => "div.tv-category-header__title-line > img.tv-category-header__icon",
                           'takeData'  => UrlTargetParser::TAKE_DATE_ATTR,
                           'attr'      => 'src',
                           'page_params' =>['name'=>'code','value']
                       ],

                       [
                           'target'    => "div.tv-category-header__title-line > img.tv-category-header__icon",
                           'takeData'  => UrlTargetParser::TAKE_DATE_ATTR,
                           'attr'      => 'src',
                           'data_dave' =>'',
                       ],
                    ]
               ]
       ]
    ];*/

class UrlTargetParserHtml extends UrlTargetParser {
    CONST TAKE_DATE_ATTR         = 1;
    CONST TAKE_DATE_STRING       = 2;
    CONST TAKE_DATE_HTML         = 3;
    CONST TAKE_DATE_HTML_OUT     = 4;

    private   $domhtml             = "";
    protected $tamplateParam       = ['type','name','value'];
    protected $tamplateTarget      = ['target','action','takeData','attr'];

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
    public function RunParser(){
       $this->parserReuest($this->sendQuery($this->params));
    }

    protected function searchTarget($target, $body){
        $result = null;
        foreach ($target as $key => $rule){
            $r =  new Selector($rule['target']);
            $tag = $body->find($rule['target']);
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
    private function parserReuest($body){
        if(!empty($body)) {
            $this->domhtml = new Dom;
            $this->domhtml->loadStr($body);
            return $this->searchTarget($this->target,$this->domhtml);
        }else{
            return null;
        }
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
                dd("Нету типа выбираемых данных");
                break;
        }
    }
}

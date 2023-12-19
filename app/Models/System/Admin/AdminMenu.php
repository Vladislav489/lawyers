<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 20.02.2023
 * Time: 12:36
 */

namespace App\Models\System\Admin;


use App\Models\CoreEngine\LogicModels\ComboSite\MenuLogic;
use App\Models\System\General\Site;
use Illuminate\Support\Facades\Auth;

class AdminMenu extends MenuLogic {
    public function __construct($params = [],$select =['*'],$callback = null){
            $params['is_deleted'] = 0;
            $this->params = $params;
            if(Auth::id() != 1)
                $this->params['site'] = (string)Site::getSiteId();
            $this->limit = false;
            $this->engine = new SystemMenu();
            $this->query = $this->engine->newQuery();
            $this->getFilter();
            $this->compileGroupParams();
            parent::__construct($this->engine,$this->params,$select);
    }
    public static function defaultMenu(){
        return  [
            ['id'=>1,'parent_id'=>0,'site_id'=>1,'lable'=>"Главная",'lang_id'=>1,
                'url'=>"/admin",'icon'=>"fa-cubes"
            ],
            ['id'=>2,'parent_id'=>0,'site_id'=>1,'lable'=>"Управление данными",'lang_id'=>1,
                'url'=>"#",'icon'=>"fa-database"
            ],
            ['id'=>4,'parent_id'=>2,'site_id'=>1,'lable'=>"SEO",'lang_id'=> 1,
                'url'=>"",'icon'=>"fa-file-text-o"
            ],
            ['id'=>5,'parent_id'=>2,'site_id'=>1,'lable'=>"Статические страницы",'lang_id'=>1,
                'url'=>"",'icon'=>"fa-file-code-o"
            ],
            ['id'=>6,'parent_id'=>0,'site_id'=>1,'lable'=>"Системные логи",'lang_id'=>1,
                'url'=>"",'icon'=>"fa-area-chart"
            ],
            ['id'=>7,'parent_id'=>6,'site_id'=>1,'lable'=>"Логи Системы",'lang_id'=>1,
                'url'=>"/admin/log", 'icon'=>"fa-bell-o"
            ],
            ['id'=>8,'parent_id'=>12,'site_id'=>1,'lable'=>"Меню Админ Панели",'lang_id'=>1,
                'url'=>"/admin/adminmenu",'icon'=>"fa-list-ul"
            ],
            ['id'=>9,'parent_id'=>0,'site_id'=>1,'lable'=> "Управление страницами",'lang_id'=>1,
                'url'=>"",'icon'=>"fa-database"
            ],
            ['id'=>10,'parent_id'=>9,'site_id'=>1,'lable'=>"Управление view",'lang_id'=>1,
                'url'=>"/admin/editview",'icon'=>"fa-file-text"
            ],
            ['id'=>11,'parent_id'=>9,'site_id'=>1,'lable'=>"Управление Путями",'lang_id'=>1,
                'url'=>"/admin/routepage",'icon'=>"fa-file-text"
            ],
            ['id'=>12,'parent_id'=>0,'site_id'=>1,'lable'=>"Управление сайтом",'lang_id'=>1,
                'url'=>"",'icon'=>""
            ],
            ['id'=>13,'parent_id'=>12,'site_id'=>1,'lable'=>"Управление доменами",'lang_id'=>1,
                'url'=>"/admin/editsite",'icon'=>""
            ],
            ['id'=>14,'parent_id'=>12,'site_id'=>1,'lable'=>"Все доступные Пути",'lang_id'=>1,
                'url'=>"/admin/routereal",'icon'=>""
            ],
        ];

    }

    public static function getColumForAdmin(){
        $columsSort = [];
        $colums = [
            ["table"=>AdminMenu::getTable(),"column"=>"id"],
            ["table"=>AdminMenu::getTable(),"column"=>"lable"],
            ["table"=>AdminMenu::getTable(),"column"=>"parent_id"],
            ["table"=>AdminMenu::getTable(),"column"=>"site_id"],
            ["table"=>AdminMenu::getTable(),"column"=>"url"],
            ["table"=>AdminMenu::getTable(),"column"=>"sort"],
        ];
        foreach ($colums as $item){
            $columsSort[] = $item['column'];
        }
        return ['select'=>$colums,"sort_col"=>$columsSort,'ignody_col'=>['site_id','is_deleted','lang_id','active']];
    }

}

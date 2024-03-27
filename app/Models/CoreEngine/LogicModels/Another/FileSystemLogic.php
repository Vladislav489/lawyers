<?php


namespace App\Models\CoreEngine\LogicModels\Another;
use App\Models\CoreEngine\Core\CoreEngine;
use App\Models\CoreEngine\Model\ModyfiModel;
use App\Models\System\General\Site;
use App\Models\System\SystemLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;


class FileSystemLogic extends CoreEngine {
    //protected $helpEngine;
    CONST DIR_MAIN = "site";
    CONST DIR_SITE = self::DIR_MAIN."/site_";
    CONST SITE = "site_";
    CONST TYPE_ENTITY = 1;
    CONST TYPE_ORGANIZATION = 2;

    CONST PARAMS_NAME = 'images';
    CONST PARAMS_NAME_ONE = 'image';

    private static $instance = null;
    protected $stoge = null;
    protected $joinDefault = [];
    protected $type = null;
    public function __construct($params = [], $select = ['*'], $callback = null) {
        $this->engine = new \App\Models\CoreEngine\ProjectModels\File\File();

        $this->query = $this->engine->newQuery();
        $this->getFilter();
        $this->compileGroupParams();
        parent::__construct($params, $select);
    }

    public function getListFile(): array|bool {
        try {
            return parent::getList();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getInstance($params = [], $select = ['*']){
        if(is_null(self::$instance))
            self::$instance = new self($params,$select);
        return self::$instance;
    }

//    public static function getTypeList(int $type_id = null): array {
//       $list = [
//           FileSystemLogic::TYPE_ENTITY => ['id' => FileSystemLogic::TYPE_ENTITY, 'name' => 'Товары' ,'dir' => 'entity','url_path'=>'product'],
//           FileSystemLogic::TYPE_ORGANIZATION => ['id' => FileSystemLogic::TYPE_ORGANIZATION,'name' => 'Организации' ,'dir' => 'organization','url_path'=>'organization' ],
//           FileSystemLogic::TYPE_BANNER => ['id' => FileSystemLogic::TYPE_BANNER,'name' => 'Банеры' ,'dir' => 'banner','url_path'=>'banner'],
//           FileSystemLogic::TYPE_BONUS => ['id' => FileSystemLogic::TYPE_BONUS,'name' => 'Бонусы' ,'dir' => 'bonus','url_path'=>'bonus'],
//           FileSystemLogic::TYPE_CATEGORY => ['id' => FileSystemLogic::TYPE_CATEGORY,'name' => 'Категории' ,'dir' => 'category','url_path'=>'category'],
//           FileSystemLogic::TYPE_TYPE => ['id' => FileSystemLogic::TYPE_TYPE,'name' => 'Типы' ,'dir' => 'type','url_path'=>'type'],
//           FileSystemLogic::TYPE_TAG => ['id' => FileSystemLogic::TYPE_TAG,'name' => 'Теги' ,'dir' => 'tag','url_path'=>'tag'],
//           FileSystemLogic::TYPE_POLIGON => ['id' => FileSystemLogic::TYPE_POLIGON,'name' => 'Полигоны' ,'dir' => 'poligon','url_path'=>'poligon'],
//           FileSystemLogic::TYPE_PROMO => ['id' => FileSystemLogic::TYPE_PROMO,'name' => 'Акции' ,'dir' => 'promo','url_path'=>'promo'],
//           FileSystemLogic::TYPE_MODIFICATOR => ['id' => FileSystemLogic::TYPE_MODIFICATOR,'name' => 'Модификаторы' ,'dir' => 'modificator','url_path'=>'modificator'],
//           FileSystemLogic::TYPE_QUANTITY_GROUP => ['id' => FileSystemLogic::TYPE_QUANTITY_GROUP,'name' => 'Группы метрик' ,'dir' => 'quantity_group','url_path'=>'quantity_group'],
//           FileSystemLogic::TYPE_QUANTITY => ['id' => FileSystemLogic::TYPE_QUANTITY,'name' => 'Размеры' ,'dir' => 'quantity','url_path'=>'quantity'],
//           FileSystemLogic::TYPE_CLIENT => ['id' => FileSystemLogic::TYPE_CLIENT,'name' => 'Пользователь','dir' => 'clint','url_path'=>'clint'],
//           FileSystemLogic::TYPE_All_FILE => ['id' => FileSystemLogic::TYPE_All_FILE,'name' => 'Общие Файлы','dir' => 'allfile','url_path'=>"/"]
//       ];
//       return (is_null($type_id))? $list : ((isset($list[$type_id]))? $list[$type_id]:false);
//    }

    public function existFile($file) {
           return file_exists($this->getDirSite()."/".$file);
    }

    public function getDirSite() {
        return  storage_path('app')."/".self::DIR_SITE.Site::getSiteId();
    }

    public function getDirSiteInfoByType(int $type_id) {
            return self::getTypeList($type_id);
    }
    public function getFolderByType(int $type_id): string {
        if($list = $this->getTypeList($type_id))
            return $this->getDirSite()."/".$list['dir'];
        return  false;
    }

    protected function deleteDir(string $dir) {
      return Storage::disk('local')->deleteDirectory($dir);
    }

    protected function deleteFile($paths): bool {
        return unlink($paths);
    }

    protected function moveFile(string $dirFrom, string $dirTo): bool {
        return Storage::disk('local')->move($dirFrom,$dirTo);
    }

    protected function getfileName($ext) {
        $uuid = Uuid::uuid6();
        $nameFile = $uuid .'.' .$ext;
        return ['name'=>$nameFile ,'uuid' => $uuid];
    }

    protected function saveFile(string $dir,string $nameFile, $file): array|bool {
        try {
            $ext = $file->getClientOriginalExtension();
            $nameFile = $this->getfileName($ext);
            if (Storage::disk('local')->putFileAs($dir, $file, $nameFile['name'])) {
                return ['fileName' => $nameFile['name'],
                    'extension' => $ext,
                    'dir' => $dir,
                    'file_size' => $file->getSize(),
                    'path' => $dir . "/" . $nameFile['name'],
                    'uuid' => $nameFile['uuid']
                ];
            }
            return false;
        } catch (\Throwable $e) {
            $dataMessage = [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dir' => (isset($dir)) ? $dir : '',
                'paramsData' => ['dir'=>$dir, 'name' =>$nameFile,'contentFile' => $file->getContent()],
                'type' =>  (isset($data['type_id']))?$this->getFolderByType($data['type_id']):''
            ];
            SystemLog::addLog("File System",$dataMessage,"Ошибка файда",SystemLog::CODE_ERROR);

            return false;
        }
    }

    protected function saveFileFromTemp(string $dir,string $nameFile,$_file): array|bool {
        try {
            if (!isset($_file['tmp_name'],$_file['name']) && empty($_file['tmp_name'])  && empty($_file['name']))
                return false;
           $info = pathinfo($_file['name']);
           $file_size = filesize($_file['tmp_name']);
           $nameFile = $this->getfileName($nameFile,$info['extension']);
           if (!move_uploaded_file($_file['tmp_name'], $dir . "/" . $nameFile['name'] . $info['extension']))
               return false;
           return ['fileName' => $nameFile['name'],
                        'extension' => $info['extension'],
                        'dir' => $dir,
                        'file_size' => $file_size,
                        'path' => $dir . "/" . $nameFile,
                        'uuid' => $nameFile['uuid']
           ];

        } catch (\Throwable $e) {
            $dataMessage = [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dir' => (isset($dir))?$dir:'',
                'paramsData' => ['dir'=>$dir, 'name' =>$nameFile,'contentFile' => $_file['tmp_name']],
                'type' =>  (isset($data['type_id']))?$this->getFolderByType($data['type_id']):''
            ];
            SystemLog::addLog("File System",$dataMessage,"Ошибка файда",SystemLog::CODE_ERROR);
            return false;
        }
    }

    protected function saveFileBase64(string $dir, string $nameFile, string $content): array|bool{
        try {
            if (is_dir($dir)) {
                if (!empty($nameFile)) {
                    if (strpos($content, 'base64') !== false) {

                        @list($type, $file_data) = explode(';', $content);

                        $type = explode("/", $type);
                        $ext = array_pop($type);
                        @list($other, $file_data) = explode(',', $file_data);

                        $file_data = base64_decode($file_data);
                        $nameFile = $this->getfileName($nameFile,$ext);
                        if (file_put_contents($dir . "/" . $nameFile['name'],$file_data)) {
                            return ['fileName' => explode('.',$nameFile['name'])[0],
                                'extension' => $ext,
                                'dir' => $dir,
                                'file_size' => strlen($file_data),
                                'path' => $dir . "/" . $nameFile['name'],
                                'uuid' => (string)$nameFile['uuid']
                            ];
                        }
                    }
                }
            }
            return false;
        } catch (\Throwable $e) {
            dd($e->getMessage(),$e->getFile(),$e->getLine());
            $dataMessage = [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dir' => (isset($dir))?$dir:'',
                'paramsData' => ['dir'=>$dir, 'name' =>$nameFile,'contentFile' => $content],
                'type' =>  (isset($data['type_id']))?$this->getFolderByType($data['type_id']):''
            ];
            SystemLog::addLog("File System",$dataMessage,"Ошибка файда",SystemLog::CODE_ERROR);
            return false;
        }
    }

    public function chmod_r($path) {
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $item) {
            chmod($item->getPathname(), 0755);
            if ($item->isDir() && !$item->isDot()) {
                $this->chmod_r($item->getPathname());
            }
        }
    }

    protected function saveDir(array $data): string|bool {
        try {
            if (isset($data['type_id']) && !empty($data['type_id'])) {
                if ($dir = $this->getFolderByType($data['type_id'])) {
                    if(!is_dir($dir)) {
                        $mainDir = File::makeDirectory($dir, 0777, true);
                        exec('chmod -R 777 '.$mainDir.' 2>&1', $output, $return_var);
                    } else {
                        $mainDir = $dir;
                    }
                    if(is_dir($mainDir)) {
                        if(isset($data['new_dir']) && !empty($data['new_dir'])) {
                            $newDir = $dir . "/" . $data['new_dir'];
                            if (is_dir($newDir)) {
                                return $newDir;
                            } else {
                                $res = File::makeDirectory($newDir, 0777, true);
                                exec('chmod -R 777 '.$newDir.' 2>&1', $output, $return_var);
                                return ($res) ? $newDir : false;
                            }
                        } else {
                             return $mainDir;
                        }
                    }
                }
            }
            return false;
        } catch (\Throwable $e) {
            exec('chmod -R 777 '.$dir.' 2>&1');
            exec('chmod -R 777 '.$newDir.' 2>&1');
            $dataMessage = [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'dir' => (isset($dir))?$dir:'',
                'paramsData' => $data,
                'type' =>  (isset($data['type_id']))?$this->getFolderByType($data['type_id']):''
            ];
            SystemLog::addLog("File System",$dataMessage,"Ошибка директори",SystemLog::CODE_ERROR);
            return false;
        }
    }



    public function deleteImage(array $data): bool {
        try {
            if (isset($data['id']) && !empty($data['id'])) {
                $result = $this->deleteCategoriesInfoAll($data);
                return $result;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getOne() {
        $this->setJoin($this->joinDefault);
        return parent::getOne();
    }

    protected function defaultSelect() {
        $tab = $this->engine->getTable();
        $this->default = [];
        return $this->default;
    }

    protected function getFilter() {
        $tab = $this->engine->getTable();
        $this->filter = [
            ['field' => $tab . '.type_id', 'params' => 'type_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.type_id', 'params' => 'site_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.abstract_id', 'params' => 'abstract_id',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.path', 'params' => 'path',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.url', 'params' => 'url',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.sort', 'params' => 'sort',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.uuid', 'params' => 'uuid',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.name', 'params' => 'name',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
            ['field' => $tab . '.is_app', 'params' => 'is_app',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],

            ['field' => $tab . '.is_hidden', 'params' => 'is_hidden',
                'validate' => ['string' => true, "empty" => true],
                'type' => 'string|array',
                "action" => 'IN', 'concat' => 'AND',
            ],
        ];
        return $this->filter = array_merge($this->filter, parent::getFilter());
    }

    protected function compileGroupParams() {
        $this->group_params = [
            'select' => [],
            'by' => [],
            'relatedModel' => []
        ];
        return $this->group_params;
    }
}

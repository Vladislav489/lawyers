<?php

namespace App\Http\Mainstay\File;

use App\Models\CoreEngine\LogicModels\File\FileLogic;
use App\Models\System\ControllersModel\MainstayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileMainstayController extends MainstayController
{
//    public function store($param = []) {
//        $this->params = empty($param) ? $this->params : $param;
//        return response()->json((new FileLogic())->store($this->params));
//    }

    public function actionGetFilesList($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new FileLogic($this->params))->offPagination()->getList());
    }

    public function getFile($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        return response()->json((new FileLogic($this->params))->getOne());
    }

    public function actionDownloadFile($filePath) {
        if (!empty((new FileLogic(['path' => $filePath]))->getOne())) {
            return response()->download(Storage::path($filePath));
        }
        return response('<div style="color: red">no file or access</div>', 404);
    }

    public function actionDeleteFile($param = []) {
        $this->params = empty($param) ? $this->params : $param;
        $file = (new FileLogic($this->params))->getOne();
        if (!empty($file)) {
            if ((new FileLogic())->deleteForeva($file)) {
                Storage::delete($file['path']);
                return $this->actionGetFilesList(['path_start' => $this->params['path_start']]);
            }
            return response()->json(['error' => 'Server Error deleting file'], 500);
        }
        return response()->json(['error' => 'No file of access'], 403);
    }
}

<?php

namespace App\Plugins\UpdaterPlugin\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Plugin;
use Socialite;
use App\Plugins\UpdaterPlugin\Repositories\UpdaterRepository;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use Auth;
use Storage;



class UpdaterPluginController extends Controller {

    protected $updaterRepo = null;

    public function __construct(UpdaterRepository $updaterRepo){
        $this->updaterRepo = $updaterRepo;
    }


    public function showUpdater() {
        $errors = [];
        $this->updaterRepo->checkReadWritePermissions($errors);
        $currentVersion = $this->updaterRepo->currentVersion();
        return Plugin::view('UpdaterPlugin/updater', ['errors' => $errors, "current_version" => $currentVersion]);
    }


    public function checkUpdate(Request $req) {
        $response = $this->updaterRepo->checkUpdates($req->url);
        return response()->json($response);
    }

   

    public function doUpdate (Request $request) 
    {
        
        if(is_null($request->filename)) {
            return response()->json([
                "status" => 'error',
                "error_type" => "INVALID_UPDATE_FILE",
                "error_text" => trans('admin.update_file_required')
            ]);
        }
       
        

        $this->updaterRepo->setOverwriteLanguageFiles(
            $request->overwrite_lang_files == 'true' ? true : false
        );
        
        $response = $this->updaterRepo->doUpdate($request->filename);
        
        return response()->json($response);

    }


    /*
    public function doBackup(Request $req) {
        $output = $this->updaterRepo->doBackup();
        return response()->json(["status" => "success", "output" => $output]);
    }
    */
}

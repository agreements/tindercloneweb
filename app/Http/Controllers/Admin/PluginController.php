<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Admin\PluginRepository;
use App\Repositories\Admin\UtilityRepository;
use Hash;
use Validator;

class PluginController extends Controller {

    protected $pluginRepo;
    public function __construct (PluginRepository $pluginRepo) {

        $this->pluginRepo = $pluginRepo;
    }


    public function showPlugins () {        

        $loadedPlugins = $this->pluginRepo->getLoadedPlugins();
        $plugins       = $this->pluginRepo->getPlugins();

        return view('admin.admin_plugin', ['plugins' => $plugins, 'loadedPlugins' => $loadedPlugins]);
    }
    
    public function activatePlugin (Request $request) {
        $this->pluginRepo->activatePlugin($request->id);
        return response()->json(['status' => 'success']);
    }

    public function deactivatePlugin (Request $request) {
        $this->pluginRepo->deactivatePlugin($request->id);
        return response()->json(['status' => 'success']);
    }


    public function installPlugin (Request $request) {
        $response = $this->pluginRepo->installPlugin(
            $request->plugin,
            $request->author,
            $request->description,
            $request->version,
            $request->website
        );


        return response()->json($response);
       
    }



}
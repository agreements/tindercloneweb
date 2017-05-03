<?php

namespace App\Repositories\Admin;

use Storage;
use App\Components\Plugin;
use App\Components\PluginAbstract;
use App\Components\PluginInstall;
use App\Models\Plugins;
use App\Models\SocialLogins;
use App\Repositories\Admin\UtilityRepository;

class PluginRepository {

    public function __construct(Plugins $plugins, SocialLogins $socialLogins){
        $this->plugins = $plugins;
        $this->socialLogins = $socialLogins;
        $this->updateRepo = app('App\Plugins\UpdaterPlugin\Repositories\UpdaterRepository');
    }


    public function getPlugins() { return $this->plugins->all(); }

	public function getLoadedPlugins() {

        $dirs = Storage::directories('/app/Plugins');
        $loaded_plugins = [];

        foreach ($dirs as $dir) {

            $plugin_name             = explode('/', $dir)[2];
            $plugin_file         = "{$dir}/{$plugin_name}.php";

            if (Storage::has($plugin_file)) {

                $plugin_class         = "\\{$plugin_name}";
                
                require_once base_path() . "/{$plugin_file}";

                $pluginObj  = new $plugin_class;
                
                //checkint all following methos exixt's or not in every plugin info class
                if ($pluginObj instanceof PluginAbstract) {
                   
                    $pluginObj->name = $plugin_name;
                    
                    $temp = $this->plugins->Where('name', $plugin_name)->first();
                    
                    if ($temp == null) {
                        $pluginObj->isInstalled = false;    
                        array_push($loaded_plugins, $pluginObj);
                    }
                }
            }
                
        }
        return $loaded_plugins;
    }

    public function activatePlugin ($id) {
        $this->plugins->where('id', $id)->update(["isactivated" => "activated"]);
        
       \App\Components\Plugin::syncWithConfig();
    }

    public function deactivatePlugin ($id) {
        $this->plugins->where('id', $id)->update(["isactivated" => "deactivated"]);
        
        \App\Components\Plugin::syncWithConfig();
    }


    public function checkLangPathPermission () {
        $path = base_path() . '/resources/lang';
        return (is_writable($path)) ? true : false;
    }


    public function getSupportedLanguages () {
        
        $lang_path = base_path() . "/resources/lang";
        $dirs      = scandir ($lang_path);
        $langs     = [];

        /*  searching for valid language dirctory
            if dirctory contains app.php file then only directory wil be
            treated as valid language directory. 
        */
        foreach ($dirs as $dir) {

            if ($dir == '.' || $dir == '..') {

                continue;
            }
            
            $lang = $lang_path . '/' . $dir;

            //pushing valid language directory into array
            if (file_exists($lang . '/app.php')) {

                array_push($langs, $dir);
            }
                 
        }

        return $langs;
 
    }




    public function copyPluginLanguages ($plugin_name) {

        $langs = $this->getSupportedLanguages();

        foreach ($langs as $lang) {
            
            $app_lang_path    = "resources/lang/{$lang}/";
            $plugin_lang_path = "app/Plugins/{$plugin_name}/language/{$lang}";
            
            foreach (Storage::files($plugin_lang_path) as $file) {

                $file_array = explode("/", $file);
                $file_name = $file_array[count($file_array) - 1];
                $new_path = $app_lang_path.$file_name;
                
                if (Storage::has($new_path)) {
                    Storage::delete($new_path);
                }

                Storage::copy($file, $app_lang_path.$file_name);
            }
               

        }

    }

    public function createPluginDir ($plugin) {
        if (!file_exists(public_path().'/plugins')) {
            mkdir(public_path().'/plugins', 0777);
        }
    }

    public function copyPluginAssets ($plugin) {

        $src  = base_path()."/app/Plugins/{$plugin}/assets";
        $dest = public_path()."/plugins/{$plugin}";

        if (file_exists($src)) {
           UtilityRepository::copyFolder($src, $dest); 
        }       
        
    }

    public function executePluginIntall ($pluginName) {

        $pluginFile = "{$pluginName}Install.php";
        $pluginPath = base_path() . "/app/Plugins/{$pluginName}/";

        if (file_exists( $pluginPath.$pluginFile)) {

            require_once $pluginPath.$pluginFile;

            $pluginClass = "\\{$pluginName}Install";
            $pluginObj   = new $pluginClass;

            if (method_exists($pluginObj, 'install')) {
                $pluginObj->install();
            }

        }

    }

    public function createEntrySocialLogins($pluginName, $id)
    {
        $plugin_path = base_path()."/app/Plugins/{$pluginName}/{$pluginName}.php";
        require_once $plugin_path;
        $plugin_class = "\\{$pluginName}";
        $plugin_obj   = new $plugin_class;

        if (method_exists($plugin_obj, 'isSocialLogin') && $plugin_obj->isSocialLogin()) {
            
            $social = clone $this->socialLogins;
            $social->name = $pluginName;
            $social->plugin_id = $id;
            $social->priority = 999; 
            $social->save();   
        }
        
        if(method_exists($plugin_obj, 'productID')) {
            $this->updateRepo->UpdateLocalFile($plugin_obj->productID(), $plugin_obj->version());
        }
    }

    public function addPlugin ($data) {

        $plugin = clone $this->plugins;
        foreach ($data as $key => $value) {
            $plugin->$key = $value;
        }
        $plugin->save();
        return $plugin;
    }


    public function installPlugin($plugin, $author, $description, $version, $website, $isCore = false, $isActivated = 'deactivated'){

        try {

            if ( !$this->checkLangPathPermission() ) {

                return ['status' => 'error', 'message' => trans('admin.lang_path_permission')];
            }

            $this->copyPluginLanguages($plugin);
            $this->createPluginDir($plugin);
            $this->copyPluginAssets($plugin);    
            $this->executePluginIntall($plugin);

            $plugin = $this->addPlugin([

                'name'        => $plugin,
                'author'      => $author,
                'description' => $description,
                'version'     => $version,
                'website'     => $website,
                'is_core'     => $isCore,
                'isactivated' => $isActivated,

            ]);

            $this->createEntrySocialLogins($plugin->name, $plugin->id);

            return ['status' => 'success', 'message' => trans('admin.plug_ins_succ_msg')];


        } catch(\Exception $e){
            return ['status' => 'error', 'message' => trans('admin.plug_ins_err_msg')];
        }
     
    }


}
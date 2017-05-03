<?php

namespace App\Installer\Repositories;

use Storage;
use App\Models\Admin;
use Schema;
use Hash;
use App\Repositories\Admin\ProfileManageRepository;
use App\Models\Themes;
use App\Components\ThemeInterface;
use App\Models\Settings;
use App\Models\Package;
use App\Models\SuperPowerPackages;
use App\Components\PluginAbstract;
use App\Components\PluginInstall;
use App\Models\Plugins;
use App\Models\Pages;
use App\Models\SocialLogins;
use Illuminate\Database\Schema\Blueprint;
use \PDOException;
use Validator;
use App\Libs\CrontabManager\CrontabRepository;
use App\Libs\CrontabManager\CrontabAdapter;
use App\Libs\CrontabManager\CrontabJob;
use App\Components\Plugin;

class InstallerRepository
{
	public function __construct(
		ProfileManageRepository $profileManageRepo, 
		Settings $settings, 
		Package $package, 
		SuperPowerPackages $superPowerPackages,
		Admin $admin,
		Themes $themes
	)
	{
		$this->profileManageRepo = $profileManageRepo;
		$this->settings = $settings;
		$this->package = $package;
		$this->superPowerPackages = $superPowerPackages;
		$this->admin = $admin;
		$this->themes = $themes;
		$this->payment = app('App\Models\Payment');
	}


	protected $custom_error_messages = [
		'max'       => 'The :attribute maximum lenght is :max.',
		'min'       => 'The :attribute minimum lenght can be :min.',
		'required'  => 'The :attribute is required.',
		'confirmed' => 'The :attribute and confirm :attribute not matched.',
  	];

  	protected $rules = [
		'name'                  => 'required|max:100',
		'username'              => 'required|email|max:200',
		'password'              => 'required|min:8|max:100|confirmed',
		'password_confirmation' => 'required',
		'website_title'         => 'required|min:4|max:300',
    ];

    protected $admin_validation_success = false;
	public function validateAdminAndWebsiteTitle($request_data, &$errors)
	{
        $validator = Validator::make($request_data, $this->rules, $this->custom_error_messages);

        if($validator->fails()) {

			$errors["error_type"] = "ADMIN_VALIDATION_ERROR";
			$errors["status"]     = "validation failed";

        	$msgs = $validator->errors();

        	if ($msgs->has('name')) {
	        	$errors['errors']['name'] = $msgs->get('name')[0];
	      	}

			if ($msgs->has('username')) {
				$errors['errors']['username'] = $msgs->get('username')[0];
			}

			if ($msgs->has('password')) {
				$errors['errors']['password'] = $msgs->get('password')[0];
			}

			if ($msgs->has('password_confirmation')) {
				$errors['errors']['password_confirmation'] = $msgs->get('password_confirmation')[0];
			}

			if ($msgs->has('website_title')) {
				$errors['errors']['website_title'] = $msgs->get('website_title')[0];
			}

			return $this;
        }

	    $this->admin_validation_success = true;  
	    $this->admin_config = [

            'name'       => $request_data["name"],
            'username'   => $request_data["username"],
            'password'   => Hash::make($request_data["password"]),
            'last_ip'    => $this->ip_address,
            'last_login' => date('Y-m-d H:i:s'),
        ];
	    
	    $this->website_title = $request_data['website_title'];
	    return $this;

	}

	

	public function setDomain($domain)
	{
		$this->domain = $domain;
		return $this;
	}

	public function setServerIP($ip)
	{
		$this->ip_address = $ip;
		return $this;
	}

	protected $installation_success = false;
	public function startInstallation(&$errors)
	{
		if(!$this->admin_validation_success) {
			$errors["error_type"] = "UNKNOWN_ERROR";
			$errors["status"]     = "unknown error";
			$errors["errors"]     = "unknown error";
			return $this;
		}

		$this->createAdminTable()
			->createAdmin($this->admin_config);

		try {

			$this->createTables();
			$corePlugins = $this->getLoadedPlugins();
                
            foreach ($corePlugins as $plugin) {

              if(!$plugin->isInstalled){
                  	$data = array(
						'name'        => $plugin->name,
						'author'      => $plugin->author(),
						'description' => $plugin->description(),
						'version'     => $plugin->version(),
						'website'     => $plugin->website(),
						'is_core'     => $plugin->isCore(),
						'isactivated' => 'activated',
                  	);

                  $this->installPlugin($data);        
              }

            }

            Plugin::syncWithConfig();

            $this->setWebsiteTitle($this->website_title);
            $this->setOtherDefaultSettings();
            $this->installTheme('DefaultTheme');

			$this->installation_success = true;
			return $this;

		} catch(\Exception $e) {
			$errors["error_type"] = "UNKNOWN_ERROR";
			$errors["status"]     = "unknown error";
			$errors["errors"]     = $e->getMessage();
			return $this;
		}
	}

	public $cron_error = '';

	public function finishInstalltion()
	{
		if(!$this->installation_success){
			return $this;
		}
		$this->setPhpPath();

		try {

			$this->saveLicenseKey();

			$res = $this->postDataToURL(
				"http://licensing.datingframework.com/add-installation",
				['domain' => $this->domain, "license_key" => $this->getLicenseKey()],
				true
			);

		} catch(\Exception $e){}

		$this->moveFiles();
		$this->generateAPPKEY();
		$this->startCron();
	}

	public function generateAPPKEY()
	{	
		$php_path = $this->settings->get('php_path');
		$artisan_path = base_path('artisan');
		$command = "{$php_path} {$artisan_path} key:generate";

		try {
			exec($command);
		}catch(\Exception $e){}
		
	}

	public function startCron()
	{	
		try {

			$crontabRepository = new CrontabRepository(new CrontabAdapter());

			$php_path = $this->settings->get('php_path');
			$artisan_path = base_path('artisan');
			$cron_string = "* * * * * {$php_path} {$artisan_path} schedule:run >> /dev/null 2>&1";
			$crontabJob = CrontabJob::createFromCrontabLine($cron_string);

			try {
				$crontabRepository->removeJob($crontabJob);
				$crontabRepository->persist();
			} catch(\Exception $e){}


			$crontabRepository->addJob($crontabJob);
			$crontabRepository->persist();


		} catch(\Exception $e) {
			$this->cron_error = $e->getMessage();
		}
	}



	public function saveLicenseKey()
	{
		$this->settings->set('app_license_key', $this->getLicenseKey());
	}

	public function setPhpPath()
	{
		$path = $this->findPhpPath();
		$this->settings->set("php_path", $path);
	}

	public function findPhpPath()
	{
		try {

			$paths = $_SERVER['PATH'];
			$paths = explode(":", $paths);

			foreach($paths as $path) {

				$php_path = "$path/php";

				if(file_exists($php_path)) {
					return $php_path;
				}
			}

			return "";


		} catch(\Exception $e) {
			return "";
		}
	}

	public function getLicenseKey()
	{
		if(session()->has('license_key'))
			return session('license_key');
		if(isset($this->license_key)){
			return $this->license_key;
		}

		return $this->settings->get('license_key');
	}

	public function setLicenseKey($license_key)
	{
		$this->license_key = $license_key;
		session(['license_key' => $license_key]);
		return $this;
	}

	public function checkLicenseKey(&$errors)
	{
		if(isset($this->license_key) && $this->license_key == '') {
			$errors["error_type"] = "LICENSE_KEY_ERROR";
			$errors["status"] = "License key required";
		} 
		return $this;
	}


	public function setCookie($cookie_key, $cookie_value, $expire)
	{
		setcookie($cookie_key, $cookie_value, $expire);
	}


	//this method returns all supported languages
    public function getSupportedLanguages () {
        

        $lang_path = base_path() . "/resources/lang";

        $dirs = scandir ($lang_path);

        $langs = [];

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

	// plugins
	public function getLoadedPlugins () {

		$dirs = Storage::directories('app/Plugins');
		$loaded_plugins = [];

		foreach($dirs as $dir) {

			$plugin_name = explode('/', $dir)[2];
			$plugin_info = "{$dir}/{$plugin_name}.php";

			if (Storage::has($plugin_info)) {

				$pluginClass = "\\{$plugin_name}";
				require_once base_path(). "/{$plugin_info}";
				
				
				$pluginObj = new $pluginClass;

				if ($pluginObj instanceof PluginAbstract) {
					
					$pluginObj->name = $plugin_name;

					$temp = Plugins::Where('name', '=', $plugin_name)->first();

					if($temp != null) {
					
						$pluginObj->isInstalled = true;
					
					} else {

						$pluginObj->isInstalled = false;
					}
					
					array_push($loaded_plugins, $pluginObj);
				}
			}
				
		}
		return $loaded_plugins;
	}

	public function installPlugin($request)
    {
        //creating theme directory in public folder with js, css, fonts, images
        $this->createPluginDir($request['name']);
        $this->copyPluginAssets($request['name']);    
        $this->callPluginInstaller ($request['name']);
        $plugin = $this->addPlugins($request);
        $this->createEntrySocialLogins($plugin->name, $plugin->id);
        $this->copyPluginLanguages($request['name']);
       
    }

    public function createEntrySocialLogins($pluginName, $id)
    {
        $plugin_path = base_path()."/app/Plugins/{$pluginName}/{$pluginName}.php";
        require_once $plugin_path;
        $plugin_class = "\\{$pluginName}";
        $plugin_obj   = new $plugin_class;

        if (method_exists($plugin_obj, 'isSocialLogin') && $plugin_obj->isSocialLogin()) {
            
            $social = new SocialLogins;
            $social->name = $pluginName;
            $social->plugin_id = $id;
            $social->priority = 999; 
            $social->save();   
        }
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

    public function callPluginInstaller ($plugName) { 

    	$plugInstallFile = $plugName . 'Install.php';


    	if ( Storage::has('app/Plugins/' . $plugName . '/' . $plugInstallFile) ) {

    		$plugInstallClass = "\\" . $plugName . 'Install';
    		
    		if (!class_exists($plugInstallClass)) {

    			include base_path() . '/app/Plugins/' . $plugName . '/' . $plugInstallFile;
    		}

			$installObj = new $plugInstallClass;

			if (method_exists($installObj, 'install')) {

				$installObj->install();
			}
 

    	}
    	
    }

    public function addPlugins($data)
	{
		$plugin = new Plugins;

		foreach($data as $key => $value)
			$plugin->$key = $value;

		$plugin->save();
		return $plugin;
	}


    public function copyPluginAssets($plugin)
	{
		$src = base_path().'/app/Plugins/' . $plugin . '/assets';
		$dest = public_path().'/plugins/' . $plugin;


		if(file_exists($src))
		{
			$this->copyFolder($src, $dest); 
		}		
		
	}

    public function createPluginDir($plugin)
	{
		if(!file_exists(public_path().'/plugins'))
		{
			mkdir(public_path().'/plugins', 0777);
		}
	}


	//this function copy folder to another directory
	function copyFolder($source, $destination) 
	{ 
       //Open the specified directory

       $directory = opendir($source); 
       
       //Create the copy folder location
       if(!file_exists($destination))
       		mkdir($destination, 0777);

       //Scan through the folder one file at a time
       while(($file = readdir($directory)) != false) 
       {
       		if($file == '.' || $file == '..')
       			continue;

       		if(is_dir($source. '/' .$file))
       		{
       			
       			$this->copyFolder($source . '/' . $file, $destination . '/' . $file);
       		}
       		else
       		{
       			//Copy each individual file 
	            if(!copy($source.'/' .$file, $destination.'/'.$file));
       		}
       } 

	}



	public function setWebsiteTitle($title_str)
	{
		$this->settings->set('website_title', $this->website_title);
		return $this;
	}

	public function setOtherDefaultSettings()
	{

		$this->settings->set('defaultCredits', '0');
		$this->settings->set('spotCredits', '0');
		$this->settings->set('currency', 'USD');
		$this->settings->set('riseupCredits', '0');
		$this->settings->set('website_logo', 'logo.png');
		$this->settings->set('website_outerlogo', 'outerlogo.png');
		$this->settings->set('meta_description', '');
		$this->settings->set('meta_keywords', '');
		$this->settings->set('meta_block', '');
		$this->settings->set('default_language', 'en');
		$this->settings->set('max_file_size', '10');
		$this->settings->set('default_male', 'male.jpg');
		$this->settings->set('default_female', 'female.jpg');
		$this->settings->set('default_prefered_genders', 'male,female');
		$this->settings->set('credits_module_available', 'true');
		$this->settings->set('spotlight_only_superpowers', 'false');
		$this->settings->set('peoplenearby_only_superpowers', 'false');
		$this->settings->set('maxmind_geoip_enabled', 'false');
		$this->settings->set('profile_visitor_details_show_mode', 'true');
		$this->settings->set('profile_interests_show_mode', 'true');
		$this->settings->set('profile_about_me_show_mode', 'true');
		$this->settings->set('profile_score_show_mode', 'true');
		$this->settings->set('profile_map_show_mode', 'true');
		$this->settings->set('advance_filter_only_superpowers', 'false');
		$this->settings->set('filter_distance_unit', 'km');
        $this->settings->set('filter_distance', '100');
        $this->settings->set('filter_range_min', '0');
        $this->settings->set('filter_range_max', '100');
        $this->settings->set('filter_non_superpowers_range_enabled', 'false');
		
        $this->settings->set('photo_restriction_mode', 'false');
        $this->settings->set('minimum_photo_count', '0');
        $this->settings->set('visitor_setting', 'everyone');
        $this->settings->set('limit_encounter', '10');
        $this->settings->set('limit_chat', '10');
		

		$arr['new_field']   = 'Gender';
		$arr['section_id']  = '0';
		$arr['type']        = 'dropdown';
		$arr['register']    = 'yes';
		$arr['search']      = 'yes';
		$arr['search_type'] = 'dropdown';
		$arr['unit']        = '';
        $this->profileManageRepo->post_add_field($arr);

        $option_arr["optiontitle"] = 'Male';
        $option_arr["field"] = '1';
        
        $this->profileManageRepo->post_add_field_option($option_arr);

        $option_arr["optiontitle"] = 'Female';
        $option_arr["field"] = '1';
        
        $this->profileManageRepo->post_add_field_option($option_arr);

		

		
        $this->initSuperpowerPackagesTable();
		$this->initCreditPackagesTable();
		$this->initPaymentsTable();



	}

	public function initSuperpowerPackagesTable()
	{
		$super_pack = new $this->superPowerPackages;
		$super_pack->amount = 0;
		$super_pack->duration = 0;
		$super_pack->package_name = 'Default';
		$super_pack->name_code = 'Default';
		$super_pack->description_code = 'Default';
		$super_pack->save();	
		
		return $this;
	}

	public function initCreditPackagesTable()
	{
		$pack = new $this->package;
		$pack->amount = 0;
		$pack->credits = 0;
		$pack->packageName = 'Default';
		$pack->name_code = 'Default';
		$pack->description_code = 'Default';
		$pack->save();

		return $this;
	}

	public function initPaymentsTable()
	{
		$payment = new $this->payment;
		$payment->name = 'superpower';
		$payment->type = 'stored';
		$payment->heading_code = 'payments.superpower_heading';
		$payment->sub_heading_code = 'payments.superpower_subheading';

		$payment->save();


		$payment = new $this->payment;
		$payment->name = 'credit';
		$payment->type = 'stored';
		$payment->heading_code = 'payments.credit_heading';
		$payment->sub_heading_code = 'payments.credit_subheading';

		$payment->save();

		return $this;
	}

	public function createVisitorsTable()
	{
		
		Schema::dropIfExists('visitors');

  		Schema::create('visitors', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');	            
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createUserFieldsTable()
	{
		
		Schema::dropIfExists('user_fields');

  		Schema::create('user_fields', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->text('value');
	            $table->bigInteger('field_id');
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createFieldsTable()
	{
		
		Schema::dropIfExists('fields');

  		Schema::create('fields', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 100);
	            $table->string('code', 100);
	            $table->bigInteger('section_id');
	            $table->enum('type', ['text', 'textarea', 'dropdown', 'checkbox']);
	            $table->enum('on_registration', ['yes', 'no']);
	            $table->enum('on_search', ['yes', 'no']);
	            $table->enum('on_search_type', ['dropdown', 'range']);
	            $table->string('unit', 100)->nullable();
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createFieldOptionsTable()
	{
		Schema::dropIfExists('field_options');

  		Schema::create('field_options', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 100);
	            $table->string('code', 100);            
	            $table->bigInteger('field_id');
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createFieldSectionsTable()
	{
		Schema::dropIfExists('field_sections');

  		Schema::create('field_sections', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 100);
	            $table->string('code', 100); 
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createUserInterestsTable()
	{
		Schema::dropIfExists('userinterests');

  		Schema::create('userinterests', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->bigInteger('interestid');	            
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}


	public function createUserAbuseReportsTable()
	{
		Schema::dropIfExists('userabusereports');

  		Schema::create('userabusereports', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('reporting_user');
	            $table->bigInteger('reported_user');
	            $table->string('reason', 500);	            
	            $table->string('action', 10);	            
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createPhotoAbuseReportsTable()
	{
		Schema::dropIfExists('user_photo_abuse_reports');

  		Schema::create('user_photo_abuse_reports', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('reporting_user');
	            $table->bigInteger('reported_user');
	            $table->bigInteger('reported_photo');
	            $table->string('reason', 500);	            
	            $table->string('status', 10);	  

	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}


	public function createUserTable()
	{
		Schema::dropIfExists('user');

  		Schema::create('user', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('username', 100)->nullable();
	            $table->string('password', 1000);	            
	            $table->string('gender', 20);
	            $table->string('name', 100);	    
	            $table->string('slug_name', 255)->nullable();	    
	            $table->date('dob');        
	            $table->string('city', 100);	            
	            $table->string('country', 100);	            
	            $table->string('hereto', 50);	            
	            $table->string('profile_pic_url', 500);	            
	            $table->string('status', 500);	            
	            $table->string('package_name', 200)->nullable();	
	            $table->timestamp('expired_at')->nullable();
	            $table->string('activate_token', 100);	
	            $table->string('password_token', 100);	
	            $table->string('activate_user', 50);	
	            $table->string('register_from', 100);	
	            $table->string('verified', 20);	
	            $table->float('latitude')->nullable();	
	            $table->float('longitude')->nullable();	
	            $table->string('language', 100);	
	            $table->timestamp('last_request')->nullable();
	            $table->string('access_token', 225)->nullable();	
	            $table->string('remember_token', 100)->nullable();	
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}



	public function createTransactionTable()
	{
		Schema::dropIfExists('transaction');

  		Schema::create('transaction', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('gateway', 100);	            
	            $table->string('transaction_id', 100);	            
	            $table->bigInteger('amount');	            
	            $table->string('status', 100);	            
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}


	public function createSuperPowerPackagesTable()
	{
		Schema::dropIfExists('superpowerpackages');

  		Schema::create('superpowerpackages', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('package_name', 200);	            
	            $table->string('name_code', 255);	            
	            $table->string('description_code', 255);	            
	            $table->decimal('amount', 20, 2);	            
	            $table->bigInteger('duration')->nullable();	            
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}

	public function createSuperPowerHistoryTable()
	{
		Schema::dropIfExists('superpower_history');

  		Schema::create('superpower_history', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');	            
	            $table->bigInteger('trans_table_id');
	            $table->bigInteger('superpower_package_id');
	            $table->timestamps();	            
	            $table->softDeletes();
        });
        return $this;
	}


	public function createProfileTable()
	{
		Schema::dropIfExists('profile');

  		Schema::create('profile', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->string('prefer_gender', 100);	            
	            $table->string('prefer_age', 10);	     
	            $table->enum('prefer_online_status', ['online', 'all'])->nullable();
	            $table->integer('prefer_distance_nearby');
	            $table->float('popularity')->nullable();
	            $table->string('aboutme', 5000);      
	            $table->float('latitude')->nullable();	
	            $table->float('longitude')->nullable();	
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createMatchesTable()
	{
		Schema::dropIfExists('matches');

  		Schema::create('matches', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createInterestsTable()
	{
		Schema::dropIfExists('interests');

  		Schema::create('interests', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('interest', 100);	            
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createEncounterTable()
	{
		Schema::dropIfExists('encounter');

  		Schema::create('encounter', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');
	            $table->smallInteger('likes');
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createCreditsTable()
	{
		Schema::dropIfExists('credits');

  		Schema::create('credits', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->bigInteger('balance');
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createCreditPackagesTable()
	{
		Schema::dropIfExists('creditPackages');

  		Schema::create('creditPackages', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->decimal('amount', 20, 2);
	            $table->bigInteger('credits');
	            $table->string('packageName', 100);
	            $table->string('name_code', 255);
	            $table->string('description_code', 255);
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createCreditHistoryTable()
	{
		Schema::dropIfExists('creditHistory');

  		Schema::create('creditHistory', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->bigInteger('credits');
	            $table->bigInteger('transTable_id');	           
	            $table->string('activity', 100);
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createBlockUsersTable() 
	{
		Schema::dropIfExists('blockusers');

  		Schema::create('blockusers', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user1');
	            $table->bigInteger('user2');	            
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}


	public function createPhotosTable()
	{
		Schema::dropIfExists('photos');

  		Schema::create('photos', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->string('source_photo_id',100)->nullable();
	            $table->string('photo_source', 500)->nullable();
	            $table->string('photo_url', 500);
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}

	public function createThemesTable()
	{
		Schema::dropIfExists('themes');

  		Schema::create('themes', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 200);
	            $table->string('isactivated', 50);
	            $table->string('author', 200);
	            $table->string('description', 500);
	            $table->string('version', 20);
	            $table->string('website', 200);
	            $table->string('role', 200);
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}

	public function createPluginsTable()
	{
		Schema::dropIfExists('plugins');

  		Schema::create('plugins', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 200);
	            $table->string('isactivated', 50);
	            $table->string('author', 200);
	            $table->string('description', 500);
	            $table->string('version', 100);
	            $table->string('website', 100);
	            $table->boolean('is_core');
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}

	public function createSettingsTable()
	{
		Schema::dropIfExists('settings');

  		Schema::create('settings', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('admin_key', 100);
	            $table->string('value', 500);
	            $table->timestamps();
	            $table->softDeletes();
        });
        return $this;
	}

	public function createUserSettingsTable()
	{
		Schema::dropIfExists('user_settings');

  		Schema::create('user_settings', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->string('key', 100);
	            $table->string('value', 500);
	            $table->timestamps();
	            $table->softDeletes();
        });	
        return $this;
	}

	public function createAdminTable()
   	{
  		Schema::dropIfExists('admin');

  		Schema::create('admin', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('username', 100)->unique();
	            $table->string('password', 1000);
	            $table->string('name', 200);
	            $table->string('last_ip', 50);
	            $table->timestamp('last_login');
	            $table->timestamps();
	            $table->softDeletes();

        });

        return $this;
		
   	}

   	public function createUserSocialLoginTable()
   	{
   		Schema::dropIfExists('user_social_login');

  		Schema::create('user_social_login', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('userid');
	            $table->string('src', 500);
	            $table->string('src_id', 500);
	            $table->timestamps();
	            $table->softDeletes();

        });
        return $this;
   	}

   	public function createUserSuperPowersTable()
   	{
   		Schema::dropIfExists('user_superpowers');

  		Schema::create('user_superpowers', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->bigInteger('user_id');
	            $table->smallInteger('invisible_mode');
	            $table->smallInteger('hide_superpowers');
	            $table->timestamp('expired_at')->nullable();
	            $table->timestamps();
	            $table->softDeletes();

        });

        return $this;
   	}
	
	public function createSocialLoginsTable()
	{	
		Schema::dropIfExists('social_logins');

  		Schema::create('social_logins', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 500);
	            $table->smallInteger('plugin_id');
	            $table->smallInteger('priority');
	            $table->timestamps();
	            $table->softDeletes();

        });

        return $this;
	}

	public function createAdmin($admin_config)
	{
		$admin = new $this->admin;

   		foreach ($admin_config as $key => $value){
   			$admin->$key = $value;
   		}

   		$admin->save();
   		return $admin;
	}



	protected $db_check_success = false;
	protected $db_config = [
		'driver'    => 'mysql',
		'collation' => 'utf8_unicode_ci',
		'charset'   => 'utf8',
		'prefix'    => ''
	];

	public function checkDB($config, &$errors)
	{
		//creating validator for login requests
        $validator = Validator::make($config, [
			'host'     => 'required|min:2',
			'database' => 'required|min:2',
			'username' => 'required|min:2',
		]);

        //if input validation fails then redirect to login view ageain
        if($validator->fails()) {
        	$errors["error_type"] = "DB_VALIDATION_ERROR";
        	$errors["status"] = "Database validation failed.";
        	return $this;
        }
              
  		$this->db_config = array_merge($this->db_config, $config);

    	$driver = new \Illuminate\Database\Connectors\MySqlConnector;
      
      	$error = null;
		 
		try { 
			$connection = $driver->connect($this->db_config);
		   	$connection->query('select database()')->fetchColumn();

		   	$this->db_check_success = true;

		} catch(\PDOException $e) {
			$errors["error_type"] = "DB_CONNECTION_ERROR";
			$errors["status"] = $e->getMessage();
		}

		return $this;
	}


	protected $db_setup_success = false;
	public function setupDatabase(&$errors)
	{	
		if(!$this->db_check_success) {
			$errors["error_type"] = "DB_SETUP_FAILED";
			$errors["status"] = "Database setup failed.";
			return $this;
		}

		$database_dump = Storage::get('app/Installer/database.stub');
   		foreach ($this->db_config as $key => $value) {
   			$database_dump = str_replace('{{{'.$key.'}}}', $value, $database_dump);
   		}
   		Storage::put('/config/database.php', $database_dump);

   		$this->db_setup_success = true;
   		return $this;
	}



	// public function setupEmail($config)
	// {

	// 	$core_mail = Storage::get('app/Plugins/InstallerPlugin/installer_storage/core_mail.php');

 //   		foreach ($config as $key => $value)
 //   		{
 //   			$core_mail = str_replace('{{{'.$key.'}}}', $value, $core_mail);
 //   		}

 //   		if(Storage::has('/config/mail.php'))
 //   		{
 //   			Storage::delete('/config/mail.php');
 //   			Storage::put('/config/mail.php', $core_mail);
 //   		}
 //   		else
 //   		{
 //   			Storage::put('/config/mail.php', $core_mail);
 //   		}

	// }	


	public function createPaymentsTable()
	{
		Schema::dropIfExists('payments');

  		Schema::create('payments', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 255);
	            $table->enum('type', ['stored', 'non-stored'])->default('stored');	            
	            $table->string('heading_code', 500);
	            $table->string('sub_heading_code', 500);
	            $table->timestamps();	            
	            $table->softDeletes();
        });

        return $this;
	}

	public function createPaymentGatewaysTable()
	{
		Schema::dropIfExists('payment_gateways');

  		Schema::create('payment_gateways', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->string('name', 255);
	            $table->enum('type', ['stored', 'non-stored'])->default('stored');	   
	            $table->timestamps();	            
	            $table->softDeletes();        
        });

        return $this;
	}

	public function createSuperpowerPackagesGatewaysTable()
	{
		Schema::dropIfExists('superpowerPackagesGateways');

  		Schema::create('superpowerPackagesGateways', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->integer('package_id'); 
	            $table->integer('gateway_id'); 
	            $table->timestamps();	            
	            $table->softDeletes();   
        });

        return $this;
	}

	public function createCreditPackagesGatewaysTable()
	{
		Schema::dropIfExists('creditPackagesGateways');

  		Schema::create('creditPackagesGateways', function (Blueprint $table) {
	    
	            $table->bigIncrements('id');
	            $table->integer('package_id'); 
	            $table->integer('gateway_id'); 
	            $table->timestamps();	            
	            $table->softDeletes();   
        });

        return $this;
	}


	public function createTables()
	{
		if(isset($this->skip_create_tables) && $this->skip_create_tables) {
			return $this;
		}

		return $this
			->createSocialLoginsTable()
			->createFieldsTable()
			->createUserFieldsTable()
			->createFieldSectionsTable()
			->createFieldOptionsTable()
			->createSettingsTable()
			->createPluginsTable()
			->createThemesTable()
			->createPhotosTable()
			->createBlockUsersTable()
			->createCreditHistoryTable()
			->createCreditPackagesTable()
			->createCreditsTable()
			->createEncounterTable()
			->createInterestsTable()
			->createMatchesTable()
			->createProfileTable()
			->createSuperPowerPackagesTable()
			->createSuperPowerHistoryTable()
			->createTransactionTable()
			->createUserTable()
			->createUserAbuseReportsTable()
			->createPhotoAbuseReportsTable()
			->createUserInterestsTable()
			->createUserSettingsTable()
			->createUserSocialLoginTable()
			->createUserSuperPowersTable()
			->createPaymentsTable()
			->createPaymentGatewaysTable()
			->createSuperpowerPackagesGatewaysTable()
			->createCreditPackagesGatewaysTable()
			->createVisitorsTable();
	}




	//This method is checking read and write permissins
	public function checkReadWritePermissions (&$error) {
		
		if (!$this->checkPermissionStoragePath ()) {

			array_push($error, storage_path(). ' should be made writable.');
		}

		//checking routes.php file permission writable
		if (!$this->checkPermissionRoutesFile ()) {

			array_push($error, 'app/Http/routes.php may not exists or may not be writable.');
		}

		if (!$this->checkPermissionAppFile ()) {

			array_push($error, 'config/app.php may not exists or may not be writable.');
		}

		if (!$this->checkPermissionDatabaseFile ()) {

			array_push($error, 'config/database.php may not exists or may not be writable.');
		}

		

		if (!$this->checkPermissionBootCachePath ()) {

			array_push($error, base_path() . '/bootstrap/cache should be made writable or create one and make writable.');
		} 
			

		if (!$this->checkPermissionPublicPath ()) {

			array_push($error, public_path(). ' should be made writable.');
		}

		if (!$this->checkResourcesPathPermission()) {

			array_push($error, base_path() . '/resources should be writable for plugiin multilanguage.');
		}
 
		if (!$this->checkPermissionBootCacheServicesFile()) {

			array_push($error, base_path() . '/bootstrap/cache/services.json should be write and readable.');
		}
			
 
		if(count($error) > 0)
			return false; //error
		else
			return true; //no error
	}

	public function checkResourcesPathPermission () {

		$path = base_path() . '/resources';

		return (is_writable($path)) ? true : false;
	}

	//check plugin path permission
	public function checkLangPathPermission () {

		$path = base_path() . '/resources/lang';

		return (is_writable($path)) ? true : false;
	}




	public function checkPermissionBootCachePath () {

		$path = base_path() . '/bootstrap/cache';

		if (is_writable($path)) {

			return true;

		} else {

			return false;
		}
	}

	public function checkPermissionBootCacheServicesFile () {

		$path = base_path() . '/bootstrap/cache/services.json';

		if (is_writable($path)) {

			return true;

		} else {

			return false;
		}
	}



	public function checkPermissionDatabaseFile () {

		$routesPath = base_path('config') . '/database.php';


		if (!is_writable($routesPath)) {

			return false;

		} else {

			return true;
		}

	}

	public function checkPermissionAppFile () {

		$routesPath = base_path('config') . '/app.php';


		if (!is_writable($routesPath)) {

			return false;

		} else {

			return true;
		}

	}

	public function checkPermissionRoutesFile () {

		$routesPath = app_path('Http') . '/routes.php';

		if (!is_writable($routesPath)) {

			return false;

		} else {

			return true;
		}

	}

	public function checkPermissionStoragePath()
	{
		$storage_path = storage_path();

		if(is_writable($storage_path))
			return true;
		else
			return false;
	}

	public function checkPermissionAppPath()
	{
		$app_path = app_path();

		if(is_writable($app_path))
			return true;
		else
			return false;
	}


	public function checkPermissionConfigPath()
	{
		$config_path = base_path() . '/config';

		if(is_writable($config_path))
			return true;
		else
			return false;
	}

	public function checkPermissionPublicPath()
	{
		$public_path = public_path();

		if(is_writable($public_path))
			return true;
		else
			return false;
	}

	public function get_activated_theme_by_role ($role) {
	    
        return $this->themes->where('isactivated', 'activated')->where('role', $role)->first();
    }

	public function themesConfigPath(){
		return config_path('themes.php');
	}
    
    public function syncThemesWithConfig(){
	    
	    $parent_theme       = $this->get_activated_theme_by_role('parent');
        $child_theme        = $this->get_activated_theme_by_role('child');
        
        $arr = array();
        $arr["parent"] = serialize($parent_theme);
        $arr["child"] = serialize($child_theme);
		$arrayString = var_export($arr, true);
        $arrayString = "<?php return \n {$arrayString};"; 
        file_put_contents($this->themesConfigPath(), $arrayString, LOCK_EX);
        
    }
	

	public function installTheme($theme)
    {
      	//creating theme directory in public folder with js, css, fonts, images
     	$this->createDir($theme);
     	$this->copyAssets($theme);    
     	
     	$dir = base_path()."/resources/views/themes/{$theme}";
     	$theme_info = "{$dir}/{$theme}.php";
			
		require_once $theme_info;
					
		$themeClass = "{$theme}\\{$theme}";
		$themeObj = new $themeClass; 
				
		//checkint all following methos exixt's or not in every theme info class
		if($themeObj instanceof ThemeInterface)
		{

     		$this->addTheme([
				'name'        => $theme,
				'author'      => $themeObj->author(),
				'description' => $themeObj->description(),
				'version'     => $themeObj->version(),
				'website'     => $themeObj->website(),
				'isactivated' => 'activated',
				'role'        => 'parent',
            ]);
  		}
  		else
  			throw new \Exception('No ' . $theme. ' Found');


  		$this->syncThemesWithConfig();
       
    }

    //this function creates all required directories for theme in public folder
	public function createDir($theme)
	{
		if(!Storage::has('/public/themes/'. $theme))
			Storage::makeDirectory('/public/themes/'. $theme);
	}

	//copy assets contents to themes/{theme} folder
	public function copyAssets($theme)
	{
		$src  = base_path()."/resources/views/themes/{$theme}/assets";
		$dest = public_path()."/themes/{$theme}";

		//create themes directory in public folder if no exists
		$theme_path = public_path().'/themes';
       	if(!file_exists($theme_path)) {
           mkdir($theme_path, 0777);
       	}

		if(file_exists($src))
		{
			$this->copyFolder($src, $dest); 
		}		
		
	}


	//this function creates an entry in themes table for theme installed
	public function addTheme($data)
	{
		$theme = new $this->themes;

		foreach($data as $key => $value)
			$theme->$key = $value;

		$theme->save();
	}


	public function postDataToURL($url, $postVars, $responseJson = false) 
	{
		$postData = http_build_query($postVars);

		$opts = [
			'http' => [
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $postData
		    ]
		];

		$context  = stream_context_create($opts);

		$result = file_get_contents($url, false, $context);

		return ($responseJson) ? json_decode($result, true) : $result;

	}


	public function moveFiles()
  	{
        $error = array();

        if($this->checkReadWritePermissions($error)){
                
            $core_routes = Storage::get('app/Installer/routes.stub');
            Storage::put('/app/Http/routes.php', $core_routes);
            $core_app_config = Storage::get('app/Installer/app.stub');
            Storage::put('/config/app.php', $core_app_config);
        }

       	return $this;
  	}

}
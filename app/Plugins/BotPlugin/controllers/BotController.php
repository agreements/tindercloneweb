<?php

namespace App\Http\Controllers;

use App\BotPlugin\Repositories\BotRepository;
use App\Repositories\ProfileRepository;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Components\Plugin;
use Validator;


class BotController extends Controller {

	protected $botRepo = null;
    protected $profileRepo = null;

	public function __construct (BotRepository $botRepo, ProfileRepository $profileRepo) {

		$this->botRepo = $botRepo;
        $this->profileRepo = $profileRepo;
	}

    public function showBots () {

    	$settings = $this->botRepo->getSettings ();
    	$bots = $this->botRepo->getBots ();

        $overall = $this->botRepo->getTotalActiveBotsCount ();
        $totalaccount = $this->botRepo->getTotalBotUsersCount ();

    	return Plugin::view('BotPlugin/bots', [
        
            'bots' => $bots, 
            'settings' => $settings,
            'overall' => $overall,
            'totalaccount' => $totalaccount,            

        ]);

    }



    public function settings (Request $request) {
        
    	try {

    		$no_of_bots = $request->no_of_bots;

    		if ( !$no_of_bots || $no_of_bots < 1) {

    			return response()->json(['status' => 'error',
                                    'message' => trans('app.required_number_bots')
                ]);

    		}

            if(!($no_of_bots <= $this->botRepo->getCountBots())) {

                return response()->json(['status' => 'error',
                                'message' => trans_choice('app.create_bots',0)
                ]);
            }

    		$this->botRepo->setSettings($no_of_bots);

    		return response()->json(['status' => 'success',
                                    'message' => trans('app.save_bot_set')
            ]);


    	} catch (\Exception $e) {

    		return response()->json(['status' => 'error',
                                    'message' => trans('app.fail_bot_set')
            ]);
    	}
    }



    public function deleteBot (Request $request) {

    	$name = '';

    	try {

    		$name = $request->name;
    		$this->botRepo->deleteBot ($request->id);

    		return response()->json(['status' => 'success',
                                    'message' => $name . ' '.trans('app.bot_delete_success')
            ]);

    	} catch (\Exception $e) {

    		return response()->json(['status' => 'error',
                                    'message' => trans('app.bot_delete_fail').' ' . $name
            ]);
    	}
    }



    public function activateBot (Request $request) {

    	$name = '';

    	try {

    		$name = $request->name;
    		$this->botRepo->activateBot ($request->id);

    		return response()->json(['status' => 'success',
                                    'message' => $name . ' '.trans('app.bot_active_success')
            ]);

    	} catch (\Exception $e) {

    		return response()->json(['status' => 'error',
                                    'message' =>  trans('app.bot_active_fail').' ' . $name
            ]);
    	}
    }













    public function deactivateBot (Request $request) {

    	$name = '';

    	try {

    		$name = $request->name;
    		$this->botRepo->deactivateBot ($request->id);

    		return response()->json(['status' => 'success',
                                    'message' => $name . ' '.trans('app.bot_deactive_success')
            ]);

    	} catch (\Exception $e) {

    		return response()->json(['status' => 'error',
                                    'message' => trans('app.bot_deactive_fail').' ' . $name
            ]);
    	}
    }




    public function showCreate () {

        $field_sections = $this->profileRepo->get_fieldsections();

        return Plugin::view('BotPlugin/bot_create',[
                'field_sections' => $field_sections,
            ]);
    }




    //this method will creat bot
    public function createBot (Request $request) {

        $validator = $this->botRepo->validate ($request);
        
        //if input validation fails then response validation failed
        if ($validator->fails()) {
            
            return response()->json(['status' => 'warning',
                                    'message' => $validator->errors()->all()[0]
            ]);

        }


        try {

        	$data = $request->all();

	        //saving profile picture
	        $profile_pic_name = $this->botRepo->saveProfilePicture ($data['profile_pic']);

	        $data = $this->botRepo->modifyBotCreationData($data, $profile_pic_name);	        
	        //saving data to bot table
	        $this->botRepo->createBot($data);

			
            $bot_count = $this->botRepo->getCountBots();
            if ($bot_count < 2) {
                $this->botRepo->setSettings(1);
            }

			//success response	        
	        return response()->json(['status' => 'success',
                                    'message' => trans('app.bot_create_success')
            ]);



        } catch (\Exception $e) {

        	return response()->json(['status' => 'error',
                                    'message' => $e->getMessage()
            ]);

        }
	        
    	
    } 





    public function showBotUsers () {

        $bot_users = $this->botRepo->getAllBotUsers();

        return Plugin::view('BotPlugin/bot_users', [
            'bot_users' => $bot_users, 
        ]);
    }




    public function doAction (Request $request) {
      
        try {


            $action = $request->_action;
            $bot_ids = explode(',', $request->data);

            if ($action == 'activate') {

                $this->botRepo->activateBotUsers ($bot_ids);

                 return response()->json(['status' => 'success',
                                    'message' => trans('app.bot_users_activate')
                ]);

            } else if ($action == 'deactivate') {

                $this->botRepo->deactivateBotUsers ($bot_ids);

                return response()->json(['status' => 'success',
                                    'message' => trans('app.bot_users_deactivate')
                ]);
            }



        } catch (\Exception $e) {

            return response()->json(['status' => 'error',
                                    'message' => trans('app.task_failed')
            ]);

        }
          


    }



    public function uploadPhotos(Request $request)
    {   
        $bot_id = $request->bot_id;
        $photos = $request->photos;

        $response = $this->botRepo->uploadPhotos($bot_id, $photos);
        return redirect(url('admin/plugin/bots'));
    }


    public function getBotPhotos(Request $request)
    {
        $bot_id = $request->bot_id;
        $photos = $this->botRepo->getBotPhotos($bot_id);
        return response()->json(["status" => "success", "photos" => $photos]);
    }

}

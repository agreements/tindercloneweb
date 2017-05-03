<?php

namespace App\BotPlugin\Repositories;

use Validator;
use App\Models\Bot;
use App\Models\Settings;
use Hash;
use App\Models\Photo;
use App\Models\BotPhoto;
use App\Models\User;
use App\Models\BotFields;
use App\Models\UserFields;
use App\Repositories\ProfileRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Components\Plugin;
use DB;

class BotRepository
{

    public function __construct(
        ProfileRepository $profileRepo, 
        BotFields $botFields, 
        UserFields $userFields, 
        Photo $photo, 
        User $user, 
        Settings $settings,
        UtilityRepository $utilityRepo,
        Bot $bot,
        BotPhoto $botPhoto
    )
    {
        $this->profileRepo = $profileRepo;
        $this->botFields   = $botFields;
        $this->userFields  = $userFields;
        $this->photo       = $photo;
        $this->user        = $user;
        $this->settings    = $settings;
        $this->utilityRepo = $utilityRepo;
        $this->bot         = $bot;
        $this->botPhoto    = $botPhoto;
    }


    public function getAllBotUsers() 
    {

        $users = $this->user->withTrashed()
                ->Where('username', 'like', '%@bot.bot')
                ->orderBy('created_at', 'desc')
                ->paginate(100);

        $users->setPath('bot_users');
    
        return $users;

    }

    public function getCountBots () {
        return $this->bot->count();
    }

    public function activateBotUsers ($bot_ids) {


        $this->user->whereIn('id', $bot_ids)
            ->update([
                'activate_user' => 'activated', 
                'activate_token' => ''
            ]);
    }



    public function deactivateBotUsers ($bot_ids) {


        $this->user->whereIn('id', $bot_ids)
            ->update([
                'activate_user' => 'deactivated', 
                'activate_token' => ''
            ]);
    }




    public function getTotalActiveBotsCount () {

        return $this->bot->where('isactive', '=', 'true')->count();

    }

    public function getTotalBotUsersCount () {

        return $this->user->where('username', 'like', '%@bot.bot')->count();

    }


	public function getBots () {

		return $this->bot->with('photos')->orderBy('created_at', 'desc')->get();

	}

	public function getSettings () {

		$settings = [];

		$settings['no_of_bots'] = $this->settings->get('no_of_bots');
		$settings['bot_gender_filter'] = $this->settings->get('bot_gender_filter');

		return $settings;

	}

	public function setSettings ($no_of_bots) {

		$this->settings->set('no_of_bots', $no_of_bots);
	}


	public function deleteBot ($botID) {

		$bot = $this->bot->find($botID);
        if($bot) {
            $this->botFields->where('bot_id', $bot->id)->forceDelete();
            $bot->forceDelete();
            return true;
        }
		return false;
	}





	public function deactivateBot ($botID) {

		$bot = $this->bot->find($botID);

		$bot->isactive = 'false';
		$bot->save();
	}



	public function activateBot ($botID) {

		$bot = $this->bot->find($botID);

		$bot->isactive = 'true';
		$bot->save();
	}


    public function createBotPhoto($bot_id, $photo_name)
    {
        $botPhoto = new $this->botPhoto;
        $botPhoto->bot_id = $bot_id;
        $botPhoto->photo_name = $photo_name;
        $botPhoto->save();
        return $botPhoto;
    }


	public function createBot($data) {

	    $bot = new $this->bot;

        $bot->name = $data['name'];
        $bot->joining = $data['joining'];
        $bot->dob = $data['dob'];
        $bot->gender = $data['new_gender'];
        $bot->status = $data['status'];
        $bot->aboutme = $data['aboutme'];
        $bot->password = $data['password'];
        $bot->profile_pic = $data['profile_pic'];
        $bot->isactive = $data['isactive'];

        $bot->save();


        $this->createBotPhoto($bot->id, $data['profile_pic']);

        $sections = $this->profileRepo->get_fieldsections();
        foreach($sections as $section) {

            foreach($section->fields as $field)
            {
                $code = $field->code;
                if(isset($data[$code]))
                {

                    if(is_array($data[$code])) {

                        foreach($data[$code] as $option) {
                            $this->createBotField($bot->id, $field->id, $option);
                        }

                    } else {
                        $this->createBotField($bot->id, $field->id, $data[$code]);
                    }

                }
            }
        }
	
	}

    public function createBotField($botId, $fieldId, $value)
    {
        $botField = new $this->botFields;
        $botField->bot_id = $botId;
        $botField->field_id = $fieldId;
        $botField->value = $value;
        $botField->save();

        return $botField;
    }


	public function modifyBotCreationData ($data, $profile_pic_name) {

		unset($data['_token']);
        unset($data['password_confirmation']);


        $data['profile_pic'] = $profile_pic_name;
        $data['new_gender'] = $this->profileRepo->getFieldValue($data['gender']);
        $data['isactive'] = 'true';
        $data['dob'] = \DateTime::createFromFormat('d/m/Y', $data['dob']);
        $data['joining'] = \DateTime::createFromFormat('d/m/Y', $data['joining']);
        $data['password'] = Hash::make($data['password']);

        return $data;

	}


	// public function getValidationMessages () {

 //    	return $messages = [

 //            'required'             => 'The :attribute is required.',
 //            'profile_pic.required' => 'Choose Profile Picture.',
 //            'max'                  => 'The :attribute can be maximum :max in length.',
 //            'min'                  => 'The :attribute must be minimum :min in length.',
 //            'date_format'          => 'The :attribute format DD/MM/YYYY.',
 //            'before'               => 'You must be at least 18 years old.',
 //            'confirmed'            => 'Password not matched.',
 //            'unique'               => ':attribute exists already.',

 //        ];
 //    }


    public function validate ($request) {

    	//creating validator for login requests
        $validator = Validator::make($request->all() , [

            'name'                  => 'required|max:100|unique:bot,name',
            'profile_pic'           => 'required',
            'dob'                   => 'required|date_format:d/m/Y|before:18 years ago',
            'joining'               => 'required|date_format:d/m/Y',
            'aboutme'               => 'required|max:500',
            'status'                => 'required|max:100',
            'password'              => 'required|min:8|max:100|confirmed',
            'password_confirmation' => 'required|min:8|max:100',

        ]);

        return $validator;

    }


    public function saveProfilePicture ($file) {

        $this->utilityRepo->validImage($file, $ext);
        $fileName = $this->utilityRepo->generate_image_filename('bot_', $ext);
        $this->profileRepo->save_resize_photo($file, $fileName);      

        return $fileName;
    } 



    //create bot users
    public function createBotUsers ($user) {
 
        $no_of_bots = $this->settings->get('no_of_bots');
        $this->cloneBots($user, $no_of_bots);

    }

    public function cloneBots ($user, $count) {

        $bots = $this->bot->where('isactive', '=' ,'true')
                ->take($count)
                ->get();

        foreach ($bots as $bot) {

            $this->doClone($user, $bot);

        }

    }

    public function doClone ($user, $bot) {

        $newUser = new $this->user;

        $email = uniqid($bot->name) . '@bot.bot';
        $newUser->username = str_replace(' ', '', $email);
        $newUser->name = $bot->name;
        $newUser->password = $bot->password;
        $newUser->gender = $bot->gender;
        $newUser->dob = $bot->dob;
        $newUser->city = $user->city;
        $newUser->country = $user->country;
        $newUser->profile_pic_url = $bot->profile_pic;
        $newUser->status = $bot->status;
  
        $newUser->activate_token = '';
        $newUser->password_token = '';
        $newUser->activate_user = 'activated';
        $newUser->register_from = 'Admin Bot';
        $newUser->verified = 'verified';
        $newUser->latitude = $user->latitude;
        $newUser->longitude = $user->longitude;
        
        $newUser->created_at = strtotime($bot->joining);
        $newUser->updated_at = strtotime($bot->joining);
        //$newUser->remember_token = '';

        $newUser->save();
        $newUser->createAndSaveSlugName();
        $this->copyAllBotPhotosToUser($bot, $newUser);


       //creating user profile
        $bot_fields = $this->botFields->where('bot_id',$bot->id)->get();

        foreach($bot_fields as $field) {
            $user_field = new $this->userFields;
            $user_field->user_id = $newUser->id;
            $user_field->value = $field->value;
            $user_field->field_id = $field->field_id;
            $user_field->save();
        }





        $newUser->profile->aboutme = $bot->aboutme;
        $newUser->profile->save();


    }

    public function getAllBotPhotos($bot_id)
    {
        return $this->botPhoto->where('bot_id', $bot_id)->get();
    }

    public function copyAllBotPhotosToUser($bot, $user)
    {
        $botPhotos = $this->getAllBotPhotos($bot->id);

        foreach($botPhotos as $botPhoto){
            $photo = new $this->photo;
            $photo->userid = $user->id;
            $photo->source_photo_id = '';
            $photo->photo_source = 'bot_photo';
            $photo->photo_url = $botPhoto->photo_name;
            $photo->save();
        }

    }

    //This methdo will serach users withing 100 km radious
    //return no of users

    public function countNearByUsers($lat, $lng, $distance, $unit) {
     
        $nearbyusers = [];

        if($unit == "km") {

            $varR = 111.045;

        } else {

            $varR = 69;
        } 


        $userLat = number_format($lat, 6, '.', '');
        $userLng = number_format($lng, 6, '.', '');

        $minlng = $userLng-($distance/abs(cos(deg2rad($userLat))*$varR));
        $maxlng = $userLng+($distance/abs(cos(deg2rad($userLat))*$varR));
        $minlat = $userLat-($distance/$varR);
        $maxlat= $userLat+($distance/$varR);

        $minlng = number_format($minlng, 6, '.', '');
        $maxlng = number_format($maxlng, 6, '.', '');
        $minlat = number_format($minlat, 6, '.', '');
        $maxlat = number_format($maxlat, 6, '.', '');

    
        $count = $this->user->whereBetween('latitude', [$minlat, $maxlat])
                    ->whereBetween('longitude', [$minlng, $maxlng])
                    ->count();

        return $count;
       
    }

    public function uploadPhotos($bot_id, $photos)
    {
        foreach($photos as $photo){
            $photo_name = $this->saveProfilePicture($photo);
            $this->createBotPhoto($bot_id, $photo_name);
            Plugin::fire('image_watermark', $photo_name);
        }
       
    }


    public function getBotPhotos($bot_id)
    {   
        $original_photo_base_url = url('uploads/others/original');
        return $this->botPhoto->where('bot_id', $bot_id)
                            ->select(['id', 'photo_name', DB::raw("concat('".$original_photo_base_url."/', photo_name) as original_photo_url")])
                            ->get()->toArray();
    }

}

    

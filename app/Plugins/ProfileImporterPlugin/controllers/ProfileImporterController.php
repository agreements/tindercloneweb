<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use App\Repositories\RegisterRepository;
use App\Repositories\BotRepository;
use App\Models\Bot;
use App\Models\BotFields;
use App\Repositories\Admin\GeneralManageRepository;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Components\Plugin;
use Validator;
use Excel;
use App\Models\User;
use Config;
use Input;
use Storage;
use App\Models\FieldOptions;
use App\Models\UserFields;
use App\Models\Photo;
use Hash;

class ProfileImporterController extends Controller {

    protected $profileRepo;

    public function __construct (ProfileRepository $profileRepo) {

        $this->profileRepo = $profileRepo;
        $this->registerRepo = app('App\Repositories\RegisterRepository');
    }


	public function showimport()
	{
		$field_sections = $this->profileRepo->get_fieldsections();
		return Plugin::view('ProfileImporterPlugin/importer',['field_sections' => $field_sections]);
	}
	
	
	public function import(Request $request) {
		ini_set('max_execution_time', 0);
		$input_arr = $request->all();

		$file = Input::file('file')->getRealPath();

		$cols['email']    = $request->email;
		$cols['id']       = $request->id;
		$cols['fname']    = $request->fname;
		$cols['lname']    = $request->lname ? $request->lname : '';
		$cols['dob']      = $request->dob;
		$cols['gender']   = $request->gender;

		if ($input_arr['pwd-for'] == 0 && !$request->password_all) {
			return redirect('admin/pluginsettings/profileimporter')->with('error', "Must provide password");
		}


		$cols['password'] = ($input_arr['pwd-for'] == 0) ? Hash::make($request->password_all) : $request->password_column;



		if($input_arr['location'] == 0) {
			$cols['latitude']  = $request->lat;
			$cols['longitude'] = $request->lng;
			$cols['city']      = $request->city;
			$cols['country']   = $request->country;
		} else {
			$cols['latitude']  = $input_arr['col-lat'];
			$cols['longitude'] = $input_arr['col-lng'];	
			$cols['city']      = $input_arr['col-city'];
			$cols['country']   = $input_arr['col-country'];
		}

		
		$photo_folder = $request->photo_folder;


		$sections = app("App\Repositories\ProfileRepository")->get_fieldsections();

		$profile_fields_given = array();
		$col_sep = $request->columns_separate;
		foreach($sections as $section) {
			foreach($section->fields() as $field) {
				if($input_arr[$field->code]) {
					$cols[$field->id] = $input_arr[$field->code];
					array_push($profile_fields_given, $field->id);
				}
			}
		}

		
		Config::set('excel.csv.delimiter', $col_sep);
		
		if ($request->import_as == 'user') {
			self::profileImportAsUsers($file, $col_sep,$photo_folder,$cols,$profile_fields_given,$input_arr);
		} else {
			self::profileImportAsBots($file, $col_sep,$photo_folder,$cols,$profile_fields_given,$input_arr);
		}


		

		return redirect('admin/pluginsettings/profileimporter')->with('success', "Users created Successfully");

	}


	public static function profileImportAsBots ($file, $col_sep,$photo_folder,$cols,$profile_fields_given,$input_arr) {
		Excel::load($file, function($reader) use ($col_sep,$photo_folder,$cols,$profile_fields_given,$input_arr) {

			$reader->noHeading();
		    $arr = $reader->toArray();
		    
		    for($i=1; $i < sizeof($arr); $i++) {
		    

		    	$data = $arr[$i];
		    	
		    	if($input_arr['pwd-for'] == 0) {
					$user_vars['password'] = $cols['password'];
		    	} else {
					$user_vars['password'] = ($input_arr['pwd-hashed'] == 0) ? $data[$cols['password']] : Hash::make($data[$cols['password']]);
				}

				$user_vars['name']      = $data[$cols['fname']];
				$user_vars['dob']       = \DateTime::createFromFormat($input_arr['dob_format'], $data[$cols['dob']]);
				$user_vars['joining']   = date('Y-m-d');

				if($cols['lname'] != '')
					$user_vars['name']  .= ' ' . $data[$cols['lname']];


				try {
		    		$bot = new Bot;

			        $bot->name = $user_vars['name'];
			        $bot->joining = $user_vars['joining'];
			        $bot->dob = $user_vars['dob'];
			        $bot->gender = self::validGender($data[$cols['gender']]);
			        $bot->password = $user_vars['password'];
			        $bot->profile_pic = "";
			        $bot->isactive = "true";

			        $bot->save();

			      

					$option = FieldOptions::where('code',$bot->gender)->first();	
			        $user_fields = new BotFields;
	   				$user_fields->bot_id = $bot->id;
	   				$user_fields->value = $option->id;
	   				$user_fields->field_id = $option->field_id;
   					$user_fields->save();


		         	$dir = public_path().'/'.$photo_folder.'/'.$data[$cols['id']].'*.*';
					$images = glob($dir);
					if (isset($images[0])) {
						$filename_path = explode('/', $images[0]);
						$filename = str_random(8) . end($filename_path);
						app("App\Repositories\ProfileRepository")->save_resize_photo($images[0],$filename);
						$bot->profile_pic = $filename;
						$bot->save();
					}
					

			    	foreach($profile_fields_given as $profile_field) {

			    		if($data[$cols[$profile_field]] != '') {
			    			
			    			$field = \App\Models\Fields::where('id', $profile_field)->first();
			    			
			    			if ($field->type == "dropdown") {

			    				$option = FieldOptions::where('field_id','=',$profile_field)->where('name','like', strtolower($data[$cols[$profile_field]]) .'%')->first();
			    				$user_fields = new BotFields;
				   				$user_fields->bot_id = $bot->id;
				   				if($option)
				   					$user_fields->value = $option->id;
				   				else
				   					continue;
				   				$user_fields->field_id = $profile_field;
			   					$user_fields->save();

			    			} else {
			    				$user_fields = new BotFields;
				   				$user_fields->bot_id = $bot->id;
				   				$user_fields->value = $data[$cols[$profile_field]];
				   				$user_fields->field_id = $profile_field;
			   					$user_fields->save();
			    			}
			   			}
			   		}
				} catch(Exception $e) {dd($e->getMessage());}
	    	}
		});
	}


	public static function profileImportAsUsers ($file, $col_sep,$photo_folder,$cols,$profile_fields_given,$input_arr) {
		
		Excel::load($file, function($reader) use ($col_sep,$photo_folder,$cols,$profile_fields_given,$input_arr) {

			$reader->noHeading();
		    $arr = $reader->toArray();
		    
		    for($i=1; $i < sizeof($arr); $i++) {
		    // for($i=1; $i < 2; $i++) {

		    	$data = $arr[$i];
		    	
		    	if($input_arr['pwd-for'] == 0) {
					$user_vars['password'] = $cols['password'];
		    	} else {
					$user_vars['password'] = ($input_arr['pwd-hashed'] == 0) ? $data[$cols['password']] : Hash::make($data[$cols['password']]);
				}


				if($input_arr['location'] == 0) {
					$user_vars['latitude']  = $cols['latitude'];
					$user_vars['longitude'] = $cols['longitude'];
					$user_vars['city']      = $cols['city'];
					$user_vars['country']   = $cols['country'];
				} else {

					if($cols['latitude'] == '' || $cols['longitude'] == '') {
						$coords = app("App\Repositories\ProfileRepository")->getLatLong($data[$cols['city']]);
						$user_vars['latitude']  = $coords['lat'];
						$user_vars['longitude'] = $coords['long'];
					} else {
						$user_vars['latitude']  = $data[$cols['latitude']];
						$user_vars['longitude'] = $data[$cols['longitude']];
					}

					$user_vars['city']    = $data[$cols['city']];
					$user_vars['country'] = $data[$cols['country']];
				}
				
				$user_vars['username']  = $data[$cols['email']];
				$user_vars['activate_user'] = 'activated';
				$user_vars['name']      = $data[$cols['fname']];
				$user_vars['dob']       = \DateTime::createFromFormat($input_arr['dob_format'], $data[$cols['dob']]);

				if($cols['lname'] != '')
					$user_vars['name']  .= ' ' . $data[$cols['lname']];

				$user_vars['gender'] = self::validGender($data[$cols['gender']]);
		    		
		    	
				try {
		    		$user = $this->registerRepo->register($user_vars);

		    		$option = FieldOptions::where('code',$user_vars['gender'])->first();	
					$user_field = new UserFields;
					$user_field->user_id = $user->id;
					$user_field->value = $option->id;
					$user_field->field_id = $option->field_id;
					$user_field->save();



			    	if($user && $photo_folder != '' && $cols['id'] != '' && $data[$cols['id']] != '') {
			    		
			    		$dir = public_path().'/'.$photo_folder.'/'.$data[$cols['id']].'*.*';

						$images = glob($dir);
						
						foreach($images as $image) {

							$filename_path = explode('/', $image);

							$filename = str_random(8) . end($filename_path);

							app("App\Repositories\ProfileRepository")->save_resize_photo($image,$filename);

							$photo_id = $filename;

							$album = new Photo;

				            $album->userid          = $user->id;
				            $album->photo_source    = 'CSV';
				            $album->photo_url       = $filename;
				            
				            $album->save();

				            $user->profile_pic_url = $filename;
				            $user->save();
						}

			    	}

			    	foreach($profile_fields_given as $profile_field) {

			    		if($data[$cols[$profile_field]] != '') {
			    			
			    			$field = \App\Models\Fields::where('id', $profile_field)->first();
			    			
			    			if ($field->type == "dropdown") {

			    				$option = FieldOptions::where('field_id','=',$profile_field)->where('name','like',strtolower($data[$cols[$profile_field]]).'%')->first();
			    				$user_fields = new UserFields;
				   				$user_fields->user_id = $user->id;
				   				if($option)
				   					$user_fields->value = $option->id;
				   				else
				   					continue;
				   				$user_fields->field_id = $profile_field;
			   					$user_fields->save();

			    			} else {
			    				$user_fields = new UserFields;
				   				$user_fields->user_id = $user->id;
				   				$user_fields->value = $data[$cols[$profile_field]];
				   				$user_fields->field_id = $profile_field;
			   					$user_fields->save();
			    			}
			   			}
			   		}
				} catch(Exception $e) {dd($e->getMessage());}
	    	}
		});
	}


	public static function validGender($gender) {
		$gender_field = (new GeneralManageRepository)->getGenderField();
		if($gender) {
			foreach ($gender_field->field_options() as $option) {
				if ($option->code == $gender) return $gender;
			}
		} 
		return self::randomGender($gender_field);
	}

	public static function randomGender ($gender_field) {
		return $gender_field->field_options()[0]->code;
	}


}
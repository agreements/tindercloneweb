<?php

namespace App\Repositories\Admin;

use Hash;
use App\Models\Admin;
use Validator;
use App\Repositories\Admin\UtilityRepository;

class AdminManagementRepository {

	public function __construct(Admin $admin) {
		$this->admin = $admin;
	}

	public function getAllAdmins () { return $this->admin->all(); }

	public function insertAdminData ($data) {

		$admin = clone $this->admin;
		
		foreach ($data as $key => $value) { 
			$admin->$key = $value; 
		}
		
		$admin->save();
	}


	public function changePassword ($admin_id, $new_password) {

        if(!$new_password) {

            $msg = trans_choice('admin.change_password', 0);
            return ['status' => 'error', 'message' => $msg];

        } elseif (strlen($new_password) < 8 || strlen($new_password) > 200 ) {

            $msg = trans_choice('admin.change_password', 1);
            return ['status' => 'error', 'message' => $msg];
        
        }

        $this->admin->where('id', $admin_id)->update(['password' => Hash::make($new_password)]);
        return ['status' => 'success'];

	}


	public function deleteAdmin ($admin_id) {

		 if (UtilityRepository::session_get('admin_id') == $admin_id) {
               
            $msg = trans_choice('admin.error_delete', 0);
            return ['status' => 'error', 'message' => $msg ];

         } else {
         	
         	$this->admin->destroy($admin_id);
            return ['status' => 'success'];
        }
	}






    public function createAdmin ($data) {
      	
      	//creating validator for admin create requests
        $validator = $this->validateAdminCreateCredentials($data);
    
        //if input validation fails then redirect to login view ageain
        if ($validator->fails ()) {

            $messages = $validator->errors()->all();
            UtilityRepository::session_set('createAdmin', $messages[0]);
            return false;

        } 

        if ($data["password"] === $data["confirm_password"]) {
           	
           	$data["password"] = Hash::make($data["password"]);
           	unset($data["confirm_password"]);
            $this->insertAdminData($data);

            UtilityRepository::session_set('createAdmin', 'Admin Created');
            return true;

        } else {
            UtilityRepository::session_set('createAdmin', 'password not matched');
            return false;
        }
            
        
    }



    public function validateAdminCreateCredentials ($request_data) {

        return Validator::make ($request_data, [

                'username'         => 'required|email|max:200|min:4|unique:admin,username',
                'name'             => 'required|max:100',
                'password'         => 'required|min:8|max:200',
                'confirm_password' => 'required|min:8|max:200',
        ]);
    }


}
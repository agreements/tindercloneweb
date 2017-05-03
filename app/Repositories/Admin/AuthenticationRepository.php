<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;
use Validator;

use App\Models\Admin;
use App\Repositories\Admin\UtilityRepository;

class AuthenticationRepository {

	public function __construct(Admin $admin) {
		$this->admin = $admin;
	}

	public function doLogin($username, $password, $ip) {

		$validator = $this->validateCredentials(["username" => $username, "password" => $password]);

        if ($validator->fails()) {

            $message = ['message' => trans('admin.login_failed')];
            return $this->redirect_to_login_with_errormsg($message);
        }
        

       
        if (!($admin = $this->findByUsername($username))) {

            $message = ['message' => $username.' '.trans('admin.not_registered')];
            return $this->redirect_to_login_with_errormsg($message);
        
        } else {

            if( $this->verify_password($password, $admin->password)){

                $this->storeAdminData([
                    'role'           => 'admin',
                    'name'           => $admin->name,
                    'admin_id'       => $admin->id,
                    'admin_username' => $admin->username
                ]);

                $this->set_last_login_data($admin, [
					'last_ip'    => $ip, 
					'last_login' => date('Y-m-d H:i:s')
                ]);

                return redirect()->intended('/admin/dashboard');
            } 
            else{

                $message = ['message' => trans('admin.wrong_password')];
                return $this->redirect_to_login_with_errormsg($message);
            }
        }
	}


	protected function validateCredentials ($request_data) {

        return Validator::make($request_data, [
            'username' => 'required|max:100',
            'password' => 'required|min:8',
        ]);

    }


    public function redirectWithErrors ($url, $message = []) {
        return redirect($url)->withErrors($message);
    }


    public function redirect_to_login_with_errormsg ($msg = []) {

        $url = '/admin/login';
        return $this->redirectWithErrors($url, $msg);
    }



	public function findByUsername ($username) {
		return $this->admin->where('username', $username)->first();
	}


	public function verify_password ($pass1, $pass2) {
		return password_verify($pass1, $pass2);
	}

	public function doLogout(){
		session()->forget('role');
        session()->forget('admin_username');
        session()->forget('name');
        session()->forget('admin_id');
        return true;
	}


	public function set_last_login_data ($admin, $data = []) {
		
		foreach ($data as $key => $value) {
			$admin->$key = $value;
		}

		$admin->save();
	}


	public function storeAdminData ($data = []){

        foreach ($data as $key => $value) {
            UtilityRepository::session_set($key, $value);
        } 
    }

}
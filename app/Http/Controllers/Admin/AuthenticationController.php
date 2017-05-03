<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\AuthenticationRepository;



class AuthenticationController extends Controller {    

    public function __construct (AuthenticationRepository $authRepo) {

        $this->authRepo = $authRepo;
    }


    public function showLogin() { return view('admin.login'); }


    public function doLogin (Request $request) {
  
       return $this->authRepo->doLogin($request->username, $request->password, $request->server()['REMOTE_ADDR']);
    }


    public function doLogout() {
        
        $this->authRepo->doLogout();
        return redirect('admin/login');
    }



}
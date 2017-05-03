<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Repositories\Admin\AdminManagementRepository;




class AdminManagementController extends Controller {

    protected $adminRepo;
    public function __construct (AdminManagementRepository $adminRepo) {
        $this->adminRepo = $adminRepo;
    }


    
    //this function shows the admin management view..
    //where admin can create and delete , update admin password
    //Route:: admin/users/adminmanagement
    public function showAdminManagement () {
        return view('admin.admin_management', ['admins' => $this->adminRepo->getAllAdmins()]);
    }




    //this function does all admin managent tasks
    //where admin can create and delete , update admin password
    //Route:: admin/users/adminmanagement
    public function updateAdmin (Request $request) {

        switch ($request->_task) {
            
            case 'createAdmin':
                
                $data = [
                    'name'             => $request->name,
                    'username'         => $request->username,
                    'password'         => $request->password,
                    'confirm_password' => $request->confirm_password,
                    'last_ip'          => $request->server()['REMOTE_ADDR'],
                    'last_login'       => date('Y-m-d H:i:s'),
                ];
                $this->adminRepo->createAdmin($data);

                return redirect('admin/users/adminmanagement');
                break;

            
            case 'delete_admin':
                $status = $this->adminRepo->deleteAdmin($request->id);
                return response()->json($status);
                break;

            
            case 'change_password':
                $status = $this->adminRepo->changePassword($request->id, $request->password);
                return response()->json($status);
                break;
            
            default:
                return redirect('admin/users/adminmanagement');
                break;
        }
            
         
    }



    
}


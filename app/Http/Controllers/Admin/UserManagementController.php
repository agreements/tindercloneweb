<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\UserManagementRepository;
use App\Components\Plugin;


class UserManagementController extends Controller {

    public function __construct(UserManagementRepository $userManageRepo) {
        $this->userManageRepo = $userManageRepo;
        $this->creditManageRepo = app('App\Repositories\Admin\CreditManageRepository');
    }

    public function showUserManagement () {

        return view('admin.admin_usermanagement', [
            'users' => $this->userManageRepo->getAllActivatedUsers()
        ]);
    }


    public function creditUsers(Request $request)
    {
        $user_ids = explode(",", $request->data);
        $credit_amount = $request->credit_amount;
        $response = $this->creditManageRepo->creditUsers($user_ids, $credit_amount);
        return response()->json($response);
    }


    public function activateUsersSuperpower(Request $request) 
    {
        $user_ids = explode(",", $request->data);
        $duration = $request->duration; 
        $response = $this->userManageRepo->activateUsersSuperpower($user_ids, $duration);
        return response()->json($response);
    }


    //this method will execute action like verify/unverify , activate/deactivate users.
    public function doUserManagementAction (Request $request) {

        $user_ids = explode(",", $request->data);
        $response = $this->userManageRepo->doAction($request->_action, $user_ids);
        return response()->json($response);

    }



    public function showDeactivatedUserManagement () {
        
        return view('admin.admin_deactivated_usermanagement', [
            'users' => $this->userManageRepo->getAllDeactivatedUsers()
        ]);
    }


    public function deleteUser (Request $request) {
        
        try {

            $user_ids = explode(',', $request->user_ids);
            $this->userManageRepo->delete_users_permenently($user_ids);

            Plugin::fire('users_deleted', [$user_ids]);

            return response()->json(['status' => 'success', 'message' => trans('admin.users_deleted')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]); //trans('admin.failed_to_delete_users')
        }
        
    }


}
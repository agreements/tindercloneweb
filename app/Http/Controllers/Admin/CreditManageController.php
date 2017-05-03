<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use App\Repositories\Admin\CreditManageRepository;
use App\Repositories\Admin\UtilityRepository;



class CreditManageController extends Controller {


    public function __construct (CreditManageRepository $creditManageRepo) {
        $this->creditManageRepo = $creditManageRepo;
    }
    

    public function creditManage () {

        $data = [

            'packs'                           => $this->creditManageRepo->getCreditPackagesForAdmin(),
            'superPowerPacks'                 => $this->creditManageRepo->getSuperPowerPackagesForAdmin(),
            'defaultcredits'                  => UtilityRepository::get_setting('defaultCredits'),
            'spotcredits'                     => UtilityRepository::get_setting('spotCredits'),
            'riseupcredits'                   => UtilityRepository::get_setting('riseupCredits'),
            'overallcredits'                  => $this->creditManageRepo->getOverallCredits(),
            'creditspurchagedthismonth'       => $this->creditManageRepo->getCreditsPurchasedThisMonth(),
            'creditspurchasedtoday'           => $this->creditManageRepo->getCreditsPurchasedToday (),
            'creditsusedoverall'              => $this->creditManageRepo->getCreditsUsedOverall (),
            'creditsusedthismonth'            => $this->creditManageRepo->getCreditsUsedThisMonth (),
            'creditsusedtoday'                => $this->creditManageRepo->getCreditsUsedToday (),
            'currency'                        => UtilityRepository::get_setting('currency'),
            'double_credits_superpower_users' => UtilityRepository::get_setting('double_credits_superpower_users'),
            'spotlight_only_superpowers'      => UtilityRepository::get_setting('spotlight_only_superpowers'),
            'peoplenearby_only_superpowers'   => UtilityRepository::get_setting('peoplenearby_only_superpowers'),
            'credits_module_available'   => UtilityRepository::get_setting('credits_module_available'),
        ];

        return view('admin.admin_credit_manage', $data);
    }




    public function defaultCredAdd (Request $request) {

        try {

            if ($request->defaultCredits == '') {

                return response()->json(['status' => 'error', 'message' => trans_choice('admin.default_credit_msg', 0)]);    
            }

            UtilityRepository::set_setting('defaultCredits', $request->defaultCredits);
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.default_credit_msg', 1)]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.default_credit_msg', 2)]);

        }
        
    }





    public function credAddAll (Request $request) {

        try {

            if ($request->credit == '') {

                return response()->json(['status' => 'error', 'message' => trans_choice('admin.credit_all_msg', 0)]);    
            }

            $this->creditManageRepo->credAddAll($request->credit);
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.credit_all_msg', 1)]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.credit_all_msg', 2)]);

        }
        
    }



    public function spotCred (Request $request) {

        try {

            if ($request->credits == '') {

                return response()->json(['status' => 'error', 'message' => trans_choice('admin.spot_credit_msg', 0)]);    
            }

            UtilityRepository::set_setting('spotCredits', $request->credits);
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.spot_credit_msg', 1)]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.spot_credit_msg', 2)]);

        }
        
        return redirect('/admin/creditManage');
    }




    public function riseupCred (Request $request) {

        try {

            if ($request->credits == '') {

                return response()->json(['status' => 'error', 'message' => trans_choice('admin.riseup_credit_msg', 0)]);    
            }

            UtilityRepository::set_setting('riseupCredits', $request->credits);
            
            return response()->json(['status' => 'success', 'message' => trans_choice('admin.riseup_credit_msg', 1)]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans_choice('admin.riseup_credit_msg', 2)]);

        }

    }


     public function setCurrency(Request $request) {

        try {

            $currency = $request->currency;

            if ($currency == '') {

                return response()->json(['status' => 'error']);
            }

            UtilityRepository::set_setting('currency', $currency);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error']);
        }
    }


    public function addPackage (Request $request) {

         $validator = $this->creditManageRepo->validateCreditPackage($request->all());

        if ($validator->fails()) {

            return response()->json (['status' => 'error', 'message' => $validator->errors()->all()[0]]);
        }


        try {

            $this->creditManageRepo->addPackage([

                'packageName' =>$request->packageName,
                'amount'      =>$request->amount,
                'credits'     =>$request->credits,
                "description" => $request->description

            ]);
            
            return response()->json (['status' => 'success', 'message' => trans_choice('admin.credit_pack_msg', 0)]);

        } catch (\Exception $e) {

           return response()->json (['status' => 'error', 'message' => trans_choice('admin.credit_pack_msg', 1)]);

        }

        
    }

    public function activate ($id) {

        try {

            $this->creditManageRepo->activate ($id);
            
            return response()->json (['status' => 'success', 'message' => trans_choice('admin.credit_pack_msg', 2)]);

        } catch (\Exception $e) {

            return response()->json (['status' => 'error', 'messages' => trans_choice('admin.credit_pack_msg', 3)]);
        }
        
    }


    public function deactivate($id)
    {
        try {

            $this->creditManageRepo->deactivate($id);
            
            return response()->json (['status' => 'success', 'message' => trans_choice('admin.credit_pack_msg', 4)]);

        } catch (\Exception $e) {

            return response()->json (['status' => 'error', 'messages' => trans_choice('admin.credit_pack_msg', 5)]);
        }
        
    }



    public function addSuperPowerPackage (Request $request) {

        $validator = Validator::make ($request->all(), [
           
            'package_name' => 'required|unique:superpowerpackages,package_name',
            'amount'      => 'required|numeric|min:1',
            'duration'     => 'required|numeric',

        ]);
        
        if ($validator->fails()) {

            return response()->json (['status' => 'error', 'message' => $validator->errors()->all()[0]]);
        }


        try {
			
            $this->creditManageRepo->addSuperPowerPackage([

                'package_name' => $request->package_name,
                'amount'       => $request->amount,
                'duration'     => $request->duration,
                "description" => $request->description
            ]);
            
            return response()->json (['status' => 'success', 'message' => trans_choice('admin.superpower_pack_msg', 0)]);

        } catch (\Exception $e) {
            return response()->json (['status' => 'error', 'message' => trans_choice('admin.superpower_pack_msg', 1)]);

        }

    }

    public function superPowerActivate ($id) {

        try {

            $this->creditManageRepo->superPowerActivate($id);
            
            return response()->json (['status' => 'success', 'message' => trans_choice('admin.superpower_pack_msg', 2)]);

        } catch (\Exception $e) {

            return response()->json (['status' => 'error', 'messages' => trans_choice('admin.superpower_pack_msg', 3)]);
        }
        
    }

    public function superPowerdeactivate ($id) {

        try {

            $this->creditManageRepo->superPowerdeactivate($id);
            
            return response()->json (['status' => 'success', 'message' => trans_choice('admin.superpower_pack_msg', 4)]);

        } catch (\Exception $e) {

            return response()->json (['status' => 'error', 'messages' => trans_choice('admin.superpower_pack_msg', 5)]);
        }
        
    }



    public function doubleCreditsSuperpowers (Request $req) {

        $double_credits = $req->double_credits == 'true' ? 'true' : 'false';

        UtilityRepository::set_setting('double_credits_superpower_users', $double_credits);
        return response()->json(["status" => "success"]);
    }


    public function spotlightOnlySuperpowers (Request $req) {

        $spotlight_only_superpowers = $req->spotlight_only_superpowers == 'true' ? 'true' : 'false';

        UtilityRepository::set_setting('spotlight_only_superpowers', $spotlight_only_superpowers);


        $peoplenearby_only_superpowers = UtilityRepository::get_setting('peoplenearby_only_superpowers');

        if ($peoplenearby_only_superpowers == 'true' && $spotlight_only_superpowers == 'true') {
            UtilityRepository::set_setting('credits_module_available', 'false');
        } else {
            UtilityRepository::set_setting('credits_module_available', 'true');
        }


        return response()->json(["status" => "success"]);

    }


    public function peoplenearbyOnlySuperpowers (Request $req) {

        $peoplenearby_only_superpowers = $req->peoplenearby_only_superpowers == 'true' ? 'true' : 'false';

        UtilityRepository::set_setting('peoplenearby_only_superpowers', $peoplenearby_only_superpowers);

         $spotlight_only_superpowers = UtilityRepository::get_setting('spotlight_only_superpowers');

        if ($peoplenearby_only_superpowers == 'true' && $spotlight_only_superpowers == 'true') {
            UtilityRepository::set_setting('credits_module_available', 'false');
        } else {
            UtilityRepository::set_setting('credits_module_available', 'true');
        }

        return response()->json(["status" => "success"]);
        
    }


    public function creditsModuleAvalable (Request $req) {

        $credits_module_available = $req->credits_module_available == 'true' ? 'true' : 'false';
        UtilityRepository::set_setting('credits_module_available', $credits_module_available);
        
        if($credits_module_available == 'false') {
            $this->creditManageRepo->removeCreditPayments();
        } else {
            $this->creditManageRepo->recoverCreditPayments();
        }
        
        return response()->json(["status" => "success"]);
        
    }

	



}
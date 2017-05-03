<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use App\Models\Match;
use App\Models\Encounter;
use App\Models\Visitor;
use App\Models\Photo;
use App\Models\Package;
use App\Models\Credit;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\CreditHistory;
use App\Models\Spotlight;
use App\Models\SuperPowerPackages;
use App\Models\CreditPackagesGateway;
use App\Models\PaymentGateway;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Support\Facades\DB;

use App\Components\Plugin;
use App\Components\PaymentInterface;

class CreditRepository implements PaymentInterface
{
	
	public function __construct(Package $package, Credit $credit, CreditHistory $credit_history, Transaction $transaction, User $user,CreditPackagesGateway $creditPackagesGateway, PaymentGateway $paymentGateway ){
		
		$this->package = $package;
		$this->credit = $credit;
		$this->credit_history = $credit_history;
		$this->transaction = $transaction;
		$this->user = $user;
		$this->themeRepo = app('App\Repositories\Admin\ThemeManageRepository');
		$this->packagesGateway = $creditPackagesGateway;
		$this->paymentGateway = $paymentGateway;
		
	}
	
	
	public function getPack($package)
	{
		$pack = $this->package->where('packageName', '=', $package)->first();
		return $pack;
	}

	public function getPackById($id)
	{
		return $this->package->where('id', '=', $id)->first();
	}

	public function getRiseupScreenshotUrl () {	

		$theme = $this->themeRepo->get_activated_theme_by_role('parent');
		return UtilityRepository::get_setting("{$theme->name}_riseup_screenshot");
	}

	
	//thsi funciton returns all credit packages except deleted
	public function getCreditPackages () {

		return $this->package->all();
	}



	public function getBalance($id)
	{
		$credit = $this->credit->where('userid', $id)->first();
		return $credit;
	}

	public function getRiseupCredits()
    {
        $riseupCredits = UtilityRepository::get_setting('riseupCredits');
        return ($riseupCredits == '') ? 0 : $riseupCredits;
    }

	public function transactionDetails ($gateway, $trans_id, $packid, $status, $logUser, $activity) {

		$packObj = $this->package->where('id',$packid)->first();
		$trans = $this->transaction;

		$trans->gateway = $gateway;
		$trans->transaction_id = $trans_id;
		$trans->amount = $packObj->amount;

		if ($status == 'SuccessWithWarning' || $status == 'Success' || $status == 'succeeded') {
			
			
			
			$trans->status = 'success';
			$trans->save();
			
			
			
			$history = $this->credit_history;

			$history->transTable_id = $trans->id;
			$history->userid = $logUser;
			$history->activity = $activity;
			$history->credits = $packObj->credits;
			$history->save();
			
			
			
			$user = $this->user->find($logUser);

			$double_credits = UtilityRepository::get_setting('double_credits_superpower_users');
			if ($double_credits == 'true' && $user->isSuperPowerActivated()) {
				$user->credits->balance = $user->credits->balance + ($packObj->credits * 2);
			} else {
				$user->credits->balance = $user->credits->balance + $packObj->credits;
			}
			
			$user->credits->save();
			
		}
		

	}
	
	public function gateway_packages($metadata) {
		
		$gateways = $this->paymentGateway->all();
		
		$gateways_arr = array();
		
		foreach($gateways as $gateway) {
			
			$obj = new \StdClass;
			
			$obj->name = $gateway->name;
			$obj->id = $gateway->id;
			$obj->packages = array();
			$gateways_arr[$gateway->id] = $obj;
		}
		
		
		
		$packages = $this->package->with('gateways')->get();
		//dd($packages);
		foreach($packages as $package) {
			
			$obj = new \StdClass;
			
			$obj->name = trans($package->name_code).' '.$package->amount.' : '.$package->credits.' '.trans('app.credits');
			$obj->id = $package->id;
			$obj->amount = $package->amount;
			$obj->description = trans($package->description_code);
			foreach($package->gateways as $gateway) {
				
				array_push($gateways_arr[$gateway->gateway_id]->packages,$obj);
				
			}
		}
		
		foreach($gateways_arr as $gateway) {
			if(count($gateway->packages) == 0) {
				unset($gateways_arr[$gateway->id]);
			}
			
		}
		
		return($gateways_arr);
	}

	public function payment_callback($contents) {
		        
        $packObj = $this->package->where('id',$contents['packid'])->first();
        
        if($contents['amount'] == $packObj->amount) {
	        if ($contents['status'] == 'SuccessWithWarning' || $contents['status'] == 'Success' || $contents['status'] == 'succeeded') {
		        
		    	$history = $this->credit_history;
	
				$history->transTable_id = $contents["transTable_id"];
				$history->userid =  $contents['id'];
				$history->activity = "topup";
				$history->credits = $packObj->credits;
				$history->save();
				
				$user = $this->user->find($contents['id']);
	
				$double_credits = UtilityRepository::get_setting('double_credits_superpower_users');
				if ($double_credits == 'true' && $user->isSuperPowerActivated()) {
					$user->credits->balance = $user->credits->balance + ($packObj->credits * 2);
				} else {
					$user->credits->balance = $user->credits->balance + $packObj->credits;
				}
				
				$user->credits->save();
		    }       
	            
	        Plugin::fire('credits_purchased', $contents);
	        
        }
	}

	public function all_packages($gateway_id) {
		
		$packages =  $this->package->all();
		
		$gateway_packages = $this->packagesGateway->where("gateway_id",$gateway_id)->get()->pluck("package_id")->toArray();
	
		$packages_arr = array();
		foreach($packages as $package) {
			
			$obj  = new \StdClass;
			
			$obj->id = $package->id;
			$obj->name = $package->packageName;
			$obj->amount = $package->amount;
			
			if(in_array($package->id,$gateway_packages)) {
				
				$obj->status = true;
			} else {
				
				$obj->status = false;
			}
			
			array_push($packages_arr,$obj);
		}
		
		return $packages_arr;
	}
	
	public function add_gateway_package($gateway_id, $package_id) {
		
		$gateway_package = clone $this->packagesGateway;
		$gateway_package->gateway_id = $gateway_id;
		$gateway_package->package_id = $package_id;
		$gateway_package->save();
	}
	
	
	public function remove_gateway_package($gateway_id, $package_id) {
		
		$this->packagesGateway->where("package_id",$package_id)->where("gateway_id",$gateway_id)->delete();
	}
	
	 public function payment_refund($contents) {

        $packObj = Package::withTrashed()->where('id',$packid)->first();
       

        if ($status == 'SuccessWithWarning' || $status == 'Success' || $status == 'succeeded') {
           
            $history = $this->credit_history;
            
            
			$history->transTable_id = $contents["transTable_id"];
			$history->userid =  $contents['id'];
			$history->activity = "refund";
			$history->credits = $packObj->credits;
			$history->save();
			
			$user = $this->user->find($contents['id']);

			$double_credits = UtilityRepository::get_setting('double_credits_superpower_users');
			if ($double_credits == 'true' && $user->isSuperPowerActivated()) {
				$user->credits->balance = $user->credits->balance - ($packObj->credits * 2);
			} else {
				$user->credits->balance = $user->credits->balance - $packObj->credits;
			}
			
			$user->credits->save();
            
    }    }
}

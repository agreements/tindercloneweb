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
use App\Models\UserSuperPowers;
use App\Models\Themes;
use App\Models\SuperpowerHistory;
use App\Models\SuperpowerPackagesGateway;
use App\Models\PaymentGateway;

use App\Components\Plugin;
use App\Components\PaymentInterface;

class SuperpowerRepository implements PaymentInterface {


    public function __construct(
        SuperPowerPackages $superPowerPackages, 
        Transaction $transaction, 
        User $user, 
        SuperpowerHistory $superpowerHistory, 
        UserSuperPowers $userSuperPowers, PaymentGateway $paymentGateway,SuperpowerPackagesGateway $superpowerPackageGateway
    ) {

        $this->superPowerPackages = $superPowerPackages;
        $this->transaction        = $transaction;
        $this->user               = $user;
        $this->superpowerHistory  = $superpowerHistory;
        $this->userSuperPowers    = $userSuperPowers;
		$this->paymentGateway = $paymentGateway;
		$this->packagesGateway = $superpowerPackageGateway;
    }
	
	public function getSuperPowerPack ($id) {
		return SuperPowerPackages::find($id);
	}

    public function getSuperPowerPackages() {
        return SuperPowerPackages::all();
    }

    public function transactionDetails ($gateway, $trans_id, $packid, $status, $logId, $metadata) {
        
        $invisible = json_decode($metadata)->invisible;
        $packObj = $this->superPowerPackages->where ('id', $packid)->first();
        $trans = clone $this->transaction;

        $trans->gateway = $gateway;
        $trans->transaction_id = $trans_id;
        $trans->amount = $packObj->amount;

        if ($status == 'SuccessWithWarning' || $status == 'Success' || $status == 'succeeded') {
            $trans->status = 'success';
            $trans->save();
            $user_superpower = $this->userSuperPowers->where('user_id','=',$logId)->first();
            if($user_superpower)
            {
                $user_superpower->user_id = $logId;
                $user_superpower->invisible_mode = $invisible;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$packObj->duration} days", strtotime(date('Y-m-d'))));
            }
            else
            {
                $user_superpower = clone $this->userSuperPowers;
                $user_superpower->user_id = $logId;
                $user_superpower->invisible_mode = $invisible;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$packObj->duration} days", strtotime(date('Y-m-d'))));
            }
            $user_superpower->save();
            $superpower_history = clone $this->superpowerHistory;
            $superpower_history->user_id = $logId;
            $superpower_history->trans_table_id = $trans->id;
            $superpower_history->superpower_package_id = $packObj->id;
            $superpower_history->save();
        }
        else
        {
            $trans->status = 'failure';
            $trans->save();
        }
    }

    //this funciton will return whether user has activated super power or not
    public function isSuperPowerActivated ($id) {
        
        return $this->user->find($id)->isSuperPowerActivated();
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
		
		
		
		$packages = $this->superPowerPackages->with('gateways')->get();
		//dd($packages);
		foreach($packages as $package) {
			
			$obj = new \StdClass;
			
			$obj->name = trans($package->name_code).' '.$package->amount.' : '.$package->duration.' '.trans('app.days');
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
		        
        $invisible = json_decode($contents['metadata'])->invisible;
        $packObj = $this->superPowerPackages->where ('id', $contents['packid'])->first();
        
        
        if($contents['amount'] == $packObj->amount) {
	        if ($contents['status'] == 'SuccessWithWarning' || $contents['status'] == 'Success' || $contents['status'] == 'succeeded') {
	            
	            
	            $user_superpower = $this->userSuperPowers->where('user_id','=', $contents['id'])->first();
	            if($user_superpower)
	            {
	                $user_superpower->user_id =  $contents['id'];
	                $user_superpower->invisible_mode = $invisible;
	                $user_superpower->hide_superpowers = 0;
	                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$packObj->duration} days", strtotime(date('Y-m-d'))));
	            }
	            else
	            {
	                $user_superpower = clone $this->userSuperPowers;
	                $user_superpower->user_id =  $contents['id'];
	                $user_superpower->invisible_mode = $invisible;
	                $user_superpower->hide_superpowers = 0;
	                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$packObj->duration} days", strtotime(date('Y-m-d'))));
	            }
	            $user_superpower->save();
	            $superpower_history = clone $this->superpowerHistory;
	            $superpower_history->user_id =  $contents['id'];
	            $superpower_history->trans_table_id =  $contents["transTable_id"];
	            $superpower_history->superpower_package_id = $packObj->id;
	            $superpower_history->save();
	        }
        }
    }
    
    public function all_packages($gateway_id) {
		
		$packages =  $this->superPowerPackages->all();
		
		$gateway_packages = $this->packagesGateway->where("gateway_id",$gateway_id)->get()->pluck("package_id")->toArray();
		
		$packages_arr = array();
		foreach($packages as $package) {
			
			$obj  = new \StdClass;
			
			$obj->id = $package->id;
			$obj->name = $package->package_name;
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
		 
		$packObj = SuperPowerPackages::withTrashed()->where ('id', $packid)->first();
       

        if ($status == 'SuccessWithWarning' || $status == 'Success' || $status == 'succeeded') {
           
            $user_superpower = UserSuperPowers::where('user_id','=', $contents['id'])->first();
            if($user_superpower)
            {
                $user_superpower->user_id =  $contents['id'];
                $user_superpower->invisible_mode = 0;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("-{$packObj->duration} days", strtotime(date('Y-m-d'))));
            }
            
            $user_superpower->save();
            $superpower_history = clone $this->superpowerHistory;
            $superpower_history->user_id =  $contents['id'];
            $superpower_history->trans_table_id = $contents["transTable_id"];
            $superpower_history->superpower_package_id = $packObj->id;
            $superpower_history->save();
        }
    }
}

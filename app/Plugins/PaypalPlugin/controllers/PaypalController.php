<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\CreditRepository;
use App\Repositories\PaypalRepository;
use App\Repositories\Admin\UtilityRepository;
use Illuminate\Http\Request;
use Auth;
use Omnipay\Omnipay;
use App\Components\Plugin;
use App\Settings;
use App\Package;
use App\SuperPowerPackages;
use stdClass;

use App\Repositories\PaymentRepository;

class PaypalController extends Controller {

    protected $creditRepo;
    protected $paypalRepo;
	protected $paymentRepo;

    public function __construct (CreditRepository $creditRepo, PaypalRepository $paypalRepo,PaymentRepository $paymentRepo) {

        $this->creditRepo = $creditRepo;
        $this->paypalRepo = $paypalRepo;
        $this->paymentRepo = $paymentRepo;
    }

    //this object holds paypal details
    public $gateway = null;
    protected $arr = array();

    public function gatewayInit () {

    	$settings = $this->getPaypalSettings();

    	$this->gateway = Omnipay::create('PayPal_Express');

		$this->gateway->setUsername($settings['paypal_username']);
		$this->gateway->setPassword($settings['paypal_password']);
		$this->gateway->setSignature($settings['paypal_signature']);
		//$this->gateway->currency = $settings['paypal_currency'];
		
		if ($settings['paypal_mode']) {

			if ($settings['paypal_mode'] == 'true') {

				$this->gateway->setTestMode(true);	
			}
			
		}

    }

    protected $credit_package = null;

    protected function setCreditPackage($package_id) 
    {
    	$this->credit_package = $this->creditRepo->getPackById($package_id);
    }

    //this method for initialte redirection credit package purchase or refill
	public function paypal(Request $request) {
		$redirect_url = back()->getTargetUrl();
		//dd($request->all() );
		try {

			$this->gatewayInit();
			
			$url = '/paypal/returnurl';

	        $url.='?id='.Auth::user()->id.'&redirect_url='.$redirect_url;
	        foreach($request->all() as $key => $value)
	            $url.='&'.$key.'='.$value;
			$params = array (

				'amount'      => $request->amount,
				'currency'    => UtilityRepository::get_setting('currency'),//$this->gateway->currency,
				'description' => $request->description,
				'returnUrl'   => url($url),
				'cancelUrl'   => url('/paypal/cancelurl'),
 			);	

			$response = $this->gateway->purchase($params)->send();
			
			$data = $response->redirect();

		} catch (\Exception $e) {

			die($e);
		}
	}


	// this method for redirect url of credit purchase paypal
	public function returnurl(Request $request) {

		$url = '';
		$this->gatewayInit();

		try{
			$params = array(
	
			'amount' => $request->amount,
			'currency' => UtilityRepository::get_setting('currency'),//$this->gateway->currency,
			'description' => 'purchasing package',
			    'returnUrl' => url('/paypal/returnurl'),
			    'cancelUrl' => url('/paypal/cancelurl'),
			);
	
			$response = $this->gateway->completePurchase($params)->send();
			$data = $response->getData();

        	$contents['transaction_id'] = $data['PAYMENTINFO_0_TRANSACTIONID'];
	        $contents['status'] = $data['ACK'];
	        $contents['gateway'] = 'paypal';
	        foreach($request->all() as $key => $value)
			{
				$contents[$key] = $value;
			}
				
			$this->paymentRepo->payment_callback($contents);
		}
		catch (\Exception $e) {

			die($e);
		}

		return redirect($request->redirect_url);	 
		 
	}

	public function cancelurl()
	{
		return back();
	}


	// Route::get('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalContoller@showSettings');
	public function showSettings()
	{
		$data = $this->getPaypalSettings();
		
		$data["payment_packages"] = $this->paymentRepo->stored_payment_packages("paypal");
		
		return Plugin::view('PaypalPlugin/settings', $data);
	}


	// Route::post('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalController@saveSettngs');
	public function saveSettngs (Request $request) {

		try {


			if ($request->paypal_username != null && $request->paypal_password != null && 
				$request->paypal_signature != null  &&  $request->paypal_mode != null ) {
				
				UtilityRepository::set_setting('paypal_username', $request->paypal_username);
				UtilityRepository::set_setting('paypal_password', $request->paypal_password);
				UtilityRepository::set_setting('paypal_signature', $request->paypal_signature);
				//Settings::set('paypal_currency', $request->paypal_currency);
				UtilityRepository::set_setting('paypal_mode', $request->paypal_mode);


				return response()->json(['status' => 'success', 'message' => trans('app.success_paypal_set')]);
				
			} else {

				return response()->json(['status' => 'error', 'message' => trans_choice('admin.all_field_required')]);
			}

			

		} catch (\Exception $e) {

			return response()->json(['status' => 'error', 'message' =>trans('app.failed_paypal_set')]);
		}


	}

	//this function returns the paypal gateway 
	//credentials from database settings table
	protected function getPaypalSettings () {

	
		return array(

			'paypal_username'  => UtilityRepository::get_setting('paypal_username'),
			'paypal_password'  => UtilityRepository::get_setting('paypal_password'),
			'paypal_signature' => UtilityRepository::get_setting('paypal_signature'),
			//'paypal_currency'  => Settings::_get('paypal_currency'),
			'paypal_mode'      => UtilityRepository::get_setting('paypal_mode'),
		
		);	

		
	}
}

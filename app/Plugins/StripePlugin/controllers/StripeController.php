<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\StripeRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Package;
use App\Settings;
use App\Plugins;
use App\Components\Plugin;
use Auth;
use App\SuperPowerPackages;
use Illuminate\Http\Request;

use App\Repositories\PaymentRepository;

class StripeController extends Controller
{

    protected $stripeRepo;
	protected $paymentRepo;

    public function __construct(StripeRepository $stripeRepo,PaymentRepository $paymentRepo)
    {
        $this->stripeRepo = $stripeRepo;
        $this->paymentRepo = $paymentRepo;
        
    }

    public function charge(Request $request)
    {
		//dd($request->all());
        $stripe_api_key = $this->stripeRepo->get_stripe_api_key();

        \Stripe\Stripe::setApiKey($stripe_api_key);
        
        // Get the credit card details submitted by the form
        $token2 = $request->stripeToken;

        // Create the charge on Stripe's servers - this will charge the user's card
        try {
          $charge = \Stripe\Charge::create(array(
            "amount" => $request->amount*100, // amount in cents, again
            "currency" => UtilityRepository::get_setting('currency'),
            "source" => $token2,
            "description" => $request->description
            ));
        } catch(\Stripe\Error\Card $e) {
          // The card has been declined
        }

        $contents['transaction_id'] = $charge->balance_transaction;
        $contents['status'] = $charge->status;
        $contents['gateway'] = 'stripe';
        $contents['id'] = Auth::user()->id;
        foreach($request->all() as $key => $value)
			{
				$contents[$key] = $value;
			}
        
        $this->paymentRepo->payment_callback($contents);
       
		return back();
    }

    // Route::get('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalContoller@showSettings');
    public function showSettings()
    {
        $stripe_api_key = UtilityRepository::get_setting('stripe_api_key');
        $stripe_publishable_key = UtilityRepository::get_setting('stripe_publishable_key');
        
        $payment_packages = $this->paymentRepo->stored_payment_packages("stripe");
        
        return Plugin::view('StripePlugin/settings', ['stripe_api_key'=>$stripe_api_key,'publishable_key'=>$stripe_publishable_key,"payment_packages"=>$payment_packages]);
    }

    // Route::post('/admin/pluginsettings/paypal', 'App\Http\Controllers\PaypalController@saveSettngs');
    public function saveSettngs (Request $request) {

            try {
                
                $stripe_api_key = UtilityRepository::set_setting('stripe_api_key',$request->stripe_api_key);
                $stripe_publishable_key = UtilityRepository::set_setting('stripe_publishable_key',$request->stripe_publishable_key);
                
                return response()->json(['status' => 'success', 'message' => trans('admin.success_stripe_set')]);

            } catch (\Exception $e) {

                return response()->json(['status' => 'error', 'message' => trans('admin.failed_stripe_set')]);
            }
    }

}


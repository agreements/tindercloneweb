<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Payment;
//repository use
use Auth;
use Illuminate\Http\Request;
/*
use App\Repositories\CreditRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\Admin\UtilityRepository;
use App\Components\Theme;
use App\Models\Settings;
use App\Models\Package;
*/

/*
use Omnipay\Omnipay;
use App\Components\Plugin;
use App\Models\SuperPowerPackages;
use curl;
*/

//require_once(base_path().'/vendor/paymentwall/paymentwall-php/lib/paymentwall.php');

use App\Repositories\PaymentRepository;

class PaymentGatewayController extends Controller
{
    /*
protected $creditRepo;
    protected $superpowerRepo;
    
    public function __construct(CreditRepository $creditRepo, SuperpowerRepository $superpowerRepo)
    {
        $this->creditRepo = $creditRepo;
        $this->superpowerRepo = $superpowerRepo;
    }
*/

   /*
 public function credits(Request $request)
    {
        $url = url($request->url);
        $post = 'id='.Auth::user()->id.'&email='.Auth::user()->username;
        foreach($request->all() as $key => $value)
            $post.='&'.$key.'='.$value;

        $contents = $this->curlfun($post,$url);
        
        $this->creditRepo->transactionDetails (
                $contents['gateway'],
                $contents['transaction_id'],
                $request->packid,
                $contents['status'],
                Auth::user()->id,
                "topup"
            );
        $contents['package_id'] = $request->packid;
        Plugin::fire('credits_purchased', $contents);
        return back();

    }

    public function superpower(Request $request)
    {
        $url       = url($request->url);
        $auth_user = Auth::user();
        $post      = "id={$auth_user->id}&email={$auth_user->username}&description=superpower_purchase";
        
        foreach($request->all() as $key => $value)
            $post .= "&{$key}={$value}";
        
        $contents = $this->curlfun($post,$url);
        
        $this->superpowerRepo->transactionDetails (
                $contents['gateway'],
                $contents['transaction_id'],
                $request->packid,
                $contents['status'],
                Auth::user()->id,
                $request->invisible
        );

        $contents['package_id'] = $request->packid;
        Plugin::fire('superpower_activated', $contents);
        
        return back();
    }

    public function credits_callback(Request $request)
    {
        $this->creditRepo->transactionDetails (
                $request->gateway,
                $request->transaction_id,
                $request->packid,
                $request->status,
                $request->id,
                "topup"
            );
        Plugin::fire('credits_purchased', $request);
        return response()->json(['msg' => 'success']);

    }

    public function superpower_callback(Request $request)
    {
        
        $this->superpowerRepo->transactionDetails (
                $request->gateway,
                $request->transaction_id,
                $request->packid,
                $request->status,
                $request->id,
                $request->invisible
            );
        Plugin::fire('superpower_activated', $request);
        return response()->json(['msg' => 'success']);
    }

    public function curlfun($post,$url)
    {
        try{    
                 $curl = curl_init();
                 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                 curl_setopt($curl, CURLOPT_URL, $url);
                 //The URL to fetch. This can also be set when initializing a session with curl_init().
                 curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                 //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
                 curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
                 //The number of seconds to wait while trying to connect.
                 if ($post != "") {
                 curl_setopt($curl, CURLOPT_POST, 5);
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                 }
                 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
                 //The contents of the "User-Agent: " header to be used in a HTTP request.
                 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                 //To follow any "Location: " header that the server sends as part of the HTTP header.
                 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
                 //To automatically set the Referer: field in requests where it follows a Location: redirect.
                 curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                 //The maximum number of seconds to allow cURL functions to execute.
                 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                 //To stop cURL from verifying the peer's certificate.
                 $contents = curl_exec($curl);
                 $contents = json_decode(trim($contents), TRUE);
                 curl_close($curl);
                 
                return $contents;
            
            }
            catch(\Exception $e)
             {
                 return response()->json(["error"=> $e->getMessage]);
            }
    }
*/
  
   protected  $paymentRepo;
   
   public function __construct(PaymentRepository $paymentRepo) {
	   
	   $this->paymentRepo = $paymentRepo;
   }
  
    public function payment_packages(Request $request) {
		
		$type =  $request->type;
		$metadata = $request->metadata; 
		
		$payment = $this->paymentRepo->payment_type($type);
	    
		$typeController = app(Payment::get_class($type));
		$packages = $typeController->gateway_packages($metadata);
		
		return response()->json(["package_type" => $payment->type,"gateways" => $packages,"heading" => trans($payment->heading_code), "subheading" => trans($payment->sub_heading_code)]);
    }
    
    public function add_gateway_package(Request $request) {
	    
	    $type= $request->type;
	    $gateway = $request->gateway;
	    $package_id = $request->package_id;
		$this->paymentRepo->add_gateway_package($type,$gateway,$package_id);
		
	}
	
	public function remove_gateway_package(Request $request) {
		$type= $request->type;
	    $gateway = $request->gateway;
	    $package_id = $request->package_id;
		$this->paymentRepo->remove_gateway_package($type,$gateway,$package_id);
		
		
	}

}

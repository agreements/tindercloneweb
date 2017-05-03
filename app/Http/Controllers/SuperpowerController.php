<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//repository use
use Auth;
use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\PaymentRepository;
use App\Components\Theme;

use App\Components\Plugin;

class SuperpowerController extends Controller
{
    protected $creditRepo;
    protected $superpowerRepo;
    
    public function __construct(CreditRepository $creditRepo, SuperpowerRepository $superpowerRepo,PaymentRepository $paymentRepository)    {
        $this->creditRepo = $creditRepo;
        $this->superpowerRepo   = $superpowerRepo;
        $this->paymentRepo = $paymentRepository;
    }

    public function isSuperPowerActivated($id)
    {
        $response = $this->superpowerRepo->isSuperPowerActivated($id);   
        return response()->json($response);
    }
    
    //this function activates super power pack for user
    public function activateSuperPowerpack(Request $request)
    {
        $pack = $this->superpowerRepo->getSuperPowerPack($request->package);

        $user = Auth::User();


        //checking the user whether user has enough balance to purchase the pack or not
        if( $pack->credits <= $user->credits->balance )
        {

            $this->superpowerRepo->activateSuperPowerpack($user, $pack);

            session(['activateSuperPowerPackStatus' => 'Super power activated']);
            return redirect('/credits');
        }
        else
        {
            //can't purchage pack
            session(['activateSuperPowerPackStatus' => 'No enough credit balance']);
            return redirect('/credits');
        }
    }
}

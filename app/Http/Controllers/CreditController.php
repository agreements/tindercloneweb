<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

//repository use
use Auth;
use Illuminate\Http\Request;
use App\Repositories\CreditRepository;
use App\Repositories\UserRepository;
use App\Repositories\SuperpowerRepository;
use App\Repositories\EncounterRepository;
use App\Repositories\VisitorRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SpotlightRepository;
use App\Components\Theme;

class CreditController extends Controller
{
    protected $creditRepo;
	protected $userRepo;	
    protected $superpowerRepo;
    protected $encounterRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $spotRepo;
    
    public function __construct(CreditRepository $creditRepo, UserRepository $userRepo, SuperpowerRepository $superpowerRepo, EncounterRepository $encounterRepo, VisitorRepository $visitorRepo, ProfileRepository $profileRepo, SpotlightRepository $spotRepo)
    {
        $this->creditRepo     = $creditRepo;
        $this->userRepo       = $userRepo;
        $this->superpowerRepo = $superpowerRepo;
        $this->encounterRepo  = $encounterRepo;
        $this->profileRepo    = $profileRepo;
        $this->visitorRepo    = $visitorRepo;
        $this->spotRepo       = $spotRepo;
    }


    public function credits () { 

    	$logUser = Auth::user();

        $packs           = $this->creditRepo->getCreditPackages();
        $superPowerpacks = $this->superpowerRepo->getSuperPowerPackages();
        
        $logId           = $logUser->id;

        // $matches         = $this->encounterRepo->getAllMatchedUsers ($logId);      
        // $like            = $this->encounterRepo->getAllLikes ($logId);
        // $visitors        = $this->visitorRepo->getAllVisitors ($logId);
        // $whoLiked        = $this->encounterRepo->whoLiked ($logId);    
        $spots           = $this->spotRepo->getSpotUsers();
        // $title           = $this->userRepo->getTitle();
        // $credit          = $this->creditRepo->getBalance ($logId);
        $spotCredits     = $this->spotRepo->getSpotCredits();
        $riseup_credits  = $this->creditRepo->getRiseupCredits();
        
        return Theme::view('credits', [

            'packs'           => $packs, 
            'superPowerPacks' => $superPowerpacks,
            // 'matches'         => $matches, 
            'activated'       => true,
            // 'visit'           => $visitors, 
            'logUser'         => $logUser,
            // 'like'            => $like, 
            'spots'           => $spots,
            'spotCredits'     => $spotCredits,
            // 'title'           => $title,
            // 'credit'          => $credit,
            // 'wholiked'        => $whoLiked,
            'riseup_credits'  => $riseup_credits

        ]);
    }


    public function addCredits(Request $request)
    {
    	$pack=$this->creditRepo->getPack($request->package);
	//dd($pack);
    	if($request->gateway=="stripe")
    		return Theme::view('index2',array('pack' => $pack));
	elseif($request->gateway=="paypal")
		return redirect('/paypal'.'/'.$pack->amount);
    	
    }
}

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
use App\Repositories\BlockUserRepository;
use App\Components\Theme;

class BlockUserController extends Controller
{
    protected $creditRepo;
    protected $userRepo;    
    protected $superpowerRepo;
    protected $encounterRepo;
    protected $visitorRepo;
    protected $profileRepo;
    protected $spotRepo;
    protected $blockRepo;
    
    public function __construct(CreditRepository $creditRepo, UserRepository $userRepo, SuperpowerRepository $superpowerRepo, EncounterRepository $encounterRepo, VisitorRepository $visitorRepo, ProfileRepository $profileRepo, SpotlightRepository $spotRepo, BlockUserRepository $blockRepo)
    {
        $this->creditRepo = $creditRepo;
        $this->userRepo   = $userRepo;
        $this->superpowerRepo = $superpowerRepo;
        $this->encounterRepo = $encounterRepo;
        $this->profileRepo = $profileRepo;
        $this->visitorRepo = $visitorRepo;
        $this->spotRepo = $spotRepo;
        $this->blockRepo = $blockRepo;
    }


    //this function shows block users views
    public function showBlokedUsers()
    {
        $logId = Auth::user()->id;
        $logUser = $this->userRepo->getUserById($logId);
        $visit = $this->visitorRepo->getAllVisitors($logId);
        $matches = $this->encounterRepo->getAllMatchedUsers($logId);       
        $like = $this->encounterRepo->getAllLikes($logId);
        $credit = $this->creditRepo->getBalance($logId);
        $whoLiked = $this->encounterRepo->whoLiked($logId);
        $title = $this->userRepo->getTitle();


        //$blocks->users for alll blocked users array and $blocks->count for blockusers array count
        $blocks = $this->blockRepo->getBlockedUsers($logId);
        // dd($blocks);

        return Theme::view('blocked_users',    array(  'logUser' => $logUser,
                                                        'wholiked'=>$whoLiked,
                                                        'blocked' => $blocks,
                                                        'visit'=>$visit,
                                                        'matches'=>$matches,
                                                        'credit'=>$credit,
                                                        'title'=>$title,
                                                        'like'=>$like));
    }


    //this function blockes user by user1 and user2 ids
    public function blockUser (Request $request) {

        try {


            $auth_user_id  = Auth::user()->id;
            $block_user_id = $request->user_id;


            //checking auth user id and user id are not same
            if ($block_user_id && ($auth_user_id != $block_user_id)) {

                $this->blockRepo->blockUser ($auth_user_id, $block_user_id);

                return response()->json(['status' => 'success']);
            }


            throw new \Exception('auth user can not block himself');

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'errors' => [trans('app.failed_block')] ]);
        }

            

        
    }



    public function unblockUser (Request $request) {

        try {


            $auth_user_id  = Auth::user()->id;
            $block_user_id = $request->user_id;


            //checking auth user id and user id are not same
            if ($auth_user_id != $block_user_id) {

                $this->blockRepo->unblockUser ($auth_user_id, $block_user_id);

                return response()->json(['status' => 'success']);
            }


            throw new \Exception('auth user can not unblock himself');

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'errors' => [trans('app.failed_unblock')] ]);
        }


    }





    public function blockedByAuthUser (Request $request) {

        $user = \App\Models\User::find($request->user_id);

        if ($user && Auth::user()->id != $user->id) {

            return response()->json(['status' => 'success', 'blocked' => $user->blocked_by_auth_user()]);
        }

        return response()->json(['status' => 'error']);

    }


    public function blockedAuthUser (Request $request) {

        $user = \App\Models\User::find($request->user_id);

        if ($user && Auth::user()->id != $user->id) {

            return response()->json(['status' => 'success', 'blocked' => $user->blocked_auth_user()]);
        }

        return response()->json(['status' => 'error']);

    }
	
}

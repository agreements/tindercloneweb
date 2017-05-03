<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Components\Theme;
use App\Repositories\VisitorRepository;
use App\Repositories\NotificationsRepository;
use Auth;

class VisitorController extends Controller
{
    protected $profileRepo;
    protected $encounterRepo;
    protected $visitorRepo;
    protected $notifRepo;
    protected $superpowerRepo;
    
    public function __construct(
        VisitorRepository $visitorRepo, 
        NotificationsRepository $notifRepo
    )
    {
        $this->visitorRepo    = $visitorRepo;
        $this->notifRepo      = $notifRepo;
    }

   public function getVisitors()
   {
        $auth_user = Auth::user();

        $total_visitors_count = $this->visitorRepo->getTotalVisitorsCount($auth_user->id);
        $visitors = $this->visitorRepo->getAllVisitors($auth_user->id);

        if($total_visitors_count != 0) {
            $this->notifRepo->clearNotifs("visitor");
        }
      
        $canSeeVisitors = $this->visitorRepo->canSeeVisitors($auth_user);
        
        return Theme::view('plugin.VisitorPlugin.visitor', [
            'visitors'             => $visitors,
            'total_visitors_count' => $total_visitors_count,
            'can_see_visitors'     => $canSeeVisitors
        ]);
   }

}
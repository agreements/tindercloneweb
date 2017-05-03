<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\AbuseManageRepository;
use App\Repositories\Admin\UtilityRepository;
use Hash;
use Validator;



class AbuseManageController extends Controller {

    protected $abuseRepo;
    public function __construct (AbuseManageRepository $abuseRepo) {
        $this->abuseRepo = $abuseRepo;
    }


    
   //this function displays user abuse reports
    //route :: /admin/misc/userabuse
    public function showUserAbuse () {  

        $unseenReports         =  $this->abuseRepo->getAllUnseenUserReports ();
        $seenReports           =  $this->abuseRepo->getAllSeenUserReports ();
        
        
        $totalReports          = $this->abuseRepo->getTotalUserAbuseReports ();
        $totalThisMonthReports = $this->abuseRepo->getThisMonthUserAbuseReports ();
        $totalTodayReports     = $this->abuseRepo->getTodayUserAbuseReports ();
        
        return view('admin.admin_userabuse', [

            'unseens'        => $unseenReports, 
            'seens'          => $seenReports,
            'total'          => $totalReports,
            'totalthismonth' => $totalThisMonthReports,
            'totaltoday'     => $totalTodayReports,

        ]);

    }



    public function doUserAbuseAction (Request $request) {

        if ($this->abuseRepo->doUserAbuseAction($request->_action, $request->data)){
            return response()->json(['status' => 1]);
        } else {
            return response()->json(['status' => 0]);
        }
    }



    public function showPhotoAbuse() {
        
        return view('admin.admin_photoabuse', [

            'unseens'        => $this->abuseRepo->getAllUnseenPhotoReports(), 
            'seens'          => $this->abuseRepo->getAllSeenPhotoReports(),
            'totalreports'   => $this->abuseRepo->getTotalPhotoAbuseReports(),
            'totalthismonth' => $this->abuseRepo->getThisMonthPhotoAbuseReports(),
            'totaltoday'     => $this->abuseRepo->getTodayPhotoAbuseReports()

        ]);
    }


    public function photoAbuseAction(Request $request) {

        $this->abuseRepo->doPhotoAbuseTask($request->_task, $request->data);
        return redirect('admin/misc/photoabuse');
    }


}
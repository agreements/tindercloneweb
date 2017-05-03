<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\InterestManageRepository;
use Validator;


class InterestController extends Controller {

    public function __construct (InterestManageRepository $interestRepo) {
        $this->interestRepo = $interestRepo;
    }


    public function interests () {
 
        $interests      = $this->interestRepo->getInterests ();
        $total          = $this->interestRepo->getTotalInterests ();
        $totalthismonth = $this->interestRepo->getUsersUsingInterests ();
        $totaltoday     = $this->interestRepo->getTodayInterestsAddedByUser ();

        return view('admin.admin_interests', [

            'interests'      =>$interests,
            'total'          => $total,
            'totalthismonth' => $totalthismonth,
            'totaltoday'     => $totaltoday,

        ]);

    }


    public function addInterest (Request $request) {
        $response = $this->interestRepo->addInterest($request->interest);
        return response()->json($response);
    }


    public function deleteInterest ($id) {
        $response = $this->interestRepo->deleteInterest($id);
        return response()->json($response);
    }


}
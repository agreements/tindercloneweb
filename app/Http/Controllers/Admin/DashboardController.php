<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\DashboardRepository;


class DashboardController extends Controller {

    public function __construct (DashboardRepository $dashboardRepo) {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function showDashboard() {
        
        $totalSignUps     = $this->dashboardRepo->getTotalSignUps();
        $thisMonthSignUps = $this->dashboardRepo->getMonthlySignUps();
        $daySignUps       = $this->dashboardRepo->getDaySignUps();
        $countrySignUps   = $this->dashboardRepo->getCountrySignUps();
        $monthlySignUps   = $this->dashboardRepo->getMonthwiseSignUps();   

        return view('admin.dashboard_view', array(

            'totalSignUps'     => $totalSignUps,
            'thisMonthSignUps' => $thisMonthSignUps,
            'daySignUps'       => $daySignUps,
            'countrySignUps'   => $countrySignUps,
            'monthlySignUps'   => $monthlySignUps

        ));
    }


}
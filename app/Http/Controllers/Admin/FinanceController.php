<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Repositories\Admin\FinanceRepository;


class FinanceController extends Controller {

    public function __construct (FinanceRepository $financeRepo) {
        $this->financeRepo = $financeRepo;
    }

    public function financeSettings() {
        
        $totalRevenue        = $this->financeRepo->totalRevenue();
        $thisMonthRevenue    = $this->financeRepo->getMonthlyRevenue();
        $dayRevenue          = $this->financeRepo->getDayRevenue();
        
        $revenueShareGender  = $this->financeRepo->getRevenueShareGender();
        $revenueShareCountry = $this->financeRepo->getRevenueShareCountry();
        $revenueSharePayment = $this->financeRepo->getRevenueSharePayment();
        $revenueYear         = $this->financeRepo->getRevenueYear();
        
        return view('admin.admin_finance_settings', [
            
            'revenueYear'         => $revenueYear, 
            'revenueSharePayment' => $revenueSharePayment, 
            'revenueShareCountry' => $revenueShareCountry, 
            'revenueShareGender'  => $revenueShareGender, 
            'totalRevenue'        =>$totalRevenue,
            'thisMonthRevenue'    =>$thisMonthRevenue,
            'dayRevenue'          =>$dayRevenue
        ]);
    }

}
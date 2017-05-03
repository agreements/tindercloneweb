<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\FinanceRepository;



class BillingController extends Controller {

    public function __construct(FinanceRepository $financeRepo){
        $this->financeRepo = $financeRepo;
    }

    
    public function superpowerHistories (Request $req) {

        $superpower_histories = $this->financeRepo->getSuperpowerHistories();

        return view('admin.superpower_histories', [
            "superpower_histories" => $superpower_histories
        ]);
    }

    public function creditHistories (Request $req) {

        $credit_histories = $this->financeRepo->getCreditHistories();

        return view('admin.credit_histories', [
            "credit_histories" => $credit_histories
        ]);
    }

}
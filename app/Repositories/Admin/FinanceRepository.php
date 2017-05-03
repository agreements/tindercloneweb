<?php

namespace App\Repositories\Admin;

use Hash;
use DB;
use Artisan;

use App\Models\SuperpowerHistory;
use App\Models\CreditHistory;
use App\Models\Transaction;


class FinanceRepository {

    public function __construct(SuperpowerHistory $superpowerHistory, CreditHistory $creditHistory, Transaction $transaction){
        $this->superpowerHistory = $superpowerHistory;
        $this->creditHistory     = $creditHistory;
        $this->transaction       = $transaction;
    }


	public function totalRevenue () {

    	$sum =  DB::select('
    		SELECT sum(amount) AS sum 
    		FROM transaction 
    		WHERE status = "success";'
    	)[0]->sum;

    	return ($sum) ? $sum : 0;
    }



    public function getMonthlyRevenue () {
        $date = date('Y-m');
    	$sum = DB::select('
    		SELECT sum(amount) AS sum 
    		FROM transaction 
    		WHERE status = "success"
    		AND created_at LIKE "'. $date .'-%"'
    	)[0]->sum;

    	return ($sum) ? $sum : 0;
    }


    public function getDayRevenue () {
        $date = date('Y-m-d');
    	$sum = DB::select('
    		SELECT sum(amount) AS sum 
    		FROM transaction 
    		WHERE status = "success"
    		AND created_at LIKE "'. $date .'%"'
    	)[0]->sum;

		return ($sum) ? $sum : 0;
    }


    //this two function returns revenue share by gender wise
    public function getRevenueShareGender () {

		$superpower    = $this->superpowerHistory->all();
		$creditHistory = $this->creditHistory->where("activity", "topup")->get();
        
        $all_histories = array_merge(
            $superpower->all(), 
            $creditHistory->all()
        );

        $arr = array();


        foreach($all_histories as $history) {

            $gender = $history->user->gender;
            $amount = ($history->transaction->status == "success") 
                        ? $history->transaction->amount 
                        : 0;

            $arr[$gender] = (isset($arr[$gender]))
                            ? $arr[$gender] + $amount
                            : $amount;
        }
    	
    	return $arr;
    }


    public function getRevenueShareCountry () {

		$superpower    = $this->superpowerHistory->all();
		$creditHistory = $this->creditHistory->where("activity","topup")->get();

        $all_histories = array_merge(
            $superpower->all(), 
            $creditHistory->all()
        );

		$countries    = [];

        foreach($all_histories as $history) {

            $country = $history->user->country;
            $amount = ($history->transaction->status == "success") 
                        ? $history->transaction->amount 
                        : 0;

            $countries[$country] = (isset($arr[$country]))
                            ? $arr[$country] + $amount
                            : $amount;
        }

    	return $countries;
  	}



  	public function getRevenueSharePayment () {

    	$transactions = $this->getAllTransactions();

    	$payments = [];

    	foreach ($transactions as $transaction) {

    		$gateway = $transaction->gateway;

    		if( !isset($payments[$gateway]) ) {
    			$payments[$gateway] = $transaction->amount;
    		} else {
    			$payments[$gateway] += $transaction->amount;
    		}
    	}

    	return $payments;
    }


    public function getAllTransactions () {
    	
    	return $this->transaction->where('status', '=', 'Success')->get();
    }



    public function getRevenueYear () {

  		return DB::select(
  			'SELECT YEAR(created_at) as year, 
  					sum(amount) as amount 
  			From transaction 
            WHERE status = "success"
  			group by YEAR(created_at)'
  		);
  	}


    public function getSuperpowerHistories () {
        return $this->superpowerHistory->orderBy("created_at", "desc")->paginate(100);
    }

    public function getCreditHistories () {
        return $this->creditHistory->orderBy("created_at", "desc")->paginate(100);
    }


}
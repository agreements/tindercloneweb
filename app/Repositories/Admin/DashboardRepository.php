<?php

namespace App\Repositories\Admin;
use DB;
use App\Models\User;


class DashboardRepository {

	public function __construct(User $user) {
		$this->user = $user;
	}


	public function getTotalSignUps () { return $this->user->where('username', 'NOT LIKE', '%@bot.bot')->orWhere('username',NULL)->count(); }


	public function getMonthlySignUps () {
		$date = date('Y-m');
		$users_no = $this->user->where('created_at', 'like', $date.'-%')->where(function($query){
			$query->where('username', 'NOT LIKE', '%@bot.bot')->orWhere('username',NULL);
		})->count();  

		return $users_no;
	}

	public function getDaySignUps () {
		$date = date('Y-m-d');
		$users_no = $this->user->where('created_at', 'like', $date.'%')->where(function($query){
			$query->where('username', 'NOT LIKE', '%@bot.bot')->orWhere('username',NULL);
		})->count();
		return $users_no;
	}


	public function getCountrySignUps () {

		return DB::select('SELECT country, count(*) as count From user WHERE username NOT LIKE "%@bot.bot" or username is null group by country');
	}


	//this method returns last 12 months signups
	public function getMonthwiseSignUps()
	{			
		$year = date('Y-m');
		$months = [
			'01' => 'Jan', '02' =>'Feb', '03' =>'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', 
			'07' => 'Jul','08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
		];

		$fromDate = date("Y-m-d", strtotime("-12 months"));

		$query = 'SELECT DATE_FORMAT(created_at, "%Y-%m") as date, count(id) as count FROM user WHERE DATE_FORMAT(created_at, "%Y-%m") BETWEEN "'.$fromDate.'" AND "'.date('Y-m-d').'" and (username NOT LIKE "%@bot.bot" OR username is null) GROUP BY DATE_FORMAT(created_at, "%Y-%m")';
		
		$sql=DB::select($query);
			

		$count = array();

		for($i = 11; $i >= 1; $i--)
		{
			$fromDate = date("Y-m", strtotime("-". $i ." months"));	
			$count[$fromDate] = 0;
		}

		
		foreach ($sql as $key => $value) {
			$count[$value->date] = $value->count;
		}


		$main_count = array();
		foreach ($count as $key => $value) {
			
			$temp = explode('-', $key)[1];
			$key = str_replace("-$temp", "-$months[$temp]", $key);

			$main_count[$key] = $value;
		}
		
		return $main_count;
	}

}
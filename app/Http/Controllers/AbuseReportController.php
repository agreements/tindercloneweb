<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use stdCLass;
use DB;

use App\Repositories\AbuseReportRepository;

use App\Components\Theme;
use App\Components\Plugin;




class AbuseReportController extends Controller
{

	protected $abuseRepo;

	public function __construct(AbuseReportRepository $abuseRepo) {

		$this->abuseRepo = $abuseRepo;
	}



	public function doPhotoReport (Request $request) {

		try {

			if (!$request->photo_name || !$request->reason) {

				return response()->json(['status' => 'error']);
			}


			$reporting_user_id = Auth::user()->id;
			$reported_user_id  = 0;
			$reported_photo_id = 0;

			$success = $this->abuseRepo->get_userid_photoid_by_photo_name(
							$request->photo_name, 
							$reported_user_id, 
							$reported_photo_id
			);

			if ($success && $reported_user_id != $reporting_user_id) {

				$this->abuseRepo->doPhotoReport ($reporting_user_id, $reported_user_id, $reported_photo_id, $request->reason);

				return response()->json([

					'status' => 'success', 
					'photo'  => $request->photo_name

				]);

			}

			return response()->json(['status' => 'error']);	
			

		} catch (\Exception $e) {

			return response()->json(['status' => 'error']);

		}


	}


	//this funciton calls for report user abuse
	public function reportUserAbuse (Request $request) {

		try {

			if (!$request->userid || !$request->reason) {

				return response()->json(['status' => 'error']);

			} 


			$log_user_id = Auth::user()->id;


			if ($this->abuseRepo->validate_user_id ($log_user_id, $request->userid)) {

				$this->abuseRepo->reportUserAbuse($log_user_id, $request->userid, $request->reason);
				return response()->json(['status' => 'success']);

			}

			return response()->json(['status' => 'error']);

		} catch (\Exception $e) {

			return response()->json(['status' => 'error']);

		}

	}

}


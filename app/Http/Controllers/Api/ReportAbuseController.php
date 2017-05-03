<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AbuseReportRepository;




class ReportAbuseController extends Controller {

	protected $abuseRepo;
	public function __construct () {
		$this->abuseRepo = app("App\Repositories\AbuseReportRepository");
	}

	public function reportUser (Request $req) {

		$auth_user = $req->real_auth_user;
		$user_id   = $req->reported_user_id;
		$reason    = $req->reason;

		if (!$user_id) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Reported user\'s id is required',
				]
			]);
		}

		if (!$reason) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Reason is required',
				]
			]);
		}



		if ($this->abuseRepo->validate_user_id ($auth_user->id, $user_id)) {

			$this->abuseRepo->reportUserAbuse($auth_user->id, $user_id, $reason);
			return response()->json([
    			'status' => 'success',
	    		 'success_data' => [
	    		 	"success_text" => "Report against user done successfully."
	    		 ]
    		]);

		}

		return response()->json([
			"status" => 'error', 
			'error_data' => [
				"error_text" => 'Failed to report against user',
			]
		]);

		
	}

	public function reportPhoto (Request $req) {

		$auth_user = $req->real_auth_user;
		$photo_name = $req->photo_name;
		$reason = $req->reason;

		if (!$photo_name) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Photo name is required',
				]
			]);
		}

		if (!$reason) {
			return response()->json([
				"status" => 'error', 
				'error_data' => [
					"error_text" => 'Reason is required',
				]
			]);
		}


		$reporting_user_id = $auth_user->id;
		$reported_user_id  = 0;
		$reported_photo_id = 0;

		$success = $this->abuseRepo->get_userid_photoid_by_photo_name(
			$photo_name, $reported_user_id, $reported_photo_id
		);

		if ($success && $reported_user_id != $reporting_user_id) {

			$this->abuseRepo->doPhotoReport (
				$reporting_user_id, $reported_user_id, $reported_photo_id, $reason
			);

			return response()->json([
    			'status' => 'success',
	    		 'success_data' => [
	    		 	"success_text" => "Report against photo done successfully."
	    		 ]
    		]);
    	}

    	return response()->json([
			"status" => 'error', 
			'error_data' => [
				"error_text" => 'Failed to report against photo',
			]
		]);

	}


}


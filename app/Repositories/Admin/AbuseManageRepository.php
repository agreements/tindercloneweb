<?php

namespace App\Repositories\Admin;

use App\Models\PhotoAbuseReport;
use App\Models\UserAbuseReport;
use App\Repositories\Admin\UtilityRepository;

class AbuseManageRepository {

    public function __construct(PhotoAbuseReport $photoAbuseReport, UserAbuseReport $usreAbuseReport) {
        $this->photoAbuseReport = $photoAbuseReport;
        $this->usreAbuseReport  = $usreAbuseReport;
    }

	//this function retrives all unseen user reports
	public function getAllUnseenUserReports() {

		$unseens = $this->usreAbuseReport->where('action', '=', 'unseen')->orderBy('created_at', 'desc')->get();
		$unseens->count = count($unseens);
		return $unseens;
	}

	public function getAllSeenUserReports() {

		$seens = $this->usreAbuseReport->where('action', '=', 'seen')->orderBy('created_at', 'desc')->get();
		$seens->count = count($seens);
		return $seens;
	}

	public function getTotalUserAbuseReports () {
    	
    	return $this->usreAbuseReport->all()->count();	
    }

    public function getThisMonthUserAbuseReports () {

        $month = date('Y-m');
    	return $this->usreAbuseReport->where('created_at', 'like', $month . '-%' )->count();
    }

    public function getTodayUserAbuseReports () {
        $today = date('Y-m-d');
    	return $this->usreAbuseReport->where('created_at', 'like', $today . ' %' )->count();
    }

    public function setMarkSeens($ids) {

		$this->usreAbuseReport->whereIn('id', $ids)
          			->update(['action' => 'seen']);
		
	}

	public function setMarkUnseens($ids) {

		$this->usreAbuseReport->whereIn('id', $ids)
          			->update(['action' => 'unseen']);
	}

	public function getAllUnseenPhotoReports() {

    	$unseen = $this->photoAbuseReport->orderBy('updated_at', 'desc')->where('status', '=', 'unseen')->get();

    	$unseen->count = count($unseen);
    	
    	return $unseen;
    }


    public function getAllSeenPhotoReports() {

    	$seen = $this->photoAbuseReport->orderBy('updated_at', 'desc')->where('status', '=', 'seen')->orWhere('status', '=', 'deleted')->get();

    	$seen->count = count($seen);

    	return $seen;
    }

    public function getTotalPhotoAbuseReports () {

  		return $this->photoAbuseReport->all()->count();
  	}

  	 public function getThisMonthPhotoAbuseReports () {
        $month = date('Y-m');
        return $this->photoAbuseReport->where('created_at', 'like', $month . '-%' )->count();
    }

    public function getTodayPhotoAbuseReports () {
        $today = date('Y-m-d');
    	return $this->photoAbuseReport->where('created_at', 'like', $today . ' %' )->count();
    }

    public function setMarkSeenPhotoReport($ids) {
    	$this->photoAbuseReport->whereIn('id', $ids)->update(["status" => "seen"]);
    }


    public function setMarkUnseenPhotoReport($id) {

    	$report = $this->photoAbuseReport->find($id);

    	if($report) {
    		if($report->status = 'deleted')
    		{
    			$report->photos()->withTrashed()->first()->restore();
    			
    		}	
    		
    		$report->status = 'unseen';
    		$report->save();
    	}
    }



    public function removePhotoReport ($id) {

    	$report = $this->photoAbuseReport->find($id);

    	if ($report) {

    		$report->status = 'deleted';
    		$report->save(); 

    		if ($report->reporteduser->profile_pic_url == $report->photos->photo_url) {

                $report->reporteduser->profile_pic_url = UtilityRepository::get_setting('default_'.$report->reporteduser->gender);

    			$report->reporteduser->save();
    		}

    		if (!isset($report->withTrashed()->photos->deleted_at)) {

    			$report->photos->delete();	
    		}
    		
    	}
    }


    public function recoverPhotoReport($id){

    	$report = $this->photoAbuseReport->find($id);

    	if($report)
    	{
    		$report->status = 'seen';
    		$report->photos()->withTrashed()->first()->restore();

    		$report->save();    		
    	}
    }


    public function doUserAbuseAction($action, $data) {

        if ($data) {

            $datas = explode(',', $data);

            switch ($action) {
                case 'mark_seen':
                    $this->setMarkSeens($datas);
                    return true;
                    break;

                case 'mark_seen':
                    $this->setMarkUnseens($datas);
                    return true;
                    break;
                
                default:
                    return false;
                    break;
            }

        }

    }


    public function doPhotoAbuseTask($task, $data) {
        
        $datas = explode(",", $data);

        if (count($datas) > 0) {

            switch ($task) {
          
                case 'markseen':
                    $this->setMarkSeenPhotoReport($id);
                    session(['photoabuseactionstatus' => 'success']);
                    break;


                case 'markunseen':
                    foreach($datas as $id) {
                        $this->setMarkUnseenPhotoReport($id);
                    }
                    session(['photoabuseactionstatus' => 'success']);           
                    break;


                case 'remove':
                    foreach($datas as $id) {
                        $this->removePhotoReport($id);
                    }
                    session(['photoabuseactionstatus' => 'success']);           
                    break;

             
                case 'recover':
                    foreach($datas as $id) {
                        $this->recoverPhotoReport($id);
                    }
                    session(['photoabuseactionstatus' => 'success']);
                    break;               
            }

        }
    }


}
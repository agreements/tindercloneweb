<?php

namespace App\Repositories\Admin;
use App\Models\Interests;
use App\Models\UserInterests;
use App\Components\Plugin;
use DB;

class InterestManageRepository {

    public function __construct(Interests $interests, UserInterests $userInterests) {
        $this->interests     = $interests;
        $this->userInterests = $userInterests;
    }

	public function getInterests () {

        $interests = $this->interests->orderBy('created_at', 'desc')->paginate(100);
		$interests->setPath('interests');
		return $interests;
    }

    public function getTotalInterests () { return $this->interests->all()->count(); }

    public function getUsersUsingInterests () {
    	return  $this->userInterests->groupBy('userid')
    						->get()
    						->count();
    }

    public function getTodayInterestsAddedByUser () {
        $today = date ('Y-m-d');
    	return $this->userInterests->where('created_at', 'like', $today.' %')
    						->groupBy('userid')
    						->get()
    						->count();
    }

    public function addInterest ($str) {

        if (strlen($str) < 3) {
            return ['status' => 'error'];
        }

        if( $this->interests->where('interest', $str)->first() ) {
            return ['status' => 'existed'];
        }

        $interest = clone $this->interests;
        $interest->interest = $str;
        $interest->save();

        Plugin::fire('interest_added', $interest);

        return ['status' => 'success', 'id' => $interest->id, 'timestamp' => $interest->created_at->format('Y-m-d h:m:s')];
    	
    }

    public function deleteInterest ($id) {

        $interests     = $this->interests;
        $userInterests = $this->userInterests;

        DB::transaction(function () use($id, $interests, $userInterests) {
            $userInterests->where('interestid', $id)->forceDelete();
            $interests->where('id', $id)->forceDelete();
        });

        return ["status" => "success"];
    }
}
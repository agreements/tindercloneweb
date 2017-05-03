<?php

namespace App\Repositories\Admin;
use DB;

//models
use App\Models\User;
use App\Models\BlockUsers;
use App\Models\CreditHistory;
use App\Models\Credit;
use App\Models\Encounter;
use App\Models\Match;
use App\Models\Photo;
use App\Models\SuperpowerHistory;
use App\Models\UserSuperPowers;
use App\Models\UserAbuseReport;
use App\Models\UserInterests;
use App\Models\UserSettings;
use App\Models\UserSocialLogin;
use App\Models\UserFields;
use App\Models\PhotoAbuseReport;
use App\Models\Profile;
use App\Models\Visitor;
use App\Models\RiseUp;
use App\Models\Spotlight;
use App\Models\Notifications;
use App\Models\NotificationSettings;

class UserManagementRepository {

	public function __construct(
		User $user, 
		BlockUsers $blockUsers, 
		CreditHistory $creditHistory,
		Credit $credit,
		Encounter $encounter,
		Match $match,
		Photo $photo,
		SuperpowerHistory $superpowerHistory,
		UserSuperPowers $userSuperPowers,
		UserAbuseReport $userAbuseReport,
		UserInterests $userInterests,
		UserSettings $userSettings,
		UserSocialLogin $userSocialLogin,
		UserFields $userFields,
		PhotoAbuseReport $photoAbuseReport,
		Profile $profile,
		Visitor $visitor,
		RiseUp $riseUp,
		Spotlight $spotlight,
		Notifications $notifications,
		NotificationSettings $notificationSettings
	){

		$this->user                 = $user;
		$this->blockUsers           = $blockUsers;
		$this->creditHistory        = $creditHistory;
		$this->credit               = $credit;
		$this->encounter            = $encounter;
		$this->match                = $match;
		$this->photo                = $photo;
		$this->superpowerHistory    = $superpowerHistory;
		$this->userSuperPowers      = $userSuperPowers;
		$this->userAbuseReport      = $userAbuseReport;
		$this->userInterests        = $userInterests;
		$this->userSettings         = $userSettings;
		$this->userSocialLogin      = $userSocialLogin;
		$this->userFields           = $userFields;
		$this->photoAbuseReport     = $photoAbuseReport;
		$this->profile              = $profile;
		$this->visitor              = $visitor;
		$this->riseUp               = $riseUp;
		$this->spotlight            = $spotlight;
		$this->notifications        = $notifications;
		$this->notificationSettings = $notificationSettings;

	}

	public function activateUsersSuperpower($user_ids, $duration_days) 
	{
		if($duration_days < 1) {
			return ["status" => "error", "error_type" => "DURATION_ERROR"];
		}

		foreach($user_ids as $user_id) {
			$user_superpower = $this->userSuperPowers->where('user_id', $user_id)->first();

			if ($user_superpower) {
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$duration_days} days", strtotime(date('Y-m-d'))));

			} else {
				$user_superpower = clone $this->userSuperPowers;
                $user_superpower->user_id = $user_id;
                $user_superpower->invisible_mode = 0;
                $user_superpower->hide_superpowers = 0;
                $user_superpower->expired_at = date('Y-m-d', strtotime("+{$duration_days} days", strtotime(date('Y-m-d'))));
			}

			$user_superpower->save();

		}

		return ['status' => 'success'];
	}

	
	public function doAction($action, $user_ids){

		switch ($action) {
			case 'verify':
				$this->verifyUsers($user_ids);
				break;

			case 'unverify':
				$this->unvefiryUsers($user_ids);
				break;

			case 'activate':
				$this->activateUsers($user_ids);
				break;

			case 'deactivate':
				$this->deactivateUsers($user_ids);
				break;
			
			default:
				return ['status' => 0];
				break;
		}

		return ['status' => 1];

	}



	/* This function returns all users 
       and also can access profiles of users by $user->profile
    */
	public function getAllActivatedUsers () {

		$users = $this->user->withTrashed()
					->where('activate_user', 'activated')
					->Where('username', 'not like', '%@bot.bot')
					->orWhere('username', NULL)
					->orderBy('created_at', 'desc')
					->paginate(100);

		$users->setPath('usermanagement');

		return $users;
	}


	public function getAllDeactivatedUsers () {

		$users = $this->user->withTrashed()
					->where('activate_user', 'deactivated')
					->Where('username', 'not like', '%@bot.bot')
					->orWhere('username', NULL)
					->orderBy('created_at', 'desc')
					->paginate(100);
					
		$users->setPath('deactivate_usermanagement');
		
		return $users;
	}


	public function verifyUsers ($userIds) {

    	if (is_array($userIds)) {

    		$this->user->whereIn('id', $userIds)
    			->update(['verified' => 'verified']);
    	}
    
    }


    public function unvefiryUsers ($userIds) {

    	if (is_array($userIds)) {

			$this->user->whereIn('id', $userIds)
				->update(['verified' => 'unverified']);
    		
    	}

    }

    public function activateUsers($userIds) {

    	if (is_array($userIds)) {
			
			$this->user->whereIn('id', $userIds)
				->update(['activate_user' => 'activated']);
    	
    	}

		

    }


    public function deactivateUsers($userIds) {

    	if (is_array($userIds)) {
			
			$this->user->whereIn('id', $userIds)
				->update([
					'activate_user'  =>'deactivated', 
					'activate_token' => rand()
				]);
    	
    	}

    }


	public function delete_users_permenently ($user_ids) {

		DB::transaction(function () use($user_ids) {
    		
			$this->deleteFromBlockUsersTable($user_ids);
			
			$this->deleteFromCreditHistoryTable($user_ids);
			$this->deleteFromCreditTable($user_ids);
			$this->deleteFromEncounterTable($user_ids);
			$this->deleteFromMatchTable($user_ids);
			$this->deleteFromNotificationsTable($user_ids);
			$this->deleteFromNotificationSettingsTable($user_ids);
			$this->deleteFromPhotoTable($user_ids);
			$this->deleteFromProfileTable($user_ids);
			$this->deleteFromUserTable($user_ids);
			$this->deleteFromRiseUpTable($user_ids);
			$this->deleteFromSpotlightTable($user_ids);
			$this->deleteFromSuperpowerHistoryTable($user_ids);
			$this->deleteFromUserAbuseReportTable($user_ids);
			$this->deleteFromUserInterestsTable($user_ids);
			$this->deleteFromUserFieldsTable($user_ids);
			$this->deleteFromPhotoAbuseReportTable($user_ids);
			$this->deleteFromUserSettingsTable($user_ids);
			$this->deleteFromUserSocialLoginTable($user_ids);
			$this->deleteFromUserSuperPowersTable($user_ids);
			$this->deleteFromVisitorTable($user_ids);

		});

	}


	public function deleteFromBlockUsersTable ($user_ids) {
		$this->blockUsers->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
	}


	public function deleteFromCreditHistoryTable ($user_ids) {
		$this->creditHistory->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromCreditTable ($user_ids) {
		$this->credit->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}


	public function deleteFromEncounterTable ($user_ids) {
		$this->encounter->withTrashed()->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
	}

	public function deleteFromMatchTable ($user_ids) {
		$this->match->withTrashed()->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
	}

	public function deleteFromNotificationsTable ($user_ids) {
		$this->notifications->withTrashed()->whereIn('from_user', $user_ids)->orWhereIn('to_user', $user_ids)->forceDelete();
	}

	public function deleteFromNotificationSettingsTable ($user_ids) {
		$this->notificationSettings->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromPhotoTable ($user_ids) {
		$this->photo->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromProfileTable ($user_ids) {
		$this->profile->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromUserTable ($user_ids) {
		$this->user->withTrashed()->whereIn('id', $user_ids)->forceDelete();
	}

	public function deleteFromRiseUpTable ($user_ids) {
		$this->riseUp->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromSpotlightTable ($user_ids) {
		$this->spotlight->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromSuperpowerHistoryTable ($user_ids) {
		$this->superpowerHistory->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
	}

	public function deleteFromVisitorTable ($user_ids) {
		$this->visitor->withTrashed()->whereIn('user1', $user_ids)->orWhereIn('user2', $user_ids)->forceDelete();
	}

	public function deleteFromUserAbuseReportTable ($user_ids) {
		$this->userAbuseReport->withTrashed()->whereIn('reporting_user', $user_ids)->orWhereIn('reported_user', $user_ids)->forceDelete();
	}

	public function deleteFromPhotoAbuseReportTable ($user_ids) {
		$this->photoAbuseReport->withTrashed()->whereIn('reporting_user', $user_ids)->orWhereIn('reported_user', $user_ids)->forceDelete();
	}

	public function deleteFromUserInterestsTable ($user_ids) {
		$this->userInterests->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromUserFieldsTable ($user_ids) {
		$this->userFields->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
	}

	public function deleteFromUserSettingsTable ($user_ids) {
		$this->userSettings->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromUserSocialLoginTable ($user_ids) {
		$this->userSocialLogin->withTrashed()->whereIn('userid', $user_ids)->forceDelete();
	}

	public function deleteFromUserSuperPowersTable ($user_ids) {
		$this->userSuperPowers->withTrashed()->whereIn('user_id', $user_ids)->forceDelete();
	}

}
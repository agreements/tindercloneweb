<?php

namespace App\Repositories;

use App\Models\BlockUsers;
use stdClass;
use App\Components\Plugin;

class BlockUserRepository
{
	public function __construct(BlockUsers $block_users){
		
		$this->block_users = $block_users;
		
	}


	//this function returns all blocked users iss as array by id passed and id blocked by other users
	/*public function getAllBlockedUsersIds($id)
	{
		$blockedIds = array(-1);

		$blocks = $this->block_users->where('user1', '=', $id)->get();

		foreach($blocks as $block)
			array_push($blockedIds, $block->user2);


		$blocks = $this->block_users->where('user2', '=', $id)->get();

		foreach($blocks as $block)
			array_push($blockedIds, $block->user1);


		return $blockedIds;
	}*/

	protected $blockedIdsArray;
	public function getAllBlockedUsersIds($userId)
	{

		if( isset($this->blockedIdsArray[$userId]) ) {
			return $this->blockedIdsArray[$userId];
		}


		$blocks = $this->block_users->select(
			\DB::raw("IF(user1={$userId},user2,user1) as user_id")
		)->where('user1', $userId)
		->orWhere('user2', $userId)->get()->toArray();

		return $this->blockedIdsArray[$userId] = $this->flatten($blocks);
	}

	public function flatten($array)
	{
		$flat_array = [];
		array_walk($array, function($item) use(&$flat_array){
			$flat_array[] = $item['user_id'];
		});

		return $flat_array;
	}



	//this function returns blocked users objecs by id passed
	public function getBlockedUsers($user_id)
	{
		
		$blocked = new stdClass;
		$blocked->users = [];

		$blocks = $this->block_users->where('user1', $user_id)->get();
		$blocked->count = count($blocks);

		if($blocked->count > 0)
		{
			foreach($blocks as $block) {
				array_push($blocked->users, $block->user);	
			}
		}
		
		return $blocked;
	}



	public function blockUser($auth_user_id, $block_user_id) {

		$block = $this->block_users->where('user1', '=', $auth_user_id)->where('user2', '=', $block_user_id)->first();

		if ($block == null) {

			$block = $this->block_users;

			$block->user1 = $auth_user_id;
			$block->user2 = $block_user_id;

			$block->save();
			
			Plugin::fire('user_blocked', $block);

		}
			
	}


	public function unblockUser($auth_user_id, $block_user_id) {

		$block = $this->block_users->where('user1', '=', $auth_user_id)->where('user2', '=', $block_user_id)->first();

		if ($block) {

			$block->delete();
		}
	}


}

<?php

namespace App\Repositories;

use Validator;
use App\Models\NudePhoto;
use Hash;
use App\Models\Settings;
use App\Models\Photo;
use DB;

class PhotoNudityRepository
{
	
	public function __construct(NudePhoto $nudePhoto, Settings $settings, Photo $photo)
	{
		$this->settings = $settings;
		$this->nudePhoto = $nudePhoto;
		$this->photo = $photo;
	}

	public function getNudePhotos () {
		return $this->nudePhoto->withTrashed()->orderBy('created_at', 'desc')->paginate(100);
	}

	public function deleteNudePhotos($user_ids) {
		$this->nudePhoto->whereIn('user_id', $user_ids)->forceDelete();
	}

	public function getNudePhotoById ($id) {
		return $this->nudePhoto->find($id);
	}


	public function getNudePhotoByPhotoname($photo_name)
	{
		return $this->nudePhoto->where('photo_name', $photo_name)->first();
	}


	public function reportNudePhoto($user_id, $photo_name)
	{
		try {

			DB::transaction(function() use($photo_name, $user_id){
				$this->createOrUpdatePhotoNudityRecord($user_id, $photo_name);
			});

			return response()->json(['status' => 'success']);

		} catch(\Exception $e) {
			return response()->json(['status' => 'error', 'error_type' => "UNKNOWN_ERROR"]);
		}
	}

	public function markPhotoAsNude($photo_name)
	{
		$photo = $this->photo->where('photo_url', $photo_name)->first();
		
		if(!$photo) return false;
		
		$photo->nudity = 1;
		$photo->is_checked = 1;
		$photo->save();
		return $photo;
	}

	public function markPhotoChecked($photo_name)
	{
		$photo = $this->photo->where('photo_url', $photo_name)->first();
		
		if(!$photo) return false;
		
		$photo->is_checked = 1;
		$photo->save();
		return $photo;
	}


	public function createOrUpdatePhotoNudityRecord($user_id, $photo_name, $status = 'unseen')
	{
		$nudePhoto = $this->getNudePhotoByPhotoname($photo_name);

		if($nudePhoto) {
			$nudePhoto->status = $status; 
		} else {

			$nudePhoto = new $this->nudePhoto;
			$nudePhoto->user_id = $user_id;
			$nudePhoto->photo_name = $photo_name;
			$nudePhoto->status = $status;
		}

		$photo = $this->markPhotoAsNude($photo_name);
		$this->changeProfilePictureToDefault($photo);

		$nudePhoto->save();
		return $nudePhoto;
	}
	
	public function changeProfilePictureToDefault(&$photo)
	{
		if(!$photo) {
			return false;
		}

		$user = $photo->user;
		if($user &&  $user->profile_pic_url == $photo->photo_url) {
			
			$user->profile_pic_url = $this->settings->get('default_'.$user->gender);
			$user->save();

			return true;
		}
	}


	public function createNudePhotoRecord ($data) {
		$nude_photo = new $this->nudePhoto;
		foreach ($data as $key => $value) {
			$nude_photo->$key = $value;
		}
		$nude_photo->save();
		return $nude_photo;
	}

	public function markSeen($photo_ids) {
		foreach ($photo_ids as $id) {
			$nude_photo = $this->nudePhoto->find($id);
			if ($nude_photo && $nude_photo->status != 'deleted') {
				$nude_photo->status = 'seen';
				$nude_photo->save();
			}
		}
		return true;
	}

	public function get_nudity_percentage()
	{
		return$this->settings->get('nudity_percentage');
	}

	public function set_nudity_percentage () {

		return $this->settings->set('nudity_percentage');
	}


}
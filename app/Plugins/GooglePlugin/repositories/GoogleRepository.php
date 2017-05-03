<?php

namespace App\Repositories;

use App\Models\Photo;

class GoogleRepository {

	public function insertPhoto($logId,$image_id,$url)
	{
		$album = new Photo;

        $album->userid          = $logId;
        $album->source_photo_id = $image_id;
        $album->photo_source    = 'google';
        $album->photo_url       = $url;
        
        $album->save();
	}
}

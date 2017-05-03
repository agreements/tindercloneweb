<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\PhotoNudityRepository;
use Illuminate\Http\Request;
use App\Models\NudePhoto;
use App\Components\Plugin;
use Auth;

 
class PhotoNudityController extends Controller {

    protected $photonudeRepo;

    public function __construct (PhotoNudityRepository $photonudeRepo) {
        $this->photonudeRepo = $photonudeRepo;
    }

   	
   	public function reportNudePhoto (Request $req) {
   		$response = $this->photonudeRepo->reportNudePhoto(Auth::user()->id, $req->photo_name);
         return response()->json($response);
   	}



   	public function showNudePhotos () {
   		$nude_photos = $this->photonudeRepo->getNudePhotos();
   		
   		$nudity_percentage = $this->photonudeRepo->get_nudity_percentage();
   		return Plugin::view('PhotoNudityPlugin/photo_report_list', [
   			"nude_photos" => $nude_photos,"nudity_percentage"=>$nudity_percentage
   		]);
   	}


   	public function savePercentage(Request $request) {
	   	
	   	 try {
                
                $nudity_percentage = UtilityRepository::set_setting('nudity_percentage',$request->nudity_percentage);
                
                return response()->json(['status' => 'success', 'message' => trans('admin.success_nudity_percentage')]);

            } catch (\Exception $e) {

                return response()->json(['status' => 'error', 'message' => trans('admin.failed_nudity_percentage')]);
            }
	   	
   	}

   	public function deletePhoto (Request $req) {

   		$nude_photo_ids = $req->photo_ids;

         if (strlen($nude_photo_ids) > 0) {
            $array = explode(",", $nude_photo_ids);
            foreach ($array as $nude_photo_id) {
               $nude_photo = $this->photonudeRepo->getNudePhotoById($nude_photo_id);

               if ($nude_photo) {
                  $nude_photo->status = 'deleted';
                  $nude_photo->save();

                  $nude_photo->photo()->delete();

                  $user = $nude_photo->user;
                  if ($user->profile_pic_url == $nude_photo->photo_name) {
                     $user->profile_pic_url = UtilityRepository::get_setting('default_'.$user->gender);
                     $user->save();
                  }
               }
            }
            return response()->json(['status' => 'success', 'msg' => trans('admin.nude_delete_success')]);
         }
         return response()->json(['status' => 'error', 'msg' => trans('admin.select_photos')]);
   	}

   	public function recoverPhoto(Request $req) {
         $photo_ids = $req->photo_ids;
         if (strlen($photo_ids) > 0) {
            $photo_ids = explode(",", $photo_ids);
            foreach ($photo_ids as $nude_photo_id) {
               $nude_photo = $this->photonudeRepo->getNudePhotoById($nude_photo_id);

               if ($nude_photo) {
                  $nude_photo->status = 'seen';
                  $nude_photo->save();

                  $nude_photo->photo()->restore();

                  $user = $nude_photo->user;
               }
            }
            return response()->json(['status' => 'success', 'msg' => trans('admin.nude_recover_success')]);
         }
         return response()->json(['status' => 'error', 'msg' => trans('admin.select_photos')]);
   	}


   	public function seenPhoto (Request $req) {
         $photo_ids = $req->photo_ids;
         if (strlen($photo_ids) > 0) {
            $photo_ids = explode(",", $photo_ids);
            $this->photonudeRepo->markSeen($photo_ids);
            return response()->json(['status' => 'success', 'msg' => trans('admin.nude_seen_success')]);
         }

         return response()->json(['status' => 'error', 'msg' => trans('admin.select_photos')]);
   	}


      public function markPhotoChecked(Request $req)
      {         
         $this->photonudeRepo->markPhotoChecked($req->photo_name);
         return response()->json(['status' => "success"]);
      }

}


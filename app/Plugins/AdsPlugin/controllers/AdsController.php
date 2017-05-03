<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\TestModel;

use App\Components\Plugin;
use App\Repositories\AdsRepository;
use Auth;
use App\Models\Ads;
use App\Models\AdsActive;
use Illuminate\Http\Request;
use App\Components\Theme;
use App\Models\Settings;

class AdsController extends Controller
{
      protected $adsRepo;

    public function __construct(AdsRepository $adsRepo)
    {
        $this->adsRepo = $adsRepo;
    }
    
    public function show () {

        $banner = $this->adsRepo->getAllAds();
          
        $active = $this->adsRepo->getSetAds();

        return Plugin::view('AdsPlugin/settings', [
         
            'adds' => $banner, 
            'active' => $active
        ]);
        
    }

    public function add_banner (Request $request) {

      try {

            $prev = $this->adsRepo->getAdByName($request->name);

            if ($prev) {
              return response()->json(['status' => 'error', 'message' => trans('app.advertise_name').' '.$request->name. trans('app.already_exists')]);
            }

            $this->adsRepo->createAd($request->name, $request->htmlcode);

            return response()->json(['status' => 'success', 'message' => $request->name. ' '. trans('app.ad_success_create')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.fail_save').' ' .$request->name. ' '.trans('app.advertise')]);
        }
        
    }

    public function update(Request $request) {
      
      try {
        
          $banner = $this->adsRepo->getAdById($request->id);
          if ($banner) {

              $banner->name = $request->name;
              $banner->code = $request->htmlcode;
              $banner->save();

              return response()->json(['status' => 'success', 'message' => $request->name. ' '.trans('ap.ad_success_update')]);

          } else {

              return response()->json(['status' => 'error', 'message' => trans('fail_update_ad').' '. $request->name]);
          }
          
          

      } catch (\Exception $e) {

          return response()->json(['status' => 'error', 'message' => trans('fail_update_ad').' '. $request->name]);
      }
          
    }

    public function delete(Request $request) {

        try {

          $banner = $this->adsRepo->getAdById($request->id);
          $banner->delete();
          
          return response()->json(['status' => 'success', 'message' => trans('app.advertise').' ' . $request->name . ' '.trans('app.delete_success')]);

        } catch (\Exception $e) {

          return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
          

    }

    public function placeholder (Request $request) {

      try {
            $this->adsRepo->setAds($request->all());

            return response()->json(['status' => 'success', 'message' => trans('app.assign_ad_update')]);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => trans('app.assign_ad_fail')]);
        }
            
    }
}